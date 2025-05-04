<?php

namespace BusinessLogic\Model\HomeModel;

use BusinessLogic\Model\ReflectionModel\ReflectionModel;
use BusinessLogic\Model\GatheringModel\GatheringModel;
use BusinessLogic\Model\ProfileModel\ProfileModel;

class HomeModel
{
    public function __construct() {}

    // public function getProfileDetails()
    // {
    //     $profileId = $_SESSION['profile_id'];
    //     return (new ProfileModel())->getProfileDetails($profileId);
    // }

    public function getAllReflections()
    {
        $profileId = $_SESSION['profile']['profileID'];
        return (new ReflectionModel())->getAllReflections($profileId);
    }

    public function getAvailableGatherings()
    {
        $profileId = $_SESSION['profile']['profileID'];
        return (new GatheringModel())->getAvailableGatherings($profileId);
    }

    public function getMyGatherings()
    {
        $hostProfileId = $_SESSION['profile']['profileID'];
        return (new GatheringModel())->getMyGatheringsWithTab($hostProfileId);
    }

    public function getUserProfileById($userId)
    {
        return (new ProfileModel())->getUserByProfileID($userId);
    }   

    public function logout()
    {
        return (new ProfileModel())->logout();
    }
}
