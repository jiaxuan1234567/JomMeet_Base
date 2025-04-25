<?php

require_once __DIR__ . '/Database.php';
$db = new Database();

class GatheringDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllGatherings()
    {
        try {
            $stmt = $this->db->getConnection()->prepare("SELECT * FROM gathering");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllGatherings: " . $e->getMessage());
            return false;
        }
    }


    public function getGatheringById($id)
    {
        try {
            $stmt = $this->db->getConnection()->prepare("
            SELECT g.*, l.*
            FROM gathering g
            JOIN location l ON g.locationID = l.locationID
            WHERE g.gatheringID = :id
        ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getGatheringById: " . $e->getMessage());
            return null;
        }
    }

    public function addUserToGathering($userID, $gatheringID)
    {
        try {
            $stmt = $this->db->getConnection()->prepare("INSERT INTO profileGathering (userID, gatheringID) VALUES (:userID, :gatheringID)");
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Error in addUserToGathering: " . $e->getMessage());
            return false;
        }
    }
}
