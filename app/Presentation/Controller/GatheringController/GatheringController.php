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
        $sel = $_SESSION['selected_location'] ?? ['locationId' => '', 'address' => ''];
        $locationId  = $sel['locationId'];
        $address   = $sel['address'];

        unset($_SESSION['selected_location']);

        include $this->fileHelper->getFilePath('CreateGathering');
    }

    public function createGathering()
    {
        $data = [
            'theme'          => $_POST['inputTheme'] ?? '',
            'inputPax' => $_POST['inputPax'] ?? '',
            'date'       => $_POST['inputDate'] ?? '',
            'startTime' => $_POST['startTime'] ?? '',
            'endTime' => $_POST['endTime'] ?? '',
            'locationId'     => $_POST['locationId'] ?? '',
        ];

        $data = [
            'locationId'        => (int)($_POST['locationId'] ?? 0),
            'theme'             => trim($_POST['inputTheme'] ?? ''),
            'maxParticipant'    => (int)($_POST['inputPax'] ?? 0),
            'minParticipant'    => (int)($_POST['minParticipant'] ?? 3),
            'currentParticipant' => 1,    // new gathering starts at zero
            'date'              => $_POST['inputDate'] ?? '',
            'startTime'         => $_POST['startTime'] ?? '',
            'endTime'           => $_POST['endTime'] ?? '',
            'status'            => 'NEW',
            'preference'        => $_POST['preference'] ?? 'ENTERTAINMENT',
        ];

        try {
            $newId = $this->gatheringModel->createGathering($data);
            // 3) Redirect to a "view" page or index
            header("Location: /gathering/view/{$newId}");
            exit;
        } catch (Exception $e) {
            // on error, you could re-render the form with $e->getMessage()
            http_response_code(500);
            echo "Error creating gathering: " . htmlspecialchars($e->getMessage());
        }
    }

    public function viewSelectLocation()
    {
        $redirectUrl = $_GET['redirect'] ?? '/my-gathering/create';
        require_once $this->fileHelper->getFilePath('SelectLocation');
    }

    public function selectLocationSubmit()
    {
        $id      = $_POST['locationId']  ?? null;
        $address = $_POST['address']     ?? null;

        if ($id && $address) {
            $_SESSION['selected_location'] = [
                'locationId' => $id,
                'address'    => $address,
            ];
        }

        // Redirect back to the create‐form
        header('Location: /my-gathering/create');
        exit;
    }

    public function viewDetail($id)
    {
        $gathering = $this->gatheringModel->getGatheringById($id);
        include $this->fileHelper->getFilePath('GatheringDetail');
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
                header("Location: /gathering");
                // return include $this->fileHelper->getFilePath('GatheringList');
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

    public function matchGathering($userid)
    {
        try {
            error_log("[GatheringController] ===== MATCH BUTTON CLICKED =====");
            error_log("[GatheringController] User ID received: " . $userid);

            error_log("[GatheringController] Calling matchGathering model...");
            $gatherings = $this->gatheringModel->matchGathering($userid);
            error_log("[GatheringController] Model execution completed");
            error_log("[GatheringController] Number of matched gatherings: " . count($gatherings));

            if (empty($gatherings)) {
                error_log("[GatheringController] No gatherings matched user preferences");
            } else {
                error_log("[GatheringController] Displaying matched gatherings");
            }

            return include $this->fileHelper->getFilePath('GatheringList');
        } catch (Exception $e) {
            error_log("[GatheringController] ERROR in matchGathering: " . $e->getMessage());
            error_log("[GatheringController] Stack trace: " . $e->getTraceAsString());
            return include $this->fileHelper->getFilePath('GatheringList');
        }
    }
}
