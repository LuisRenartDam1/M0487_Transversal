<?php


class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "BBDDTransversal";
    private $connection;

    public function getConnection() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }

        return $this->connection;
    }
}
?>