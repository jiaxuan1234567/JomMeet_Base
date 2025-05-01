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

    // Update a gathering’s status
    public function updateGatheringStatus($gatheringID, $status)
    {
        try {
            $stmt = $this->db->prepare("UPDATE gathering SET status = :status WHERE gatheringID = :id");
            return $stmt->execute([
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
    WHERE (
        theme LIKE :searchTerm 
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
    )
    AND status = 'NEW'
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

    // 
    public function verifyUserInGathering($userID, $gatheringID)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM profilegathering WHERE profileID = :pid AND gatheringID = :gid");
            $stmt->execute([':pid' => $userID, ':gid' => $gatheringID]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("userJoined error: " . $e->getMessage());
            return false;
        }
    }

    public function getProfileByUserId($userID)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT *
            FROM profile
            WHERE profileID = :profileID        
        ");
            $stmt->bindParam(':profileID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getProfileByUserId: " . $e->getMessage());
            return null;
        }
    }

    public function getJoinedGatheringByUserId($userID)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT g.*
            FROM gathering g
            JOIN profilegathering pg ON g.gatheringID = pg.gatheringID
            WHERE pg.profileID = :profileID        
        ");
            $stmt->execute([':profileID' => $userID]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getJoinedGatheringByUserId: " . $e->getMessage());
            return [];
        }
    }

    // vs ----
    public function getAvailableGatherings($profileId)
    {
        try {
            $sql = "
             SELECT g.*, l.* 
            FROM location l
            JOIN gathering g ON g.locationID = l.locationID
            LEFT JOIN profilegathering p 
              ON g.gatheringID = p.gatheringID AND p.profileID = :pid
            WHERE g.hostProfileID != :pid
              AND p.profileID IS NULL
              AND g.maxParticipant > g.currentParticipant
              AND g.status IN ('NEW')
              ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':pid' => $profileId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] getUserGatherings: " . $e->getMessage());
            return [];
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
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':pid' => $profileId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] getUserGatherings: " . $e->getMessage());
            return [];
        }
    }

    public function createGathering($d)
    {
        $sql = "INSERT INTO `gathering` (locationID, theme, maxParticipant, minParticipant, currentParticipant, date, startTime, endTime, status, preference, hostProfileID) 
        VALUES (:locationID, :theme, :max, :min, :current, :date, :start, :end, :status, :preference, :hostProfileID)";
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
            ':hostProfileID' => $d['hostProfileID']
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function addUserToGathering($userID, $gatheringID)
    {
        try {
            $this->db->beginTransaction();

            // First query: Add user to the profileGathering table
            $stmt1 = $this->db->prepare("INSERT INTO `profilegathering` (profileID, gatheringID) VALUES (:profileID, :gatheringID)");
            $stmt1->execute([
                ':profileID' => $userID,
                ':gatheringID' =>  $gatheringID
            ]);

            // Second query: Increment the currentParticipant in the gathering table
            $stmt2 = $this->db->prepare("UPDATE `gathering` SET currentParticipant = currentParticipant + 1 WHERE gatheringID = :gatheringID");
            $stmt2->execute([':gatheringID' => $gatheringID]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error in addUserToGathering: " . $e->getMessage());
            return false;
        }
    }

    public function cancelWithParticipant($gatheringID)
    {
        try {
            $this->db->beginTransaction();

            // 1. Get participants BEFORE deleting
            $selectStmt = $this->db->prepare("SELECT profileID FROM profilegathering WHERE gatheringID = :id");
            $selectStmt->execute([':id' => $gatheringID]);
            $participants = $selectStmt->fetchAll(PDO::FETCH_COLUMN);

            // 2. Update status
            $updateStmt = $this->db->prepare("UPDATE gathering SET status = 'CANCELLED' WHERE gatheringID = :id");
            $updateStmt->execute([':id' => $gatheringID]);

            // 3. Remove participants
            $deleteStmt = $this->db->prepare("DELETE FROM profilegathering WHERE gatheringID = :id");
            $deleteStmt->execute([':id' => $gatheringID]);

            $this->db->commit();
            return $participants;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("[GatheringDAO] cancelWithParticipants failed: " . $e->getMessage());
            return false;
        }
    }

    // Fetch all gatherings whose status may need updating
    public function fetchGatheringsToTransition()
    {
        $sql = "
            SELECT
                `gatheringID`,
                `date`,
                `startTime`,
                `endTime`,
                `status`
            FROM `gathering`
            WHERE
            ( `status` = 'new'
                AND CONCAT(`date`, ' ', `startTime`) <= NOW()
            )
        OR ( `status` = 'start'
                AND CONCAT(`date`, ' ', `endTime`)   <= NOW()
            )
        ";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("[GatheringDAO] fetchGatheringsToTransition: " . $e->getMessage());
            return [];
        }
    }
}
