<?php
session_start();


function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
      
        $_SESSION['user'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password']; 
        $_SESSION['cart'] = [];

        
        header("Location: ../view/shop.php");
        exit;
    }
}


login();
?>
<?php
session_start();

function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
        // En un caso real, aquí harías un INSERT en la base de datos
        $_SESSION['user'] = $_POST['username'];
        $_SESSION['cart'] = [];
        
        
        header("Location: ../view/shop.php");
        exit;
    }
}

register();
?>
<?php
session_start();

function logout() {
    
    $_SESSION = array();

    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

   
    session_destroy();

    
    header("Location: ../view/login.php");
    exit;
}

logout();
?>