<?php
 
session_start();
 
require_once __DIR__ . '/../MODEL/Users.php';
require_once __DIR__ . '/../MODEL/db.php';
 
$userController = new UserController();
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register']))        $userController->register();
    if (isset($_POST['login']))           $userController->login();
    if (isset($_POST['updateProfile']))   $userController->updateProfile();
    if (isset($_POST['changePassword']))  $userController->changePassword();
    if (isset($_POST['deleteAccount']))   $userController->deleteAccount();
}
 
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userController->logout();
}
 
// ──────────────────────────────────────────────────────────────────────────────
class UserController {
 
    // ── CREATE ── Register ────────────────────────────────────────────────────
    public function register() {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $user = new Users($_POST['username'], $_POST['password']);
            $db   = new Database();
            $conn = $db->getConnection();
 
            if ($user->register($conn)) {
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['cart'] = [];
                header("Location: ../VIEW/shop.php");
                exit();
            } else {
                header("Location: ../VIEW/registerError.html");
                exit();
            }
        } else {
            header("Location: ../VIEW/register.html?error=empty");
            exit();
        }
    }
 
    // ── READ ── Login ─────────────────────────────────────────────────────────
    public function login() {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $user = new Users($_POST['username'], $_POST['password']);
            $db   = new Database();
            $conn = $db->getConnection();
 
            if ($user->login($conn)) {
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['cart'] = [];
                header('Location: ../VIEW/shop.php');
                exit();
            } else {
                header("Location: ../VIEW/registerError.html");
                exit();
            }
        } else {
            header("Location: ../VIEW/login.html?error=empty");
            exit();
        }
    }
 
    // ── READ ── View profile (redirect to profile.php) ───────────────────────
    public function viewProfile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }
        header("Location: ../VIEW/profile.php");
        exit();
    }
 
    // ── UPDATE ── Edit profile fields ─────────────────────────────────────────
    public function updateProfile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }
 
        $username  = $_SESSION['user'];
        $email     = trim($_POST['email']     ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $bio       = trim($_POST['bio']       ?? '');
 
        // Handle avatar upload
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo   = finfo_open(FILEINFO_MIME_TYPE);
            $mime    = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
            finfo_close($finfo);
 
            if (in_array($mime, $allowed) && $_FILES['avatar']['size'] <= 2 * 1024 * 1024) {
                $ext    = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $fname  = 'avatar_' . preg_replace('/[^a-z0-9]/i', '_', $username) . '.' . $ext;
                $dest   = __DIR__ . '/../IMAGENES/' . $fname;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                    $avatar = '../IMAGENES/' . $fname;
                }
            }
        }
 
        $db   = new Database();
        $conn = $db->getConnection();
        $ok   = Users::updateProfile($conn, $username, $email, $full_name, $bio, $avatar);
 
        header("Location: ../VIEW/profile.php?updated=" . ($ok ? '1' : '0'));
        exit();
    }
 
    
    public function changePassword() {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }
 
        $username   = $_SESSION['user'];
        $current    = $_POST['current_password'] ?? '';
        $new        = $_POST['new_password']     ?? '';
        $confirm    = $_POST['confirm_password'] ?? '';
 
        if (empty($current) || empty($new) || empty($confirm)) {
            header("Location: ../VIEW/profile.php?pwd_error=empty");
            exit();
        }
 
        if ($new !== $confirm) {
            header("Location: ../VIEW/profile.php?pwd_error=mismatch");
            exit();
        }
 
        if (strlen($new) < 6) {
            header("Location: ../VIEW/profile.php?pwd_error=short");
            exit();
        }
 
        $db   = new Database();
        $conn = $db->getConnection();
        $ok   = Users::changePassword($conn, $username, $current, $new);
 
        header("Location: ../VIEW/profile.php?pwd=" . ($ok ? 'ok' : 'wrong'));
        exit();
    }
 
   
    public function deleteAccount() {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }
 
        $username = $_SESSION['user'];
        $password = $_POST['delete_password'] ?? '';
 
        $db   = new Database();
        $conn = $db->getConnection();
        $ok   = Users::deleteAccount($conn, $username, $password);
 
        if ($ok) {
            
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $p = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $p["path"], $p["domain"], $p["secure"], $p["httponly"]
                );
            }
            session_destroy();
            header("Location: ../VIEW/index.html?deleted=1");
        } else {
            header("Location: ../VIEW/profile.php?del_error=1");
        }
        exit();
    }
 
   
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p["path"], $p["domain"], $p["secure"], $p["httponly"]
            );
        }
        session_destroy();
        session_unset();
        header("Location: ../VIEW/login.html");
        exit();
    }
}
?>