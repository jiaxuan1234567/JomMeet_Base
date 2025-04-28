<?php

namespace Presentation\Controller\GatheringController;

use BusinessLogic\Service\GatheringService\CheckGatheringStatus;
use BusinessLogic\Model\GatheringModel\GatheringModel;

// need dlt
use BusinessLogic\Service\GatheringManagementService\LocationService;

use Database;
use Exception;
use FileHelper;

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

    public function saveLocation()
    {
        // read JSON POST body
        $data = json_decode(file_get_contents('php://input'), true);

        // basic validation
        if (empty($data['place_id'] ?? '') || empty($data['gathering_id'] ?? '')) {
            http_response_code(400);
            echo "Missing place_id or gathering_id";
            return;
        }

        // hand off to service
        $svc = new LocationService();
        try {
            $svc->addLocationToGathering($data['gathering_id'], $data);
            http_response_code(200);
            echo "OK";
        } catch (\Exception $e) {
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
            error_log("Error in listAllGatherings: " . $e->getMessage());
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
