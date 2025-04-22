<?php
require_once __DIR__ . '../../../Business/Model/GatheringModel.php';
require_once __DIR__ . '../../../Persistence/DAO/GatheringDAO.php'; 
$dao = new GatheringDAO($db);
$gatheringModel = new GatheringModel($dao);

class GatheringController
{
    private $gatheringModel;

    public function __construct( $gatheringModel)
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

    public function viewGathering($id)
    {
        try {
            return $this->gatheringModel->getGatheringById($id);
        } catch (Exception $e) {
            error_log("Error in viewGathering: " . $e->getMessage());
            return null;
        }
    }
}
