<?php

namespace Persistence\DAO\GatheringDAO;

use PDO;
use Database;

class LocationDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function fetchAll()
    {
        $sql = "
          SELECT
            placeID   AS placeId,
            locationName AS name,
            address,
            latitude,
            longitude
          FROM `location`
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
