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

    public function getProfileGatheringByUserId($userID)
    {
        try {
            $stmt = $this->db->getConnection()->prepare("
        SELECT *
        FROM profileGathering
        WHERE profileID = :profileID        
        ");
            $stmt->bindParam(':profileID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getProfileGatheringByUserId: " . $e->getMessage());
            return [];
        }
    }



    public function addUserToGathering($userID, $gatheringID)
    {
        try {
            // Begin a transaction to ensure both queries are executed together
            $this->db->getConnection()->beginTransaction();

            // First query: Add user to the profileGathering table
            $stmt1 = $this->db->getConnection()->prepare("INSERT INTO profileGathering (profileID, gatheringID) VALUES (:profileID, :gatheringID)");
            $stmt1->bindParam(':profileID', $userID, PDO::PARAM_INT);
            $stmt1->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt1->execute();

            // Second query: Increment the currentParticipant in the gathering table
            $stmt2 = $this->db->getConnection()->prepare("UPDATE gathering SET currentParticipant = currentParticipant + 1 WHERE gatheringID = :gatheringID");
            $stmt2->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt2->execute();

            // Commit the transaction if both queries were successful
            $this->db->getConnection()->commit();

            return true;
        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            $this->db->getConnection()->rollBack();

            error_log("Error in addUserToGathering: " . $e->getMessage());
            return false;
        }
    }
}
