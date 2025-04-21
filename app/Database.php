use PDO;
use PDOException;

<?php

class Database
{
    private $host = '127.0.0.1'; // Changed from 'localhost' to '127.0.0.1'
    private $dbName = 'jommeet';
    private $username = 'root';
    private $password = '';
    private $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName}",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getGatheringDAO()
    {
        return new GatheringDAO($this->connection);
    }
}