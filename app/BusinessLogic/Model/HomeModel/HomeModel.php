<?php

namespace BusinessLogic\Model\HomeModel;

use BusinessLogic\Model\ReflectionModel\ReflectionModel;
use BusinessLogic\Model\GatheringModel\GatheringModel;

class HomeModel
{

    public function __construct() {}

    public function getAllReflections()
    {
        $profileId = $_SESSION['profile_id'];
        return (new ReflectionModel())->getAllReflections($profileId);
    }

    public function getAllGatherings()
    {
        return (new GatheringModel())->getAllGatherings();
    }
}
