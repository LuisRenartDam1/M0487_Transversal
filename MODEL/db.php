<?php

class Database {

    private string $host     = "localhost";
    private string $db_name  = "BBDDTransversal";
    private string $username = "root";
    private string $password = "";
    private static ?PDO $instance = null;
    private function __construct() {}

    public static function getConnection(): PDO {

        if (self::$instance === null) {

            $dsn = "mysql:host=localhost;dbname=BBDDtransversal;charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, "root", "", $options);
            } catch (PDOException $e) {
                error_log("Error de conexión PDO: " . $e->getMessage());
                die("No se pudo conectar a la base de datos. Contacta con el administrador.");
            }
        }

        return self::$instance;
    }
}