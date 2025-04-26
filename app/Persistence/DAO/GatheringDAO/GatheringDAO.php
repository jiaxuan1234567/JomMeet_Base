<?php

namespace Persistence\DAO\GatheringDAO;

use PDO;
use PDOException;
use Database;


class GatheringDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function updateGatheringStatus($gatheringID, $status)
    {
        try {
            $stmt = $this->db->prepare("UPDATE gathering SET status = :status WHERE gatheringID = :id");
            $stmt->execute([
                ':status' => $status,
                ':id' => $gatheringID
            ]);
        } catch (PDOException $e) {
            error_log("Error in updateGatheringStatus: " . $e->getMessage());
            return false;
        }
    }


    public function getAllGatherings()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM gathering");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllGatherings: " . $e->getMessage());
            return false;
        }
    }


    public function getGatheringById($id)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT g.*, l.*
            FROM gathering g
            JOIN location l ON g.locationID = l.locationID
            WHERE g.gatheringID = :id
        ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getGatheringById: " . $e->getMessage());
            return null;
        }
    }

    public function getProfileGatheringByUserId($userID)
    {
        try {
            $stmt = $this->db->prepare("
        SELECT *
        FROM profileGathering
        WHERE profileID = :profileID        
        ");
            $stmt->bindParam(':profileID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getProfileGatheringByUserId: " . $e->getMessage());
            return [];
        }
    }

    public function getJoinedGatheringByUserId($userID)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT g.*
            FROM gathering g
            JOIN profileGathering pg ON g.gatheringID = pg.gatheringID
            WHERE pg.profileID = :profileID        
        ");
            $stmt->bindParam(':profileID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getJoinedGatheringByUserId: " . $e->getMessage());
            return [];
        }
    }



    public function addUserToGathering($userID, $gatheringID)
    {
        try {
            // Begin a transaction to ensure both queries are executed together
            $this->db->beginTransaction();

            // First query: Add user to the profileGathering table
            $stmt1 = $this->db->prepare("INSERT INTO profileGathering (profileID, gatheringID) VALUES (:profileID, :gatheringID)");
            $stmt1->bindParam(':profileID', $userID, PDO::PARAM_INT);
            $stmt1->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt1->execute();

            // Second query: Increment the currentParticipant in the gathering table
            $stmt2 = $this->db->prepare("UPDATE gathering SET currentParticipant = currentParticipant + 1 WHERE gatheringID = :gatheringID");
            $stmt2->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt2->execute();

            // Commit the transaction if both queries were successful
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            // Rollback the transaction in case of any error
            $this->db->rollBack();

            error_log("Error in addUserToGathering: " . $e->getMessage());
            return false;
        }
    }
}
