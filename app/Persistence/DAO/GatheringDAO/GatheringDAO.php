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

    public function searchGatherings($searchTerm)
    {
        try {
            $searchTerm = "%{$searchTerm}%";

            // Handle date formats
            $dateFormats = [
                'Y-m-d',    // 2025-06-10
                'd-m-Y',    // 10-06-2025
                'm/d/Y',    // 06/10/2025
                'd/m/Y',    // 10/06/2025
                'Y/m/d',    // 2025/06/10
            ];

            // Handle time formats
            $timeFormats = [
                'H:i:s',    // 22:00:00
                'H:i',      // 22:00
                'g:i A',    // 10:00 PM
                'g:i a',    // 10:00 pm
                'g A',      // 10 PM
                'g a',      // 10 pm
            ];

            $stmt = $this->db->prepare("
                SELECT * FROM gathering 
                WHERE theme LIKE :searchTerm 
                OR preference LIKE :searchTerm
                OR DATE_FORMAT(date, '%Y-%m-%d') LIKE :searchTerm
                OR DATE_FORMAT(date, '%d-%m-%Y') LIKE :searchTerm
                OR DATE_FORMAT(date, '%m/%d/%Y') LIKE :searchTerm
                OR DATE_FORMAT(date, '%d/%m/%Y') LIKE :searchTerm
                OR DATE_FORMAT(date, '%Y/%m/%d') LIKE :searchTerm
                OR TIME_FORMAT(startTime, '%H:%i:%s') LIKE :searchTerm
                OR TIME_FORMAT(startTime, '%H:%i') LIKE :searchTerm
                OR TIME_FORMAT(startTime, '%h:%i %p') LIKE :searchTerm
                OR TIME_FORMAT(startTime, '%h %p') LIKE :searchTerm
                OR TIME_FORMAT(endTime, '%H:%i:%s') LIKE :searchTerm
                OR TIME_FORMAT(endTime, '%H:%i') LIKE :searchTerm
                OR TIME_FORMAT(endTime, '%h:%i %p') LIKE :searchTerm
                OR TIME_FORMAT(endTime, '%h %p') LIKE :searchTerm
            ");

            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] Database error in searchGatherings: " . $e->getMessage());
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

    // my-gathering
    public function getMyGatherings($profileId)
    {
        try {
            //$sql = "SELECT * FROM gathering WHERE hostProfileID = :pid";
            $sql = "SELECT 
                g.gatheringID, 
                g.theme, 
                g.currentParticipant, 
                g.maxParticipant, 
                g.date, 
                g.startTime, 
                g.endTime, 
                g.status, 
                l.locationName AS venue, 
                l.image, (g.hostProfileID = :pid) AS isHost, 
                (p.profileID IS NOT NULL) AS isJoined
                FROM `location` l
                JOIN gathering g ON l.locationID = g.locationID
                JOIN profilegathering p ON (g.gatheringID = p.gatheringID) AND (p.profileID = :pid)
                WHERE (g.hostProfileID = :pid) OR (p.profileID = :pid)";
            // $sql = "
            //   SELECT
            //     g.gatheringID,
            //     g.theme,
            //     g.currentParticipant,
            //     g.maxParticipant,
            //     g.date,
            //     g.startTime,
            //     g.endTime,
            //     g.status,
            //     l.name     AS venue,
            //     l.coverImg AS cover,
            //     (g.hostProfileID = :pid)        AS isHost,
            //     (p.profileID       IS NOT NULL) AS isJoined
            //   FROM gathering g
            //   JOIN location  l ON g.locationID = l.locationID
            //   LEFT JOIN participation p
            //     ON g.gatheringID = p.gatheringID
            //    AND p.profileID  = :pid
            //   WHERE g.hostProfileID = :pid
            //      OR p.profileID    = :pid
            //   ORDER BY g.date DESC, g.startTime
            // ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':pid' => $profileId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] getUserGatherings: " . $e->getMessage());
            return [];
        }
    }

    public function createGathering(array $d): int
    {
        $sql = "INSERT INTO `gathering` (locationID, theme, maxParticipant, minParticipant, currentParticipant, date, startTime, endTime, status, preference) 
        VALUES (:locationID, :theme, :max, :min, :current, :date, :start, :end, :status, :preference)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':locationID'   => $d['locationId'],
            ':theme'        => $d['theme'],
            ':max'          => $d['maxParticipant'],
            ':min'          => $d['minParticipant'],
            ':current'      => $d['currentParticipant'] ?? 0,
            ':date'         => $d['date'],
            ':start'        => $d['startTime'],
            ':end'          => $d['endTime'],
            ':status'       => $d['status'],
            ':preference'   => $d['preference'],
        ]);

        return (int)$this->db->lastInsertId();
    }
}
