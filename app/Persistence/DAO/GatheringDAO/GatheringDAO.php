<?php

namespace Persistence\DAO\GatheringDAO;

use PDO;
use PDOException;
use Database;
use \DateTime;


class GatheringDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollback();
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

    public function searchGatherings($searchTerm, $profileId)
    {
        try {
            $searchTerm = "%{$searchTerm}%";

            $stmt = $this->db->prepare("
            SELECT g.*, l.*
            FROM gathering g
            JOIN location l ON g.locationID = l.locationID
            LEFT JOIN profilegathering p 
              ON g.gatheringID = p.gatheringID AND p.profileID = :pid
            WHERE (
                g.theme LIKE :searchTerm 
                OR g.preference LIKE :searchTerm
                OR DATE_FORMAT(g.date, '%Y-%m-%d') LIKE :searchTerm
                OR DATE_FORMAT(g.date, '%d-%m-%Y') LIKE :searchTerm
                OR DATE_FORMAT(g.date, '%m/%d/%Y') LIKE :searchTerm
                OR DATE_FORMAT(g.date, '%d/%m/%Y') LIKE :searchTerm
                OR DATE_FORMAT(g.date, '%Y/%m/%d') LIKE :searchTerm
                OR TIME_FORMAT(g.startTime, '%H:%i:%s') LIKE :searchTerm
                OR TIME_FORMAT(g.startTime, '%H:%i') LIKE :searchTerm
                OR TIME_FORMAT(g.startTime, '%h:%i %p') LIKE :searchTerm
                OR TIME_FORMAT(g.startTime, '%h %p') LIKE :searchTerm
                OR TIME_FORMAT(g.endTime, '%H:%i:%s') LIKE :searchTerm
                OR TIME_FORMAT(g.endTime, '%H:%i') LIKE :searchTerm
                OR TIME_FORMAT(g.endTime, '%h:%i %p') LIKE :searchTerm
                OR TIME_FORMAT(g.endTime, '%h %p') LIKE :searchTerm
            )
            AND g.hostProfileID != :pid
            AND p.profileID IS NULL
            AND g.maxParticipant > g.currentParticipant
            AND g.status = 'NEW'
        ");

            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':pid', $profileId, PDO::PARAM_INT);
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

    public function isUserJoined($gatheringId, $profileId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM profilegathering 
            WHERE gatheringID = :gid AND profileID = :pid
        ");
        $stmt->execute([':gid' => $gatheringId, ':pid' => $profileId]);
        return $stmt->fetchColumn() > 0;
    }

    // public function hasTimeConflict($profileId, $startTime)
    // {
    //     $stmt = $this->db->prepare("
    //         SELECT g.* FROM gathering g
    //         JOIN profilegathering pg ON pg.gatheringID = g.gatheringID
    //         WHERE pg.profileID = :pid
    //         AND g.status = 'ACTIVE'
    //         AND CONCAT(g.date, ' ', g.startTime) = :start
    //     ");
    //     $stmt->execute([
    //         ':pid' => $profileId,
    //         ':start' => $startTime->format('Y-m-d H:i:s')
    //     ]);
    //     return $stmt->rowCount() > 0;
    // }

    public function hasTimeConflict($profileId, $startTime, $endTime)
    {
        $formattedStart = $startTime->format('Y-m-d H:i:s');
        $formattedEnd = $endTime->format('Y-m-d H:i:s');
        error_log("[hasTimeConflict] Checking time conflict for profile $profileId between $formattedStart and $formattedEnd");

        $stmt = $this->db->prepare("
        SELECT g.* 
        FROM gathering g
        JOIN profilegathering pg ON pg.gatheringID = g.gatheringID
        WHERE pg.profileID = :pid
        AND g.status = 'NEW'
        AND (
            (:start BETWEEN CONCAT(g.date, ' ', g.startTime) AND 
                CASE 
                    WHEN g.endTime < g.startTime THEN DATE_ADD(CONCAT(g.date, ' ', g.endTime), INTERVAL 1 DAY)
                    ELSE CONCAT(g.date, ' ', g.endTime)
                END
            )
            OR (:end BETWEEN CONCAT(g.date, ' ', g.startTime) AND 
                CASE 
                    WHEN g.endTime < g.startTime THEN DATE_ADD(CONCAT(g.date, ' ', g.endTime), INTERVAL 1 DAY)
                    ELSE CONCAT(g.date, ' ', g.endTime)
                END
            )
            OR (CONCAT(g.date, ' ', g.startTime) BETWEEN :start AND :end)
            OR (
                CASE 
                    WHEN g.endTime < g.startTime THEN DATE_ADD(CONCAT(g.date, ' ', g.endTime), INTERVAL 1 DAY)
                    ELSE CONCAT(g.date, ' ', g.endTime)
                END
                BETWEEN :start AND :end
            )
        )
    ");
        $stmt->execute([
            ':pid' => $profileId,
            ':start' => $formattedStart,
            ':end' => $formattedEnd
        ]);
        $conflict = $stmt->rowCount() > 0;

        error_log("[hasTimeConflict] Conflict found: " . ($conflict ? "YES" : "NO"));

        return $conflict;
    }


    // my-gathering
    public function getUserAllGatherings($profileId)
    {
        try {
            $sql = "SELECT 
                g.*,
                l.locationName AS venue, 
                l.image, (g.hostProfileID = :pid) AS isHost, 
                (p.profileID IS NOT NULL) AS isJoined
                FROM `location` l
                JOIN gathering g ON l.locationID = g.locationID
                LEFT JOIN profilegathering p ON (g.gatheringID = p.gatheringID) AND (p.profileID = :pid)
                WHERE (g.hostProfileID = :pid) OR (p.profileID = :pid)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':pid' => $profileId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] getUserGatherings: " . $e->getMessage());
            return [];
        }
    }

    public function getUserHostedGatherings($profileId)
    {
        $stmt = $this->db->prepare("SELECT g.*, l.*, 1 as isHost, EXISTS (
            SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID
        ) as isJoined
        FROM gathering g
        JOIN `location` l ON g.locationID = l.locationID
        WHERE g.hostProfileID = :pid");
        $stmt->execute([':pid' => $profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserUpcomingGatherings($profileId)
    {
        $stmt = $this->db->prepare("SELECT g.*, l.*, 
            g.hostProfileID = :pid as isHost,
            EXISTS (SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID) as isJoined
            FROM gathering g
            JOIN `location` l ON g.locationID = l.locationID
            WHERE g.status = 'NEW' AND CONCAT(g.date, ' ', g.startTime) > NOW()
            AND EXISTS (
                SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID
            )");
        $stmt->execute([':pid' => $profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOngoingGatherings($profileId)
    {
        $stmt = $this->db->prepare("SELECT g.*, l.*, 
            g.hostProfileID = :pid as isHost,
            EXISTS (SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID) as isJoined
            FROM gathering g
            JOIN `location` l ON g.locationID = l.locationID
            WHERE g.status = 'NEW'
            AND CONCAT(g.date, ' ', g.startTime) <= NOW() 
            AND CONCAT(g.date, ' ', g.endTime) > NOW()
            AND EXISTS (
                SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID
            )");
        $stmt->execute([':pid' => $profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCompletedGatherings($profileId)
    {
        $stmt = $this->db->prepare("SELECT g.*, l.*, 
            g.hostProfileID = :pid as isHost,
            EXISTS (SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID) as isJoined
            FROM gathering g
            JOIN `location` l ON g.locationID = l.locationID
            WHERE g.status = 'END'
            AND EXISTS (
                SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID
            )");
        $stmt->execute([':pid' => $profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCancelledGatherings($profileId)
    {
        $stmt = $this->db->prepare("SELECT g.*, l.*, 
            g.hostProfileID = :pid as isHost,
            EXISTS (SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID) as isJoined
            FROM gathering g
            JOIN `location` l ON g.locationID = l.locationID
            WHERE g.status = 'CANCELLED'
            AND EXISTS (
                SELECT 1 FROM profilegathering pg WHERE pg.profileID = :pid AND pg.gatheringID = g.gatheringID
            )");
        $stmt->execute([':pid' => $profileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if user-related gathering only
    // -----------------------------------------------------------------------------------------------
    // Auto update gathering status
    public function updateGatheringStatuses()
    {
        try {
            $now = new \DateTime();
            $nowFormatted = $now->format('Y-m-d H:i:s');

            $sql = "UPDATE gathering
                SET status = CASE
                    WHEN CONCAT(date, ' ', startTime) > :now THEN 'NEW'
                    WHEN CONCAT(date, ' ', startTime) <= :now AND CONCAT(date, ' ', endTime) >= :now THEN 'START'
                    WHEN CONCAT(date, ' ', endTime) < :now THEN 'END'
                    ELSE status
                END
                WHERE status != 'CANCELLED'";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':now' => $nowFormatted]);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] updateGatheringStatuses: " . $e->getMessage());
        }
    }

    // ---------------------------------------------------------------------------------------------

    // Get User-related gathering only
    public function isProfileInvolved($gatheringId, $profileId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM profilegathering WHERE gatheringID = :gid AND profileID = :pid");
        $stmt->execute([':gid' => $gatheringId, ':pid' => $profileId]);
        return $stmt->fetchColumn() > 0;
    }

    public function isHostInvolved($gatheringId, $profileId)
    {
        $stmt = $this->db->prepare("SELECT * FROM `gathering` WHERE gatheringID = :gid AND hostProfileID = :pid");
        $stmt->execute([':gid' => $gatheringId, ':pid' => $profileId]);
        return $stmt->fetchColumn() > 0;
    }

    public function createGathering($d, $hostProfileId)
    {
        $sql = "INSERT INTO `gathering` (locationID, theme, maxParticipant, minParticipant, currentParticipant, date, startTime, endTime, status, preference, hostProfileID) 
        VALUES (:locationID, :theme, :max, :min, :current, :date, :start, :end, :status, :preference, :hostProfileID)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':locationID'   => $d['locationId'],
            ':theme'        => $d['inputTheme'],
            ':max'          => $d['inputPax'],
            ':date'         => $d['inputDate'],
            ':start'        => $d['startTime'],
            ':end'          => $d['endTime'],
            ':preference'   => $d['gatheringTag'],
            ':hostProfileID' => $hostProfileId,
            ':min'          => $d['minPax'],
            ':current'      => $d['currentPax'],
            ':status'       => $d['status']
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function updateGathering($d, $hostProfileId, $gatheringId)
    {
        $stmt = $this->db->prepare("
            UPDATE `gathering`
            SET theme = :theme, maxParticipant = :maxPax, date = :date,
                startTime = :start, endTime = :end, preference = :preference,
                locationID = :locationID
            WHERE gatheringID = :id AND hostProfileID = :hostId
        ");

        return $stmt->execute([
            ':theme' => $d['inputTheme'],
            ':maxPax' => $d['inputPax'],
            ':date' => $d['inputDate'],
            ':start' => $d['startTime'],
            ':end' => $d['endTime'],
            ':preference' => $d['gatheringTag'],
            ':locationID' => $d['locationId'],
            ':id' => $gatheringId,
            ':hostId' => $hostProfileId
        ]);
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

    public function leaveGathering($profileId, $gatheringId)
    {
        try {
            $this->db->beginTransaction();

            // 1. Remove user
            $deleteStmt = $this->db->prepare("DELETE FROM profilegathering WHERE profileID = :pid AND gatheringID = :gid");
            $deleteStmt->execute([
                ':pid' => $profileId,
                ':gid' => $gatheringId
            ]);

            // 2. Decrease participant count
            $updateStmt = $this->db->prepare("UPDATE gathering SET currentParticipant = currentParticipant - 1 WHERE gatheringID = :gid");
            $updateStmt->execute([':gid' => $gatheringId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("[GatheringDAO] participantLeaveGathering failed: " . $e->getMessage());
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
    //     public function insertLocationFeedback(array $data)
    // {
    //     try {
    //         $sql = "INSERT INTO feedback
    //           (profileID, gatheringID, locationID, feedbackDesc, feedbackType, date)
    //          VALUES
    //           (:pid, :gid, :lid, :desc, :type, :date)";
    //         $stmt = $this->db->prepare($sql);
    //         return $stmt->execute([
    //             ':pid'  => $data['profileID'],
    //             ':gid'  => $data['gatheringID'],
    //             ':lid'  => $data['locationID'],
    //             ':desc' => $data['feedbackDesc'],
    //             ':type' => $data['feedbackType'],
    //             ':date' => $data['date'],
    //         ]);
    //     } catch (PDOException $e) {
    //         error_log("Error inserting location feedback: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // // Location Feedback
    // public function fetchLocationFeedbacks($gatheringId, $locationId)
    // {
    //     try {
    //         $stmt = $this->db->prepare("
    //           SELECT f.*, p.name, p.avatar
    //             FROM feedback f
    //             JOIN profile p ON f.profileID = p.profileID
    //            WHERE f.gatheringID = :gid
    //              AND f.locationID  = :lid
    //            ORDER BY f.date DESC
    //         ");
    //         $stmt->execute([
    //           ':gid' => $gatheringId,
    //           ':lid' => $locationId
    //         ]);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         error_log("Error fetching location feedbacks: " . $e->getMessage());
    //         return [];
    //     }
    // }

    /**
     * Fetch all location feedback for a gathering’s location,
     * including the poster’s name and avatar.
     */
    public function getLocationFeedbackByLocation($locationId)
    {
        $sql = "
      SELECT 
        f.feedbackDesc,
        f.date,
        p.profileID,
        p.nickname    AS name,
        NULL          AS avatar
      FROM feedback f
      JOIN profile p 
        ON p.profileID = f.profileID
      WHERE f.locationID = :loc
        AND f.feedbackType = 'LOCATION'
      ORDER BY f.date ASC
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':loc' => $locationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Insert a new location feedback, but only if the user
     * hasn’t already left feedback for that gathering.
     */
    public function insertLocationFeedback($profileId, $gatheringId, $locationId, $desc)
    {
        // Only one LOCATION per user-gathering
        $check = $this->db->prepare("
      SELECT COUNT(*) FROM feedback
       WHERE profileID   = :pid
         AND gatheringID = :gid
         AND feedbackType= 'LOCATION'
    ");
        $check->execute([':pid' => $profileId, ':gid' => $gatheringId]);
        if ($check->fetchColumn() > 0) return false;

        // insert
        $ins = $this->db->prepare("
      INSERT INTO feedback
        (profileID, gatheringID, locationID, feedbackDesc, feedbackType, date)
      VALUES
        (:pid, :gid, :lid, :desc, 'LOCATION', NOW())
    ");
        return $ins->execute([
            ':pid'  => $profileId,
            ':gid'  => $gatheringId,
            ':lid'  => $locationId,
            ':desc' => $desc
        ]);
    }



    // 1) retrieve all gathering feedback entries
    public function getGatheringFeedbackByGathering(int $gatheringId): array
    {
        $sql = "
      SELECT 
        f.feedbackDesc,
        f.date,
        f.profileID          
      FROM feedback f
      WHERE f.gatheringID = :gid
        AND f.feedbackType = 'gathering'
      ORDER BY f.date ASC
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':gid' => $gatheringId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2) insert a new gathering feedback
    public function insertGatheringFeedback($profileId, $gatheringId, $locationId, $desc)
    {
        // Only one GATHERING per user-gathering
        $check = $this->db->prepare("
      SELECT COUNT(*) FROM feedback
       WHERE profileID   = :pid
         AND gatheringID = :gid
         AND feedbackType= 'GATHERING'
    ");
        $check->execute([':pid' => $profileId, ':gid' => $gatheringId]);
        if ($check->fetchColumn() > 0) return false;

        // insert
        $ins = $this->db->prepare("
      INSERT INTO feedback
        (profileID, gatheringID, locationID, feedbackDesc, feedbackType, date)
      VALUES
        (:pid, :gid, :lid, :desc, 'GATHERING', NOW())
    ");
        return $ins->execute([
            ':pid'  => $profileId,
            ':gid'  => $gatheringId,
            ':lid'  => $locationId,
            ':desc' => $desc
        ]);
    }

    public function getGatheringWithHostInfoByGatheringId($gatheringId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT g.*, p.phone, p.nickname
                FROM gathering g
                JOIN profile p ON g.hostProfileID = p.profileID
                WHERE g.gatheringID = :gatheringId
            ");
            $stmt->bindParam(':gatheringId', $gatheringId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] Error fetching gathering info: " . $e->getMessage());
            return null;
        }
    }

    public function getGatheringWithAllParticipantInfoByGatheringId($gatheringId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT g.*, p.phone, p.nickname
                FROM gathering g
                JOIN profilegathering pg ON g.gatheringID = pg.gatheringID
                JOIN profile p ON pg.profileID = p.profileID
                WHERE g.gatheringID = :gatheringId
            ");
            $stmt->bindParam(':gatheringId', $gatheringId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] Error fetching gathering info: " . $e->getMessage());
            return null;
        }
    }

    public function getRemindersByHost($gatheringID)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT r.*, g.theme, p.nickname
            FROM reminder r
            JOIN gathering g ON r.gatheringID = g.gatheringID
            JOIN profile p ON r.profileID = p.profileID
            WHERE r.gatheringID = :gatheringID
            ORDER BY r.createdAt DESC
        ");
            $stmt->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllRemindersByGatheringId: " . $e->getMessage());
            return false;
        }
    }

    public function getRemindersByParticipant($gatheringID, $profileID)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, g.theme, g.hostProfileID, p.nickname
                FROM reminder r
                JOIN gathering g ON r.gatheringID = g.gatheringID
                JOIN profile p ON r.profileID = p.profileID
                WHERE r.gatheringID = :gatheringID
                AND (r.profileID = :profileID OR r.profileID = g.hostProfileID)
                ORDER BY r.createdAt DESC
            ");

            $stmt->bindParam(':gatheringID', $gatheringID, PDO::PARAM_INT);
            $stmt->bindParam(':profileID', $profileID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRemindersByParticipant: " . $e->getMessage());
            return false;
        }
    }

    public function createReminder($r)
    {
        $sql = "INSERT INTO `reminder` (profileID, description, createdAt, gatheringID) 
                VALUES (:profileID, :description, :createdAt, :gatheringID)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':profileID'    => $r['profileId'],
            ':description'  => $r['description'],
            ':createdAt'    => $r['createdAt'],
            ':gatheringID'  => $r['gatheringId'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    // ============================================================================
    // LOCATION PART
    // ============================================================================
    public function fetchAllGatheringLocation()
    {
        $sql = "SELECT * FROM `location`";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGatheringLocationById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `location` WHERE locationID = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            error_log("LocationDAO Error: " . $e->getMessage());
            return null;
        }
    }

    public function searchLocations($query)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM `location`
        WHERE locationName LIKE :query OR address LIKE :query
        ORDER BY locationName
    ");
        $stmt->execute([':query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocationByGatheringId($gatheringId)
    {
        try {
            $sql = "
                SELECT 
                    l.*
                FROM location l
                JOIN gathering g ON g.locationID = l.locationID
                WHERE g.gatheringID = :gatheringId
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':gatheringId' => $gatheringId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[GatheringDAO] Error fetching location by gathering ID: " . $e->getMessage());
            return null;
        }
    }
}
