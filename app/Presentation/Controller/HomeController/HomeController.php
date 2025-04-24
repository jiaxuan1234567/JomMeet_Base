<?php

namespace Presentation\Controller\HomeController;

use BusinessLogic\Model\HomeModel\HomeModel;
use FileHelper;

class HomeController
{

    private $paths;

    public function __construct()
    {
        $this->paths = new FileHelper('home');
    }

    public function redirect($key)
    {
        return $this->paths->getFilePath($key);
    }

    public function home()
    {
        include $this->paths->getFilePath('HomePage');
    }

    public function gatheringHome()
    {
        $gatherings = (new HomeModel())->getAllGatherings();
        include $this->paths->getFilePath('GatheringList');
    }
}
