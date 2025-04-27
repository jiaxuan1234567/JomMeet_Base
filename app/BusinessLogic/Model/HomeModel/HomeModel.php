<?php

namespace BusinessLogic\Model\HomeModel;

use BusinessLogic\Model\GatheringModel\GatheringModel;

class HomeModel
{

    public function __construct() {}

    public function getAllGatherings()
    {
        return (new GatheringModel())->getAllGatherings();
    }
}