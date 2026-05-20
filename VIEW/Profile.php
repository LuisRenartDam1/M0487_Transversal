<?php
session_start();
 
// Only logged-in users can access this page
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . '/../MODEL/db.php';
require_once __DIR__ . '/../MODEL/Users.php';

$conn    = Database::getConnection();
$profile = Users::getProfile($conn, $_SESSION['user']);

// ── Gestión de mensajes de feedback ──
$msg = '';
$msgClass = '';

if (isset($_GET['updated'])) {
    $msg = $_GET['updated'] === '1' ? '¡Perfil actualizado con éxito!' : 'Error al actualizar el perfil.';
    $msgClass = $_GET['updated'] === '1' ? 'success' : 'error';
} elseif (isset($_GET['pwd'])) {
    $msg = $_GET['pwd'] === 'ok' ? '¡Contraseña cambiada con éxito!' : 'La contraseña actual es incorrecta.';
    $msgClass = $_GET['pwd'] === 'ok' ? 'success' : 'error';
} elseif (isset($_GET['pwd_error'])) {
    $msg = 'Error en las contraseñas (no coinciden o son muy cortas).';
    $msgClass = 'error';
} elseif (isset($_GET['del_error'])) {
    $msg = 'Contraseña incorrecta. El usuario no fue eliminado.';
    $msgClass = 'error';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub – Mi Perfil</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4ff; color: #333; padding-top: 80px; }
        
        header { background: linear-gradient(135deg, #295be2, #0579ec); color: white; padding: 16px 0; position: fixed; top: 0; width: 100%; z-index: 100; box-shadow: 0 4px 15px rgba(0,0,0,.15); }
        .header-content { max-width: 600px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .header-nav a { color: rgba(255,255,255,.85); text-decoration: none; font-size: .9em; font-weight: 500; padding: 6px 12px; border-radius: 20px; }
        .header-nav a:hover { background: rgba(255,255,255,.2); color: white; }

        .container { max-width: 600px; margin: 20px auto; padding: 0 20px; display: flex; flex-direction: column; gap: 20px; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.07); padding: 25px; }
        
        h2 { font-size: 1.1em; color: #295be2; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e8edff; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 15px; }
        
        label { font-size: .85em; font-weight: 600; color: #555; }
        input[type="text"], input[type="password"] { padding: 11px 14px; border: 2px solid #e0e0e0; border-radius: 9px; font-size: .95em; background: #fafafa; width: 100%; }
        input:focus { outline: none; border-color: #295be2; background: #fff; box-shadow: 0 0 0 3px rgba(41,91,226,.1); }
        
        .btn { padding: 11px 20px; border: none; border-radius: 9px; font-size: .95em; font-weight: 600; cursor: pointer; transition: all .2s; width: 100%; }
        .btn-primary { background: linear-gradient(135deg, #295be2, #0579ec); color: white; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(41,91,226,.3); }
        .btn-danger { background: linear-gradient(135deg, #e53935, #c62828); color: white; }
        .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(229,57,53,.3); }
        
        .alert { padding: 12px 16px; border-radius: 9px; font-size: .9em; font-weight: 500; text-align: center; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #43a047; }
        .alert-error { background: #fce4ec; color: #c62828; border-left: 4px solid #e53935; }
        .danger-zone { border: 2px dashed #ffcdd2; background: #fff8f8; padding: 15px; border-radius: 10px; }
        .danger-zone p { font-size: 0.85em; color: #666; margin-bottom: 12px; }
    </style>
</head>
<body>

<header>
    <div class="header-content">
        <strong style="font-size: 1.4em;">GameHub</strong>
        <nav class="header-nav">
            <a href="shop.php">Tienda</a>
            <a href="../CONTROLLER/UserController.php?action=logout">Cerrar Sesión </a>
        </nav>
    </div>
</header>

<div class="container">
    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgClass ?>"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Datos de Cuenta</h2>
        <form action="../CONTROLLER/UserController.php" method="POST">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($profile['username'] ?? '') ?>" required>
            </div>
            <button type="submit" name="updateProfile" class="btn btn-primary">Actualizar Usuario</button>
        </form>
    </div>

    <div class="card">
        <h2>Cambiar Contraseña</h2>
        <form action="../CONTROLLER/UserController.php" method="POST">
            <div class="form-group">
                <label for="current_password">Contraseña Actual</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nueva Contraseña</label>
                <input type="password" id="new_password" name="new_password" placeholder="Mínimo 6 caracteres" required minlength="6">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Nueva Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            <button type="submit" name="changePassword" class="btn btn-primary">Modificar Contraseña</button>
        </form>
    </div>

    <div class="card">
        <h2 style="color: #c62828;"> Zona de Peligro</h2>
        <div class="danger-zone">
            <p>Esta acción eliminará de forma irreversible tu cuenta <strong><?= htmlspecialchars($profile['username'] ?? '') ?></strong> junto con todo tu historial.</p>
            <form action="../CONTROLLER/UserController.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar definitivamente tu cuenta?');">
                <div class="form-group">
                    <label for="delete_password">Ingresa tu contraseña para confirmar</label>
                    <input type="password" id="delete_password" name="delete_password" required>
                </div>
                <button type="submit" name="deleteAccount" class="btn btn-danger">Eliminar Mi Cuenta</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>