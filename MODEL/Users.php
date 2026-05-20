<?php

class Users {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    // ──────────────────────────────────────────
    // CREATE — Registrar nuevo usuario
    // ──────────────────────────────────────────
    public function register($connection) {
        $hashed = password_hash($this->password, PASSWORD_DEFAULT);
        $sql    = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt   = $connection->prepare($sql);
        try {
            return $stmt->execute([$this->username, $hashed]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ──────────────────────────────────────────
    // READ — Login / Autenticación
    // ──────────────────────────────────────────
    public function login($connection) {
        $sql  = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$this->username]);
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($this->password, $row['password'])) {
            return true;
        }
        return false;
    }

    // ──────────────────────────────────────────
    // READ — Obtener datos básicos del perfil
    // ──────────────────────────────────────────
    public static function getProfile($connection, $username) {
        // Eliminados 'email', 'bio', etc., para evitar el error de columna inexistente
        $sql  = "SELECT username FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────
    // UPDATE — Modificar el nombre de usuario
    // ──────────────────────────────────────────
    public static function updateProfile($connection, $oldUsername, $newUsername) {
        $sql  = "UPDATE users SET username = ? WHERE username = ?";
        $stmt = $connection->prepare($sql);
        return $stmt->execute([$newUsername, $oldUsername]);
    }

    // ──────────────────────────────────────────
    // UPDATE — Cambiar la contraseña
    // ──────────────────────────────────────────
    public static function changePassword($connection, $username, $currentPassword, $newPassword) {
        // Verificar que la contraseña actual sea correcta antes de cambiarla
        $sql  = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$username]);
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($currentPassword, $row['password'])) {
            return false; 
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql    = "UPDATE users SET password = ? WHERE username = ?";
        $stmt   = $connection->prepare($sql);
        return $stmt->execute([$hashed, $username]);
    }

    // ──────────────────────────────────────────
    // DELETE — Eliminar cuenta por completo
    // ──────────────────────────────────────────
    public static function deleteAccount($connection, $username, $password) {
        // Validar contraseña de confirmación antes del borrado definitivo
        $sql  = "SELECT password FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$username]);
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($password, $row['password'])) {
            return false;
        }

        $sql  = "DELETE FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        return $stmt->execute([$username]);
    }
}
?>