<?php
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
            $sql = "SELECT g.*, l.locationName, l.address, l.image as locationImage 
                    FROM gathering g 
                    JOIN location l ON g.locationID = l.locationID";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                error_log("No gatherings found in database");
            } else {
                error_log("Found " . count($result) . " gatherings");
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error in getAllGatherings: " . $e->getMessage());
            error_log("SQL Query: " . $sql);
            return false;
        }
    }

    public function getGatheringById($id)
    {
        try {
            $sql = "SELECT g.*, l.locationName, l.address, l.image as locationImage 
                    FROM gathering g 
                    JOIN location l ON g.locationID = l.locationID 
                    WHERE g.gatheringID = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getGatheringById: " . $e->getMessage());
            error_log("SQL Query: " . $sql);
            return false;
        }
    }
}
