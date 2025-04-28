<?php

namespace Presentation\Controller\ReflectionController;

use BusinessLogic\Model\ProfileModel\ProfileModel;
use FileHelper;

class ReflectionController
{
    private $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileHelper('reflection');
    }
}
