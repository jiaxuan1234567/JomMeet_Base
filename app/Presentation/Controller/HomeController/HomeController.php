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

    public function home()
    {
        include $this->fileHelper->getFilePath('HomePage');
    }

    public function profileHome()
    {
        $userId = (int) ($_SESSION['profile_id'] ?? 0);
        $profile = (new HomeModel())->getUserProfileById($userId);
        $_SESSION['profile'] = $profile;

        include $this->fileHelper->getFilePath('Profile');
        error_log('[DEBUG] profileHome() called');
    }

    public function reflectionHome()
    {
        $reflections = (new HomeModel())->getAllReflections();
        include $this->fileHelper->getFilePath('ReflectionList');
    }

    public function gatheringHome()
    {
        $gatherings = (new HomeModel())->getAvailableGatherings();
        include $this->fileHelper->getFilePath('GatheringList');
    }

    public function myGatheringHome()
    {
        //$myGatherings = (new HomeModel())->getMyGatherings();
        $tabs = (new HomeModel())->getMyGatherings();
        include $this->fileHelper->getFilePath('MyGatheringList');
    }

    public function loginHome()
    {
        if (!empty($_SESSION['profile']['profileID'])) {
            header('Location: /');
            exit;
        }

        include $this->fileHelper->getFilePath('Login');
    }

    public function logoutHome()
    {
        $status = (new HomeModel())->logout();

        if ($status) {
            header('Location: /login');
            exit;
        }
    }

    public function help()
    {
        include $this->fileHelper->getFilePath('Help');
    }

    public function about()
    {
        include $this->fileHelper->getFilePath('About');
    }
}
