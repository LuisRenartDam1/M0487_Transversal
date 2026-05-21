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

// ── Feedback message management ──
$msg = '';
$msgClass = '';

if (isset($_GET['updated'])) {
    $msg = $_GET['updated'] === '1' ? 'Profile updated successfully!' : 'Error updating profile.';
    $msgClass = $_GET['updated'] === '1' ? 'success' : 'error';
} elseif (isset($_GET['pwd'])) {
    $msg = $_GET['pwd'] === 'ok' ? 'Password changed successfully!' : 'The current password is incorrect.';
    $msgClass = $_GET['pwd'] === 'ok' ? 'success' : 'error';
} elseif (isset($_GET['pwd_error'])) {
    $msg = 'Password error (they do not match or are too short).';
    $msgClass = 'error';
} elseif (isset($_GET['del_error'])) {
    $msg = 'Incorrect password. The user was not deleted.';
    $msgClass = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub – My Profile</title>
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
            <a href="shop.php">Store</a>
            <a href="../CONTROLLER/UserController.php?action=logout">Log Out </a>
        </nav>
    </div>
</header>

<div class="container">
    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgClass ?>"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Account Data</h2>
        <form action="../CONTROLLER/UserController.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($profile['username'] ?? '') ?>" required>
            </div>
            <button type="submit" name="updateProfile" class="btn btn-primary">Update User</button>
        </form>
    </div>

    <div class="card">
        <h2>Change Password</h2>
        <form action="../CONTROLLER/UserController.php" method="POST">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="Minimum 6 characters" required minlength="6">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            <button type="submit" name="changePassword" class="btn btn-primary">Modify Password</button>
        </form>
    </div>

    <div class="card">
        <h2 style="color: #c62828;"> Danger Zone</h2>
        <div class="danger-zone">
            <p>This action will irreversibly delete your account <strong><?= htmlspecialchars($profile['username'] ?? '') ?></strong> along with all your history.</p>
            <form action="../CONTROLLER/UserController.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete your account?');">
                <div class="form-group">
                    <label for="delete_password">Enter your password to confirm</label>
                    <input type="password" id="delete_password" name="delete_password" required>
                </div>
                <button type="submit" name="deleteAccount" class="btn btn-danger">Delete My Account</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>