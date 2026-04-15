<?php

class Users
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function register($connection)
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (username, password) VALUES (?, ?)';
        $stmt = $connection->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param('ss', $this->username, $hashedPassword);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function login($connection)
    {
        $sql = 'SELECT password FROM users WHERE username = ?';
        $stmt = $connection->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param('s', $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return password_verify($this->password, $row['password']);
        }

        $stmt->close();
        return false;
    }
}
?>
