<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\LocationDAO;
use Exception;

class LocationModel
{
    private LocationDAO $locationDAO;

    public function __construct()
    {
        $this->locationDAO = new LocationDAO();
    }

    public function getAllLocations()
    {
        try {
            return $this->locationDAO->fetchAll();
        } catch (Exception $e) {
            error_log("LocationModel Error: " . $e->getMessage());
            return [];
        }
    }

    public function getLocationById($id)
    {
        return $this->locationDAO->getLocationById($id);
    }
}
