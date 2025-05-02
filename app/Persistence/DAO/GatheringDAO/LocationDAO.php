<?php

namespace Persistence\DAO\GatheringDAO;

use PDO;
use Database;
use PDOException;

class LocationDAO
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  public function fetchAll()
  {
    $sql = "SELECT * FROM `location`";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getLocationById($id)
  {
    try {
      $stmt = $this->db->prepare("SELECT * FROM `location` WHERE locationID = :id");
      $stmt->execute([':id' => $id]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("LocationDAO Error: " . $e->getMessage());
      return null;
    }
  }
}
