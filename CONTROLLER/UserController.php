<?php

session_start();

require_once __DIR__ . '/../model/Users.php';
require_once __DIR__ . '/../model/db.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si viene del formulario de registro
    if (isset($_POST['register'])) {
        $userController->register();
    }

    // Si viene del formulario de login
    if (isset($_POST['login'])) {
        $userController->login();

    }
}

// Si se recibe por GET la petición de logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userController->logout();
}

class UserController {
    
    public function register() {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = new Users($username, $password);
            
            $db = new Database();
            $connection = $db->getConnection();

            if ($user->register($connection)) {
                // Registro exitoso, iniciamos sesión automáticamente
                $_SESSION['user'] = $username;
                $_SESSION['cart'] = [];
                header("Location: ../VIEW/shop.php"); // Ajusta esta ruta según dónde esté tu shop.php
                exit();
            } else {
                echo "Error: El usuario ya existe o hubo un problema en la DB.";
            }
        } else {
            echo "Por favor, completa todos los campos.";
        }
    }

    public function login() {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = new Users($username, $password);
            
            $db = new Database();
            $connection = $db->getConnection();

            if ($user->login($connection)) {
                $_SESSION['user'] = $username;
                $_SESSION['cart'] = [];
                header('Location: ../VIEW/shop.php'); // Ajusta esta ruta según dónde esté tu shop.php
                exit();
            } else {
                header("Location: ../VIEW/registerError.html");
            }
        } else {
            echo "Por favor, completa todos los campos.";
        }
    }

    public function logout() {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        header("Location: ../VIEW/login.html"); // Ajusta esta ruta a tu login.html
        exit();
    }
}
?>