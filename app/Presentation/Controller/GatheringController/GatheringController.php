<?php

namespace Presentation\Controller\GatheringController;

use BusinessLogic\Service\GatheringService\CheckGatheringStatus;
use BusinessLogic\Model\GatheringModel\GatheringModel;

require_once __DIR__ . '/../../../BusinessLogic/Model/GatheringModel/GatheringModel.php';


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
            error_log("Search term received: " . $searchTerm);

            if ($searchTerm === '' || $searchTerm === null) {
                $gatherings = $this->listGatherings();
                error_log("Empty search term, returning all gatherings");
                return include $this->fileHelper->getFilePath('GatheringList');
            } else {
                $gatherings = $this->gatheringModel->searchGatherings($searchTerm);
            }
            if (!$gatherings) {
                error_log("No gatherings found for search term: " . $searchTerm);
                return [];
            }

            error_log("Found " . count($gatherings) . " gatherings for search term: " . $searchTerm);
            return include $this->fileHelper->getFilePath('GatheringList');
        } catch (Exception $e) {
            error_log("Error in searchGatherings: " . $e->getMessage());
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

    public function viewMyGatheringDetails($id)
{
    // Get the gathering details by ID
    $gathering = $this->gatheringModel->getGatheringById($id);
    
    // Check if the gathering exists
    if (!$gathering) {
        // Optionally, you can handle the case where the gathering is not found, 
        // maybe redirect to an error page or the list of gatherings
        header("Location: /path-to-error-page.php");
        exit();
    }

    // Perform any additional logic or processing if needed

    // Redirect to the gathering details page
    header("Location: my-gathering-details.php?id=" . urlencode($id));
    exit();
}

}
