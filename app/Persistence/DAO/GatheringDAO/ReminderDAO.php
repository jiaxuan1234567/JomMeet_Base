<?php

namespace Persistence\DAO\GatheringDAO;

use PDO;
use PDOException;
use Database;

class ReminderDAO
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

    public function getAllReminders()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reminder");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllReminders: " . $e->getMessage());
            return false;
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
        $sql = "INSERT INTO `gathering` (profileID, description, createdAt, gatheringID) 
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
}
