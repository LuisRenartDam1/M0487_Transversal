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