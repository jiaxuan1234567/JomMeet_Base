<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use Exception;
use FileHelper;

//require_once '../app/Persistence/DAO/GatheringDAO/GatheringDAO.php';

class GatheringModel
{
    private $gatheringDAO;

    public function __construct()
    {
        $this->gatheringDAO = new GatheringDAO();
    }

    public function getAllGatherings()
    {
        try {
            $gatherings = $this->gatheringDAO->fetchAllGatherings();
            return $gatherings ?: [];
        } catch (Exception $e) {
            error_log("BLL Error: " . $e->getMessage());
            return [];
        }
    }

    public function getGatheringById($id)
    {
        try {
            $gathering = $this->gatheringDAO->getGatheringById($id);
            return $gathering ?: [];
        } catch (Exception $e) {
            error_log("BLL Error: " . $e->getMessage());
            return [];
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
                        $gathering = $this->getGatheringById($id);
                        if ($gathering) {
                            //require_once(getFilePath('GatheringDetail'));
                        } else {
                            header('Location: /gathering');
                        }
                    } else {
                        header('Location: /gathering');
                    }
                    break;
                case 'list':
                    $gatherings = $this->getAllGatherings();
                    //require_once(getFilePath('GatheringList'));
                    break;
                default:
                    header('Location: /gathering');
                    break;
            }
        } else {
            // If no action is specified, show the list
            $gatherings = $this->getAllGatherings();
            //require_once(getFilePath('GatheringList'));
        }
    }

    public function createGathering($postData) {}
}
