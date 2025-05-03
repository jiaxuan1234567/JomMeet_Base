<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\LocationDAO;
use Exception;

class LocationModel
{
    private $locationDAO;

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

    public function searchLocations($query)
    {
        try {
            return $this->locationDAO->searchLocations($query);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
