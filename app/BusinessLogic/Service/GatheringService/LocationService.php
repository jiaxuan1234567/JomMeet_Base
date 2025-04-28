<?php

namespace BusinessLogic\Service\GatheringManagementService;

use Persistence\DAO\GatheringDAO\GatheringDAO;

class LocationService
{
    private $gatheringDAO;

    public function __construct()
    {
        $this->gatheringDAO = new GatheringDAO();
    }

    public function addLocationToGathering(array $locData)
    {
        return $this->gatheringDAO->saveLocation($locData);
    }
}
