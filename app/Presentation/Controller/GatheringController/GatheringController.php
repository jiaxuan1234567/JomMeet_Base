<?php
// require_once ROOTPATH . '/fileRegister.php';
// require_once getFilePath('Database');
// require_once getFilePath('GatheringModel');
require_once(ROOTPATH . '/fileRegister.php');
require ROOTPATH . '/BusinessLogic/Model/GatheringModel/GatheringModel.php';
require_once '../app/Database.php';

class GatheringController
{
    private $gatheringModel;
    private $path;

    public function __construct()
    {
        $this->path = getFilePath("gathering");
        try {
            $db = DatabaseTest::getConnection();
            $this->gatheringModel = new GatheringModel($db);
        } catch (Exception $e) {
            error_log("Error in GatheringController constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function redirect($key)
    {
        return $this->path[$key];
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

    public function action()
    {
        try {
            return $this->gatheringModel->handleAction();
        } catch (Exception $e) {
            error_log("Error in action: " . $e->getMessage());
            return null;
        }
    }
}
