<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}


if ($_SESSION['role'] !== 'admin') {
    
    header("Location: perfil_user.php");
    exit();
}
?>
<!DOCTYPE html>