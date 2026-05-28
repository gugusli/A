<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar — Sistema de Previas E.E.S.T. N°5</title>
    <link rel="stylesheet" href="/public/css/base.css">
    <style>
        body { background: #1F3864; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
        .login-box { background:#fff; border-radius:12px; padding:2.5rem 2rem; width:100%; max-width:380px; box-shadow:0 8px 32px rgba(0,0,0,.3); }
        .login-title { color:#1F3864; font-size:1.4rem; font-weight:700; text-align:center; margin-bottom:.25rem; }
        .login-subtitle { color:#95A5A6; font-size:.85rem; text-align:center; margin-bottom:1.5rem; }
        .toggle-btns { display:flex; gap:.5rem; margin-bottom:1.5rem; }
        .toggle-btns button { flex:1; padding:.6rem; border:2px solid #2E5FAD; border-radius:6px; background:transparent; color:#2E5FAD; font-weight:600; cursor:pointer; transition:.2s; }
        .toggle-btns button.active { background:#2E5FAD; color:#fff; }
        .form-group { margin-bottom:1rem; }
        .form-group label { display:block; font-size:.85rem; color:#1A1A2E; margin-bottom:.3rem; font-weight:600; }
        .form-group input { width:100%; padding:.65rem .75rem; border:1.5px solid #ddd; border-radius:6px; font-size:1rem; box-sizing:border-box; transition:.2s; }
        .form-group input:focus { border-color:#2E5FAD; outline:none; }
        .btn-submit { width:100%; padding:.75rem; background:#1F3864; color:#fff; border:none; border-radius:6px; font-size:1rem; font-weight:700; cursor:pointer; transition:.2s; }
        .btn-submit:hover { background:#2E5FAD; }
        .error-msg { background:#fdecea; color:#E74C3C; border-radius:6px; padding:.6rem .9rem; font-size:.88rem; margin-bottom:1rem; }
        .hidden { display:none; }
    </style>
</head>
<body>
<div class="login-box">
    <div class="login-title">Sistema de Previas</div>
    <div class="login-subtitle">E.E.S.T. N°5 — Berazategui</div>

    <?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="toggle-btns">
        <button id="btn-alumno" class="active" onclick="setModo('alumno')">Soy alumno</button>
        <button id="btn-preceptora" onclick="setModo('preceptora')">Soy preceptora</button>
    </div>

    <form method="POST" action="/login" id="form-login">
        <input type="hidden" name="modo" id="campo-modo" value="alumno">

        <div id="grupo-dni" class="form-group">
            <label for="dni">DNI</label>
            <input type="text" id="dni" name="dni" placeholder="Ingresá tu DNI" autocomplete="off">
        </div>

        <div id="grupo-usuario" class="form-group hidden">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Usuario" autocomplete="off">
        </div>

        <div id="grupo-password" class="form-group hidden">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña">
        </div>

        <button type="submit" class="btn-submit">Ingresar</button>
    </form>
</div>
<script>
function setModo(modo) {
    document.getElementById('campo-modo').value = modo;
    document.getElementById('btn-alumno').classList.toggle('active', modo === 'alumno');
    document.getElementById('btn-preceptora').classList.toggle('active', modo === 'preceptora');
    document.getElementById('grupo-dni').classList.toggle('hidden', modo !== 'alumno');
    document.getElementById('grupo-usuario').classList.toggle('hidden', modo !== 'preceptora');
    document.getElementById('grupo-password').classList.toggle('hidden', modo !== 'preceptora');
}
</script>
</body>
</html>
