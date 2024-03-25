<?php

namespace Services;

use Model\User;

class UserService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser(User $user)
    {
        try {
            $sql = "INSERT INTO users (username, email, password, full_name, phone, role) VALUES (:username, :email, :password, :full_name, :phone, :role)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'full_name' => $user->getFullName(),
                'phone' => $user->getPhone(),
                'role' => $user->getRole()
            ]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getUserByID($user_id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $user = new User($row['user_id'], $row['username'], $row['email'], '', $row['full_name'], $row['phone'], $row['role'], $row['created_at'], $row['updated_at']);
        return $user;
    }

    public function getUsers()
    {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $user = new User(
                $row['user_id'],
                $row['username'],
                $row['email'],
                '',
                $row['full_name'],
                $row['phone'],
                $row['role'],
                $row['created_at'],
                $row['updated_at']
            );
            $users[] = $user;
        }
        return $users;
    }

    public function updateUser(User $user)
    {
        try {
            $sql = "UPDATE users SET username = :username, email = :email, full_name = :full_name, phone = :phone WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                // 'password' => $user->getPassword(),
                'full_name' => $user->getFullName(),
                'phone' => $user->getPhone(),
                // 'role' => $user->getRole(),
                'user_id' => $user->getUserID()
            ]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function updatePassword($user_id, $prev, $current, $confirm)
    {
        try {
            if ($current !== $confirm) {
                return "Les nouveaux mots de passe ne correspondent pas.";
            }

            $sql = "SELECT * FROM users WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            $password = $row['password'];

            // var_dump($password);

            if (!password_verify($prev, $password)) {
                return "Le mot de passe actuel est incorrect.";
            }

            $newPasswordHash = password_hash($current, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'password' => $newPasswordHash,
                'user_id' => $user_id
            ]);
            return 'success';
        } catch (\PDOException $e) {
            return 'error';
        }
    }

    public function deleteUser($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->rowCount();
    }
}
