<?php

session_start();

require_once __DIR__ . '/../MODEL/Users.php';
require_once __DIR__ . '/../MODEL/db.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $userController->register();
    }

    if (isset($_POST['login'])) {
        $userController->login();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userController->logout();
}

class UserController
{
    public function register()
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $user = new Users($username, $password);

            $db = new Database();
            $connection = $db->getConnection();

            if ($user->register($connection)) {
                $_SESSION['user'] = $username;
                $_SESSION['cart'] = [];
                header('Location: ../VIEW/shop.php');
                exit();
            }

            echo 'Error: El usuario ya existe o hubo un problema en la base de datos.';
            return;
        }

        echo 'Por favor, completa todos los campos.';
    }

    public function login()
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $user = new Users($username, $password);

            $db = new Database();
            $connection = $db->getConnection();

            if ($user->login($connection)) {
                $_SESSION['user'] = $username;
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                header('Location: ../VIEW/shop.php');
                exit();
            }

            echo 'Usuario o contraseña incorrectos.';
            return;
        }

        echo 'Por favor, completa todos los campos.';
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header('Location: ../VIEW/login.html');
        exit();
    }
}
