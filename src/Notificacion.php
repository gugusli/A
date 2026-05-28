<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException('vendor/autoload.php no encontrado. Ejecutá composer install.');
}
require_once __DIR__ . '/../vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Notificacion {
    public static function saveSubscription(int $alumno_id, array $sub): void {
        $stmt = DB::get()->prepare(
            'INSERT INTO push_subscriptions (alumno_id, endpoint, p256dh, auth)
             VALUES (:alumno_id, :endpoint, :p256dh, :auth)
             ON CONFLICT (alumno_id, endpoint) DO UPDATE
             SET p256dh=EXCLUDED.p256dh, auth=EXCLUDED.auth'
        );
        $stmt->execute([
            ':alumno_id' => $alumno_id,
            ':endpoint'  => $sub['endpoint'],
            ':p256dh'    => $sub['keys']['p256dh'],
            ':auth'      => $sub['keys']['auth'],
        ]);
    }

    public static function getSubscriptionByAlumno(int $alumno_id): array|false {
        $stmt = DB::get()->prepare('SELECT * FROM push_subscriptions WHERE alumno_id = ? LIMIT 1');
        $stmt->execute([$alumno_id]);
        return $stmt->fetch();
    }

    public static function sendPush(array $subscription, array $payload): bool {
        $publicKey  = getenv('VAPID_PUBLIC');
        $privateKey = getenv('VAPID_PRIVATE');
        $email      = getenv('VAPID_EMAIL') ?: 'mailto:admin@eest5.edu.ar';

        if (!$publicKey || !$privateKey) {
            error_log('VAPID keys no configuradas.');
            return false;
        }

        $auth = [
            'VAPID' => [
                'subject'    => $email,
                'publicKey'  => $publicKey,
                'privateKey' => $privateKey,
            ],
        ];

        $webPush = new WebPush($auth);
        $sub = Subscription::create([
            'endpoint' => $subscription['endpoint'],
            'keys'     => [
                'p256dh' => $subscription['p256dh'],
                'auth'   => $subscription['auth'],
            ],
        ]);

        $report = $webPush->sendOneNotification($sub, json_encode($payload));
        return $report->isSuccess();
    }
}
