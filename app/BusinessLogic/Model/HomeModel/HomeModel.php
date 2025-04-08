<?php
require_once __DIR__ . '/../../../Database.php';

class homeModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getUserSummary($userId)
    {
        $stmt = $this->db->prepare("SELECT name, gatheringsJoined, selfReflectionsCount FROM users WHERE id = :id");
        $stmt->bindParam(":id", $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : ['name' => 'User', 'gatheringsJoined' => 0, 'selfReflectionsCount' => 0];
    }

    public function getNotifications($userId)
    {
        $stmt = $this->db->prepare("SELECT message FROM notifications WHERE userId = :userId ORDER BY createdAt DESC LIMIT 5");
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
