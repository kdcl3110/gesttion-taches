<?php

namespace Utils;

use \PDO;
use \PDOException;

function connexionToBD()
{
    $host = 'localhost';
    $dbname = 'matis';
    $username = 'root';
    $password = 'Kdcl3110@';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Gérer les erreurs de connexion ici
        throw new PDOException($e->getMessage());
    }
}
