<?php
class Database {
    private $host = "localhost";
    private $db_name = "nombre_de_tu_base_de_datos"; 
    private $username = "root"; 
    private $password = ""; 
    public ?PDO $conn = null;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>