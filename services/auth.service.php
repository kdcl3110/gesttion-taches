<?php

namespace Services;

use Model\User;
use \PDOException;

function register(User $user)
{
    try {
        $conn = \Utils\connexionToBD();

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user->getUsername(), $user->getEmail(), $hashedPassword, $user->getFullName(), $user->getPassword()]);

        $userId = $conn->lastInsertId();

        $conn = null;

        $user->setUserId($userId);

        return $user;
    } catch (PDOException $e) {
        die("Erreur: " . $e->getMessage());
    }
}


function login($conn, $username,  $password)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Vérifier si l'utilisateur existe
        if ($stmt->rowCount() > 0) {
            // Utilisateur trouvé, vérifier le mot de passe
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
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
                return $user;
            } else {
                return null;
            }
        } else {
            return null;
        }
    } catch (PDOException $e) {
        return null;
    }
}
