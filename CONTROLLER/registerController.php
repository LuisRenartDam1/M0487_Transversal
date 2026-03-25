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