<?php

namespace Presentation\Controller\GatheringController;

use BusinessLogic\Service\GatheringService\CheckGatheringStatus;
use BusinessLogic\Model\GatheringModel\GatheringModel;

// need dlt
use BusinessLogic\Service\GatheringService\LocationService;

use Database;
use Exception;
use FileHelper;

use PDOException;

class GatheringController
{
    private $gatheringModel;
    private $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileHelper('gathering');
        $this->gatheringModel = new GatheringModel();
    }

    // -- My Gathering --
    public function viewCreate()
    {
        include $this->fileHelper->getFilePath('CreateGathering');
    }

    public function createGathering() {}

    public function viewDetail($id)
    {
        $gathering = $this->gatheringModel->getGatheringById($id);
        include $this->fileHelper->getFilePath('GatheringDetail');
    }

    public function viewSelectLocation()
    {
        require_once $this->fileHelper->getFilePath('SelectLocation');
    }

    public function apiSavedLocations()
    {
        header('Content-Type: application/json');
        $svc = new LocationService();
        // returns array of ['placeId'=>…, 'name'=>…, 'address'=>…, 'latitude'=>…, 'longitude'=>…]
        echo json_encode($svc->getAllLocations());
    }

    // helper function to save location (need delete in future)
    public function saveLocation()
    {
        // read JSON POST body
        $loc = json_decode(file_get_contents('php://input'), true);

        $db = Database::getConnection();
        try {
            try {
                $placeId = $loc['place_id'];
                $name = $loc['name'];
                $address = $loc['address'];
                $latitude = $loc['latitude'];
                $longtitude = $loc['longitude'];

                $sql = "
                      INSERT INTO `location` (placeID, locationName, address,  longitude, latitude)
                      VALUES (:pid, :name, :addr, :lng, :lat)
                      ON DUPLICATE KEY UPDATE
                        locationName = VALUES(locationName),
                        `address`    = VALUES(`address`),
                        latitude     = VALUES(latitude),
                        longitude    = VALUES(longitude)
        
                    ";
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    ':pid'  => $placeId,
                    ':name' => $name,
                    ':addr' => $address,
                    ':lng'  => $longtitude,
                    ':lat'  => $latitude,
                ]);
            } catch (PDOException $e) {
                error_log("Error in saveLocation: " . $e->getMessage());
                return false;
            }
            http_response_code(200);
            echo $status;
        } catch (Exception $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }

    // -- Join Gathering --

    public function joinGathering()
    {
        try {
            $gatheringid = $_POST['gatheringid'] ?? null;
            $userid = $_POST['userid'] ?? null;

            if ($gatheringid != null && $userid != null) {
                $result = $this->gatheringModel->addUserToGathering($userid, $gatheringid);
                error_log("Join Result: " . ($result ? 'Success' : 'Failure'));
                $gatherings = $this->gatheringModel->getAllGatherings();
                return include $this->fileHelper->getFilePath('GatheringList');
            } else {
                error_log("Missing gatheringid or userid");
            }
        } catch (Exception $e) {
            error_log("Error in joinGathering: " . $e->getMessage());
            return false;
        }
    }

    public function verifyUserInGathering($userID, $gatheringID)
    {
        return $this->gatheringModel->verifyUserInGathering($userID, $gatheringID);
    }

    public function isBeforeStartTime($gatheringID)
    {
        try {
            return $this->gatheringModel->isBeforeStartTime($gatheringID);
        } catch (Exception $e) {
            error_log("Error in isBeforeStartTime: " . $e->getMessage());
            return false;
        }
    }

    public function listGatherings()
    {
        try {
            $gatherings = $this->gatheringModel->getAllGatherings();
            if ($gatherings === false) {
                error_log("getAllGatherings returned false");
                return [];
            }
            return $gatherings;
        } catch (Exception $e) {
            error_log("Error in listGatherings: " . $e->getMessage());
            return [];
        }
    }

    public function searchGatherings()
    {
        try {
            $searchTerm = $_POST['searchTerm'] ?? '';

            if ($searchTerm === '' || $searchTerm === null) {
                $gatherings = $this->listGatherings();
                return include $this->fileHelper->getFilePath('GatheringList');
            } else {
                $gatherings = $this->gatheringModel->searchGatherings($searchTerm);
                if (!$gatherings) {
                    error_log("[GatheringController] Search returned no results for term: '" . $searchTerm . "', showing all gatherings");
                    $gatherings = $this->listGatherings();
                }
            }

            return include $this->fileHelper->getFilePath('GatheringList');
        } catch (Exception $e) {
            error_log("[GatheringController] Error in searchGatherings: " . $e->getMessage());
            return [];
        }
    }

    public function isNewGatheringConflicting($userID, $gatheringID)
    {
        try {
            return $this->gatheringModel->isNewGatheringConflicting($userID, $gatheringID);
        } catch (Exception $e) {
            error_log("Error in isNewGatheringConflicting: " . $e->getMessage());
            return false;
        }
    }

    public function checkGatheringStatus()
    {
        $checker = new CheckGatheringStatus();
        $updated = $checker->run();

        header('Content-Type: application/json');
        echo json_encode(['updated' => $updated]);
    }
}
