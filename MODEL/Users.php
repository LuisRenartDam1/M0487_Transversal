<?php

class Users {

    private string $username;
    private string $password;

    public function __construct(string $username, string $password) {
        $this->username = $username;
        $this->password = $password;
    }
    public function register(PDO $connection): bool {
        $hashed = password_hash($this->password, PASSWORD_DEFAULT);
        $sql    = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt   = $connection->prepare($sql);
        try {
            return $stmt->execute([$this->username, $hashed]);
        } catch (PDOException $e) {
            error_log("Error en register(): " . $e->getMessage());
            return false;
        }
    }
    public function login(PDO $connection): bool {
        $sql  = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$this->username]);
        $row  = $stmt->fetch(); // FETCH_ASSOC ya configurado en db.php
        return $row && password_verify($this->password, $row['password']);
    }
    public static function getProfile(PDO $connection, string $username): array|false {
        $sql  = "SELECT username FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    public static function updateProfile(PDO $connection, string $oldUsername, string $newUsername): bool {
        $sql  = "UPDATE users SET username = ? WHERE username = ?";
        $stmt = $connection->prepare($sql);
        return $stmt->execute([$newUsername, $oldUsername]);
    }

    public static function changePassword(PDO $connection, string $username, string $currentPassword, string $newPassword): bool {
        // 1. Verificar que la contraseña actual sea correcta
        $sql  = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$username]);
        $row  = $stmt->fetch();

        if (!$row || !password_verify($currentPassword, $row['password'])) {
            return false;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql    = "UPDATE users SET password = ? WHERE username = ?";
        $stmt   = $connection->prepare($sql);
        return $stmt->execute([$hashed, $username]);
    }
    public static function deleteAccount(PDO $connection, string $username, string $password): bool {
        // 1. Validar contraseña de confirmación antes del borrado definitivo
        $sql  = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$username]);
        $row  = $stmt->fetch();

        if (!$row || !password_verify($password, $row['password'])) {
            return false;
        }

        $sql  = "DELETE FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        return $stmt->execute([$username]);
    }
}