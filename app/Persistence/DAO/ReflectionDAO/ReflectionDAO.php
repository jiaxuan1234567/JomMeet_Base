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

    public function getReflectionID()
    {
        try {
            $stmt = $this->db->prepare("SELECT MAX(selfreflectID) FROM self_reflect");
            $stmt->execute();
            $lastSelfReflectID = $stmt->fetchColumn();

            // Generate the new orderitem_id
            if ($lastSelfReflectID) {
                // Increment the Self Reflect ID
                $newSelfReflectID = $lastSelfReflectID +1;
            } else {
                // If there are no existing rows, start from 1
                $newSelfReflectID = 1;
            }
            return $newSelfReflectID;
        } catch (PDOException $e) {
            error_log("Error in getReflectionID: " . $e->getMessage());
            return false;
        }
    }

    public function saveReflections($profileId) 
    {
        try {
            $stm = $this->db->prepare('INSERT INTO self_reflect
            (selfreflectID, profileID, title, content, date)
            VALUES(?, ?, ?, ?, ?)');
            $stm->execute([getSelfReflectID(),$profileId,$title,$content,$date]);
        } catch (PDOException $e) {
            error_log("Error in saveReflection: " . $e->getMessage());
            return false;
        }
    }
}
