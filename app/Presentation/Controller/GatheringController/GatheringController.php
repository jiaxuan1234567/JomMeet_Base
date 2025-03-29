<?php
require_once "../../BusinessLogic/Model/GatheringModel.php";

class GatheringController
{
    private $gatheringModel;

    public function __construct()
    {
        $this->gatheringModel = new GatheringModel();
    }

    public function createGathering()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $hostId = $_POST['host_id'];
            $location = $_POST['location'];
            $theme = $_POST['theme'];
            $maxParticipants = $_POST['max_participants'];
            $description = $_POST['description'];
            $dateTime = $_POST['date_time'];

            $result = $this->gatheringModel->createGathering($hostId, $location, $theme, $maxParticipants, $description, $dateTime);

            if ($result) {
                header("Location: success.php");
            } else {
                echo "Error creating gathering.";
            }
        }
    }
}

$controller = new GatheringController();
$controller->createGathering();
