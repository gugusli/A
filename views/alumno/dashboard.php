<?php
declare(strict_types=1);
require_once __DIR__ . '/../../src/Previa.php';
require_once __DIR__ . '/../../src/Confirmacion.php';
require_once __DIR__ . '/../../src/Notificacion.php';

$alumno_id   = (int)$_SESSION['id'];
$hoy         = Previa::getByAlumnoFechaHoy($alumno_id);
$futuras     = Previa::getByAlumno($alumno_id);
$confirmadas = Confirmacion::getConfirmadas($alumno_id);
$tieneSub    = (bool)Notificacion::getSubscriptionByAlumno($alumno_id);

ob_start();
?>
<?php if (!$tieneSub): ?>
<div class="banner-push" id="banner-push">
    ⚠️ Activá las notificaciones para recibir avisos automáticos
    <button onclick="subscribeToPush()">Activar</button>
    <button class="btn-cerrar" onclick="document.getElementById('banner-push').remove()">✕</button>
</div>
<?php endif; ?>

<h2 class="section-title">Previas de hoy</h2>

<?php if (empty($hoy)): ?>
<p class="empty-msg">No tenés previas programadas para hoy.</p>
<?php else: ?>
<div class="cards-hoy">
<?php foreach ($hoy as $p):
    $yaConfirmo = in_array($p['id'], $confirmadas, false);
    $fechaHora  = $p['fecha'] . 'T' . $p['horario'];
?>
<div class="card-previa">
    <div class="card-materia"><?= htmlspecialchars($p['materia_nombre'], ENT_QUOTES, 'UTF-8') ?></div>
    <div class="card-info">Aula <?= htmlspecialchars($p['aula'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars(substr($p['horario'],0,5), ENT_QUOTES, 'UTF-8') ?> hs</div>
    <div class="card-countdown" data-datetime="<?= htmlspecialchars($fechaHora, ENT_QUOTES, 'UTF-8') ?>">Calculando...</div>
    <span class="badge badge-<?= strtolower($p['estado']) ?>"><?= htmlspecialchars($p['estado'], ENT_QUOTES, 'UTF-8') ?></span>
    <button class="btn-confirmar"
            data-id="<?= (int)$p['id'] ?>"
            <?= $yaConfirmo ? 'disabled' : '' ?>>
        <?= $yaConfirmo ? '✓ Visto' : 'Vi el aviso' ?>
    </button>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<h2 class="section-title" style="margin-top:2rem">Próximas previas</h2>
<?php if (empty($futuras)): ?>
<p class="empty-msg">No tenés previas registradas.</p>
<?php else: ?>
<div class="table-wrap">
<table class="tabla">
    <thead><tr><th>Fecha</th><th>Materia</th><th>Aula</th><th>Horario</th><th>Estado</th></tr></thead>
    <tbody>
    <?php foreach ($futuras as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($p['materia_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($p['aula'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars(substr($p['horario'],0,5), ENT_QUOTES, 'UTF-8') ?></td>
        <td><span class="badge badge-<?= strtolower($p['estado']) ?>"><?= htmlspecialchars($p['estado'], ENT_QUOTES, 'UTF-8') ?></span></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
$pageTitle = 'Mi Dashboard — Previas';
require __DIR__ . '/../../views/layout.php';
