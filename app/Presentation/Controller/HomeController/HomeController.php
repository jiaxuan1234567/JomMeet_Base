<?php

namespace Presentation\Controller\HomeController;

use BusinessLogic\Model\HomeModel\HomeModel;
use FileHelper;

class HomeController
{

    private $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileHelper('home');
    }

    public function redirect($key)
    {
        return $this->fileHelper->getFilePath($key);
    }

    public function home()
    {
        include $this->fileHelper->getFilePath('HomePage');
    }

    public function profileHome()
    {
        include $this->fileHelper->getFilePath('Profile');
    }

    public function reflectionHome()
    {
        $reflections = (new HomeModel())->getAllReflections();
        include $this->fileHelper->getFilePath('ReflectionList');
    }

    public function gatheringHome()
    {
        $gatherings = (new HomeModel())->getAllGatherings();
        include $this->fileHelper->getFilePath('GatheringList');
    }

    public function myGatheringHome()
    {
        $hostProfileId = $_SESSION['profile_id'];
        $myGatherings = (new HomeModel())->getMyGatherings($hostProfileId);
        include $this->fileHelper->getFilePath('MyGatheringList');
    }
}
