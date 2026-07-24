<?php

require_once __DIR__ . '/Env.php';

class Database {

    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            
            
            Env::load(__DIR__ . '/../.env');

           
            $host     = Env::get('DB_HOST');
            $db_name  = Env::get('DB_NAME');
            $username = Env::get('DB_USER');
            $password = Env::get('DB_PASS');

            try {
                self::$conn = new PDO(
                    "mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4",
                    $username,
                    $password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch(PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
                exit();
            }
        }
        return self::$conn;
    }
}
