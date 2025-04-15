<?php
require_once __DIR__ . '/../../Persistence/config/Database.php';
require_once __DIR__ . '/../../BusinessLogic/Model/GatheringModel.php';

class GatheringController
{
    private $gatheringModel;

    public function __construct()
    {
        try {
            $db = DatabaseTest::getConnection();
            $this->gatheringModel = new GatheringModel($db);
        } catch (Exception $e) {
            error_log("Error in GatheringController constructor: " . $e->getMessage());
            throw $e;
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

    public function viewGathering($id)
    {
        try {
            return $this->gatheringModel->getGatheringById($id);
        } catch (Exception $e) {
            error_log("Error in viewGathering: " . $e->getMessage());
            return null;
        }
    }

    public function action(){
        try{
            return $this->gatheringModel->handleAction();
        } catch(Exception $e) {
            error_log("Error in action: " . $e->getMessage());
            return null;
        }
    }
}
