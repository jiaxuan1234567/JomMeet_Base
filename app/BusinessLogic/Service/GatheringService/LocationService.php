<?php

namespace BusinessLogic\Service\GatheringService;

use Persistence\DAO\GatheringDAO\LocationDAO;

class LocationService
{
    private $locationDAO;

    public function __construct()
    {
        $this->locationDAO = new LocationDAO();
    }

    public function getAllLocations(): array
    {
        return $this->locationDAO->fetchAll();
    }
}
