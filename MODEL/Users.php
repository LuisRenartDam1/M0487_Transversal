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
        $stmt->bind_param("ss",$this->username, $hashed_password);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function login($connection) {
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            
            if (password_verify($this->password, $row['password'])) {
                $stmt->close();
                return true;
            }
        }
        $stmt->close();
        return false;
    }
}
?>