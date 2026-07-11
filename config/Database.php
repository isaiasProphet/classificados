<?php

class Database {
    private static $host = "localhost";
    private static $db_name = "classificados";
    private static $username = "isaias"; // Using default root for local, the user can change it
    private static $password = "341200@Ibn"; 
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4",
                    self::$username,
                    self::$password
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
