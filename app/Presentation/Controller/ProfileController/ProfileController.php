<?php

namespace Presentation\Controller\ProfileController;

use BusinessLogic\Model\ProfileModel\ProfileModel;
use FileHelper;

class ProfileController
{
    private $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileHelper('profile');
    }
}
