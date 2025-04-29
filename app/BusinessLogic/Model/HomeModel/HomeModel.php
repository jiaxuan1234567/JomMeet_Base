<?php

namespace BusinessLogic\Model\HomeModel;

use BusinessLogic\Model\ReflectionModel\ReflectionModel;
use BusinessLogic\Model\GatheringModel\GatheringModel;
use BusinessLogic\Model\ProfileModel\ProfileModel;

class HomeModel
{

    public function __construct() {}

    public function getProfileDetails()
    {
        $profileId = $_SESSION['profile_id'];
        return (new ProfileModel())->getProfileDetails($profileId);
    }

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
