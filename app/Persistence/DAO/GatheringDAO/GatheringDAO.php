<?php
require_once "Database.php";

class GatheringDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function insertGathering($hostId, $location, $theme, $maxParticipants, $description, $dateTime)
    {
        $stmt = $this->db->prepare("INSERT INTO gatherings (host_id, location, theme, max_participants, description, date_time) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$hostId, $location, $theme, $maxParticipants, $description, $dateTime]);
    }

    public function fetchGatheringById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM gatherings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
