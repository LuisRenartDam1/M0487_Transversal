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
    private function conn(): PDO {
        return Database::getConnection();
    }

    public function register(): void {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $user = new Users($_POST['username'], $_POST['password']);

            if ($user->register($this->conn())) {
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['cart'] = [];
                header("Location: ../VIEW/shop.php");
            } else {
                header("Location: ../VIEW/registerError.html");
            }
        } else {
            header("Location: ../VIEW/register.html?error=empty");
        }
        exit();
    }

    public function login(): void {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $user = new Users($_POST['username'], $_POST['password']);

            if ($user->login($this->conn())) {
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['cart'] = [];
                header("Location: ../VIEW/shop.php");
            } else {
                header("Location: ../VIEW/registerError.html");
            }
        } else {
            header("Location: ../VIEW/login.html?error=empty");
        }
        exit();
    }

    public function viewProfile(): void {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }
        header("Location: ../VIEW/profile.php");
        exit();
    }

    public function updateProfile(): void {
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

        $ok = Users::updateProfile($this->conn(), $oldUsername, $newUsername);

        if ($ok) {
            // Actualizar la sesión con el nuevo nombre
            $_SESSION['user'] = $newUsername;
        }

        header("Location: ../VIEW/profile.php?updated=" . ($ok ? '1' : '0'));
        exit();
    }

    public function changePassword(): void {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }

        $username = $_SESSION['user'];
        $current  = $_POST['current_password'] ?? '';
        $new      = $_POST['new_password']     ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

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

        $ok = Users::changePassword($this->conn(), $username, $current, $new);
        header("Location: ../VIEW/profile.php?pwd=" . ($ok ? 'ok' : 'wrong'));
        exit();
    }

    public function deleteAccount(): void {
        if (!isset($_SESSION['user'])) {
            header("Location: ../VIEW/login.html");
            exit();
        }

        $username = $_SESSION['user'];
        $password = $_POST['delete_password'] ?? '';
        $ok       = Users::deleteAccount($this->conn(), $username, $password);

        if ($ok) {
            $this->destroySession();
            header("Location: ../VIEW/index.html?deleted=1");
        } else {
            header("Location: ../VIEW/profile.php?del_error=1");
        }
        exit();
    }

    public function logout(): void {
        $this->destroySession();
        header("Location: ../VIEW/login.html");
        exit();
    }

    private function destroySession(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p["path"], $p["domain"], $p["secure"], $p["httponly"]
            );
        }
        session_destroy();
    }
}