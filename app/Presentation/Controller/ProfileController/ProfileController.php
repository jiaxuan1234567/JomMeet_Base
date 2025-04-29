<?php

namespace Presentation\Controller\ProfileController;

use BusinessLogic\Model\ProfileModel\ProfileModel;
use FileHelper;

class ProfileController
{
    private $profileModel;
    private $fileHelper;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
        $this->fileHelper = new FileHelper('profile');
    }

    // public function viewProfile($id)
    // {
    //     $profile = $this->profileModel->getProfileById($id);
    //     include $this->fileHelper->getFilePath('ProfileDetail');
    // }

    public function editProfile()
    {
        include $this->fileHelper->getFilePath('EditProfile');
    }

}
