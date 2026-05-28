<?php
declare(strict_types=1);
$vapidPublic = htmlspecialchars(getenv('VAPID_PUBLIC') ?: '', ENT_QUOTES, 'UTF-8');
$rol = $_SESSION['rol'] ?? '';
$nombre = htmlspecialchars($_SESSION['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema de Previas - E.E.S.T. N°5' ?></title>
    <link rel="stylesheet" href="/public/css/base.css">
    <?php if ($rol === 'alumno'): ?>
    <link rel="stylesheet" href="/public/css/alumno.css">
    <?php elseif ($rol === 'preceptora'): ?>
    <link rel="stylesheet" href="/public/css/preceptora.css">
    <?php endif; ?>
    <script>const VAPID_PUBLIC = '<?= $vapidPublic ?>';</script>
</head>
<body>
<?php if ($rol): ?>
<nav class="navbar">
    <span class="navbar-brand">E.E.S.T. N°5 — Previas</span>
    <div class="navbar-links">
        <?php if ($rol === 'alumno'): ?>
            <a href="/alumno/dashboard">Inicio</a>
            <a href="/alumno/historial">Historial</a>
        <?php elseif ($rol === 'preceptora'): ?>
            <a href="/preceptora/panel">Panel</a>
            <a href="/preceptora/alumnos">Alumnos</a>
        <?php endif; ?>
        <span class="navbar-user"><?= $nombre ?></span>
        <a href="/logout" class="btn-logout">Salir</a>
    </div>
</nav>
<?php endif; ?>
<main class="container">
<?= $content ?? '' ?>
</main>
<footer class="footer">E.E.S.T. N°5 Berazategui &copy; <?= date('Y') ?></footer>
<?php if ($rol === 'alumno'): ?>
<script src="/public/js/push.js"></script>
<script src="/public/js/alumno.js"></script>
<?php elseif ($rol === 'preceptora'): ?>
<script src="/public/js/preceptora.js"></script>
<?php endif; ?>
</body>
</html>
