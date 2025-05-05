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

    // ------------------------------------------ View List Part ------------------------------------------//
    public function getAllReflections($profileId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM self_reflect WHERE profileID = $profileId");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllReflections: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------ Create Part ------------------------------------------//
    public function saveReflection($profileId,$reflectionDate,$reflectionTitle,$reflectionContent) 
    {
        try {
            $stm = $this->db->prepare('INSERT INTO self_reflect
            (profileID, title, content, date)
            VALUES(?, ?, ?, ?)');
            $stm->execute([$profileId,$reflectionTitle,$reflectionContent, $reflectionDate]);
        } catch (PDOException $e) {
            error_log("Error in saveReflection: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------ View Detail Part ------------------------------------------//
    public function getReflectionById($profileId,$reflectionId) 
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM self_reflect WHERE selfreflectID = $reflectionId AND profileID = $profileId");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getReflectionById: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------ Edit Part ------------------------------------------//
    public function editSaveReflection($reflectionId,$reflectionTitle,$reflectionContent) 
    {
        try {
            $stm = $this->db->prepare('UPDATE self_reflect
            SET title=?, content=?
            WHERE selfreflectID = ?');
            $stm->execute([$reflectionTitle,$reflectionContent,$reflectionId]);
        } catch (PDOException $e) {
            error_log("Error in saveReflection: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------ Delete Part ------------------------------------------//
    public function deleteReflectionById($reflectionId) 
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM self_reflect WHERE selfreflectID = :id");
            $stmt->bindParam(':id', $reflectionId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in deleteReflectionById: " . $e->getMessage());
            return false;
        }
    }

}
