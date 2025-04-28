<?php

namespace Persistence\DAO\ReflectionDAO;

use PDO;
use PDOException;
use Database;


class ReflectionDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllReflections()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM self_reflect");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllReflections: " . $e->getMessage());
            return false;
        }
    }
}
