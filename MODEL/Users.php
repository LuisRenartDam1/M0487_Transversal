<?php

class Users {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function register($connection) {
        
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $connection->prepare($sql);

      
        try {
            return $stmt->execute([$this->username, $hashed_password]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($connection) {
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$this->username]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (password_verify($this->password, $row['password'])) {
                return true;
            }
        }
        
        return false;
    }
}
?>