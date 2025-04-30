<?php

namespace app\database\connection;

use PDO;
use PDOException;

class Connection {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $host = 'localhost';
            $db   = 'adpoint_php';
            $user = 'postgres';
            $pass = 'postgres';
            $port = '5432';

            try {
                self::$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}