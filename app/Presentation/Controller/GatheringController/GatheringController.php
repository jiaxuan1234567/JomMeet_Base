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
            $this->gatheringModel = new GatheringModel(DatabaseTest::getConnection());
        } catch (Exception $e) {
            error_log("Error in GatheringController constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function render($key, $data = [])
    {
        extract($data);
        return include($this->path[$key]);
    }

    public function listGathering()
    {
        $gatherings = $this->gatheringModel->getAllGatherings();
        $this->render('GatheringList', ['gatherings' => $gatherings]);
    }

    public function viewGathering($id)
    {
        try {
            $gathering = $this->gatheringModel->getGatheringById($id);
            $this->render('JoinGatheringDetail', ['gathering' => $gathering]);
        } catch (Exception $e) {
            error_log("Error in viewGathering: " . $e->getMessage());
            return null;
        }
    }

    public function action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $action = $_GET['action'] ?? 'list';
            switch ($action) {
                case 'view':
                    $this->viewGathering($_GET['id'] ?? null);
                    break;
                default:
                    $this->listGathering();
            }
        }
    }

    public function createGathering($postData)
    {
        $this->gatheringModel->createGathering($postData);
        header('Location: /gathering');
        exit;
    }

    public function dispatch($request)
    {
        $action = $request['action'] ?? 'list';

        switch ($action) {
            case 'view':
                $id = $request['id'] ?? null;
                $this->viewGathering($id);
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->createGathering($_POST);
                } else {
                    $this->render('CreateGathering');
                }
                break;
            default:
                $this->listGathering();
        }
    }
}
