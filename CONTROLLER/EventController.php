<?php

require_once __DIR__ . '/../MODEL/db.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        // Put logic of guardarProducto.php
        break;
    case 'update':
        // Put logic of actualizarProducto.php
        break;
    case 'delete':
        // Put logic of eliminarProducto.php
        break;
    default:
        // Redirect to products view if no action is provided
        header("Location: ../VIEW/events.html");
        exit();
}
?>