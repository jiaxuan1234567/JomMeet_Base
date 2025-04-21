<?php
class DatabaseTest
{
    private static $host = "localhost"; // Change if using a different host
    private static $dbName = "jommeet"; // Replace with your actual database name
    private static $username = "root"; // Replace with your DB username
    private static $password = ""; // Replace with your DB password
    private static $connection = null;

    private function __construct()
    {
        // Private constructor to prevent direct instantiation
    }

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=utf8",
                    self::$username,
                    self::$password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
