<?php
require_once __DIR__ . '/../../Persistence/DAO/GatheringDAO.php';

class GatheringModel {
    private $gatheringDAO;

    public function __construct($db) {
        $this->gatheringDAO = new GatheringDAO($db);
    }

    public function getAllGatherings() {
        return $this->gatheringDAO->getAllGatherings();
    }

    public function getGatheringById($id) {
        return $this->gatheringDAO->getGatheringById($id);
    }
} 