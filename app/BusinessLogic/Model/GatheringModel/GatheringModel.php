<?php
require_once "../../Persistence/DAO/GatheringDAO.php";

class GatheringModel
{
    private $gatheringDAO;

    public function __construct()
    {
        $this->gatheringDAO = new GatheringDAO();
    }

    public function createGathering($hostId, $location, $theme, $maxParticipants, $description, $dateTime)
    {
        if (empty($hostId) || empty($location) || empty($theme) || empty($maxParticipants) || empty($dateTime)) {
            return false;
        }
        return $this->gatheringDAO->insertGathering($hostId, $location, $theme, $maxParticipants, $description, $dateTime);
    }

    public function getGatheringById($id)
    {
        return $this->gatheringDAO->fetchGatheringById($id);
    }
}
