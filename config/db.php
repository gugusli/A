<?php
declare(strict_types=1);

class DB {
    private static ?PDO $instance = null;

    public static function get(): PDO {
        if (self::$instance === null) {
            $dsn  = getenv('DB_DSN')  ?: $_ENV['DB_DSN']  ?? '';
            $user = getenv('DB_USER') ?: $_ENV['DB_USER'] ?? '';
            $pass = getenv('DB_PASS') ?: $_ENV['DB_PASS'] ?? '';

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }
}
