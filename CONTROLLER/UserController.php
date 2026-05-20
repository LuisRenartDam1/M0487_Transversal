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
 
    // ── UPDATE ── Edit profile fields (Username Only) ────────────────────────
    public function updateProfile() {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }
 
        $oldUsername = $_SESSION['user'];
        $newUsername = trim($_POST['username'] ?? '');
 
        if (empty($newUsername)) {
            header("Location: ../VIEW/profile.php?updated=0");
            exit();
        }
 
        $db   = new Database();
        $conn = $db->getConnection();
        
        // Ejecuta el UPDATE en la base de datos usando el nuevo método adaptado
        $ok = Users::updateProfile($conn, $oldUsername, $newUsername);
 
        if ($ok) {
            // ¡Crucial! Actualizamos la sesión para que el sistema reconozca el nuevo nombre
            $_SESSION['user'] = $newUsername; 
        }
 
        header("Location: ../VIEW/profile.php?updated=" . ($ok ? '1' : '0'));
        exit();
    }
 
    // ── UPDATE ── Change Password ─────────────────────────────────────────────
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
 
    // ── DELETE ── Delete Account ──────────────────────────────────────────────
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
 
    // ── LOGOUT ── Destroy Session ─────────────────────────────────────────────
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p["path"], $p["domain"], $p["secure"], $p["httponly"]
            );
        }
        session_destroy();
        header("Location: ../VIEW/login.html");
        exit();
    }
}
?>