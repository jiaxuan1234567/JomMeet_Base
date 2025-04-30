<?php

namespace Persistence\DAO\ProfileDAO;

use PDO;
use PDOException;
use Database;


class ProfileDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllProfiles()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `profile`");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllProfiles: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByPhoneNumber($phone)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `profile` WHERE phone = :p");
            $stmt->execute([':p' => $phone]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllProfiles: " . $e->getMessage());
            return false;
        }
    }
}
