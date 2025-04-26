<?php
namespace Presentation\Controller\GatheringController;
use BusinessLogic\Model\GatheringModel\GatheringModel;

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
        $this->gatheringModel = new GatheringModel(Database::getConnection());
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

    public function joinGathering($userid, $gatheringid)
    {
        try {
            if ($gatheringid != null && $userid != null) {
                $result = $this->gatheringModel->addUserToGathering($userid, $gatheringid);
                error_log("Join Result: " . ($result ? 'Success' : 'Failure'));
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
