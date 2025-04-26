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

    public function gatheringHome()
    {
        $gatherings = (new HomeModel())->getAllGatherings();
        include $this->fileHelper->getFilePath('GatheringList');
    }
}