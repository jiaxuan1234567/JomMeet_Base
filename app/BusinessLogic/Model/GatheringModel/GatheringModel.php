<?php
require_once '../../../Persistence/DAO/GatheringDAO/GatheringDAO.php';

class GatheringModel
{
    private $gatheringDAO;

    public function __construct($db)
    {
        $this->gatheringDAO = new GatheringDAO($db);
    }

    public function getAllGatherings()
    {
        return $this->gatheringDAO->getAllGatherings();
    }

    public function getGatheringById($id)
    {
        return $this->gatheringDAO->getGatheringById($id);
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
                            require_once(getFilePath('GatheringDetail'));
                        } else {
                            header('Location: /gathering');
                        }
                    } else {
                        header('Location: /gathering');
                    }
                    break;
                case 'list':
                    $gatherings = $this->getAllGatherings();
                    require_once(getFilePath('GatheringList'));
                    break;
                default:
                    header('Location: /gathering');
                    break;
            }
        } else {
            // If no action is specified, show the list
            $gatherings = $this->getAllGatherings();
            require_once(getFilePath('GatheringList'));
        }
    }
}
