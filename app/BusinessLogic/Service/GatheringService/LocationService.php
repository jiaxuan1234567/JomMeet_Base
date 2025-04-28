<?php

namespace BusinessLogic\Service\GatheringService;

use Persistence\DAO\GatheringDAO\GatheringDAO;

class LocationService
{
    private $gatheringDAO;

    public function __construct()
    {
        $this->gatheringDAO = new GatheringDAO();
    }
}
