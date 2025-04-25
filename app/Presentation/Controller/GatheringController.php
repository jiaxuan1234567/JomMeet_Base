<?php
require_once __DIR__ . '../../../Business/Model/GatheringModel.php';
require_once __DIR__ . '../../../Persistence/DAO/GatheringDAO.php';
$gatheringModel = new GatheringModel($dao);

class GatheringController
{
    private $gatheringModel;

    public function __construct($gatheringModel)
    {
        $this->gatheringModel = $gatheringModel;
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

    public function viewGathering()
    {
        try {
            $id = $_GET['id'] ?? null;
            if ($id != null) {
                return $this->gatheringModel->getGatheringById($id);
            }
        } catch (Exception $e) {
            error_log("Error in viewGathering: " . $e->getMessage());
            return null;
        }
    }

    public function joinGathering()
    {
        try {
            $gatheringid = $_POST['id'] ?? null;
            $userid = $_POST['userid'] ?? null;

            if ($gatheringid != null && $userid != null) {
                $result = $this->gatheringModel->addUserToGathering($userid, $gatheringid);
                error_log("Join Result: " . ($result ? 'Success' : 'Failure'));
                return $result;
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

    public function isBeforeStartTime($gatheringID){
        try {
            return $this->gatheringModel->isBeforeStartTime($gatheringID);
        } catch (Exception $e) {
            error_log("Error in isBeforeStartTime: " . $e->getMessage());
            return false;
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
}
