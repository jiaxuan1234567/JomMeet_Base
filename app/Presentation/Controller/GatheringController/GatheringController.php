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
        $this->fileHelper = new FileHelper("gathering");
        try {
            $this->gatheringModel = new GatheringModel(Database::getConnection());
        } catch (Exception $e) {
            error_log("Error in GatheringController constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function viewDetail($id)
    {
        $gathering = $this->gatheringModel->getGatheringById($id);
        include $this->fileHelper->getFilePath('JoinGatheringDetail');
    }

    // public function render($key, $data = [])
    // {
    //     extract($data);
    //     return include($this->fileHelper[$key]);
    // }

    // public function listGathering()
    // {
    //     $gatherings = $this->gatheringModel->getAllGatherings();
    //     $this->render('GatheringList', ['gatherings' => $gatherings]);
    // }

    // public function viewGathering($id)
    // {
    //     try {
    //         $gathering = $this->gatheringModel->getGatheringById($id);
    //         $this->render('JoinGatheringDetail', ['gathering' => $gathering]);
    //     } catch (Exception $e) {
    //         error_log("Error in viewGathering: " . $e->getMessage());
    //         return null;
    //     }
    // }

    // public function action()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $action = $_GET['action'] ?? 'list';
    //         switch ($action) {
    //             case 'view':
    //                 $this->viewGathering($_GET['id'] ?? null);
    //                 break;
    //             default:
    //                 $this->listGathering();
    //         }
    //     }
    // }

    // public function createGathering($postData)
    // {
    //     $this->gatheringModel->createGathering($postData);
    //     header('Location: /gathering');
    //     exit;
    // }

    // public function dispatch($request)
    // {
    //     $action = $request['action'] ?? 'list';

    //     switch ($action) {
    //         case 'view':
    //             $id = $request['id'] ?? null;
    //             $this->viewGathering($id);
    //             break;
    //         case 'create':
    //             if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //                 $this->createGathering($_POST);
    //             } else {
    //                 $this->render('CreateGathering');
    //             }
    //             break;
    //         default:
    //             $this->listGathering();
    //     }
    // }
}
