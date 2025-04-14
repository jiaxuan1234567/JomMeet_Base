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

    public function handleAction()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'view':
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $gathering = $this->viewGathering($id);
                        if ($gathering) {
                            include __DIR__ . '/../View/gathering_detail.php';
                        } else {
                            header('Location: /gathering');
                        }
                    } else {
                        header('Location: /gathering');
                    }
                    break;
                case 'list':
                    $gatherings = $this->listGatherings();
                    include __DIR__ . '/../View/gathering_list.php';
                    break;
                default:
                    header('Location: /gathering');
                    break;
            }
        } else {
            // If no action is specified, show the list
            $gatherings = $this->listGatherings();
            include __DIR__ . '/../View/gathering_list.php';
        }
    }
}
