<?php

namespace Presentation\Controller\ReflectionController;

use BusinessLogic\Model\ReflectionModel\ReflectionModel;
use FileHelper;

class ReflectionController
{
    private $reflectionModel;
    private $fileHelper;

    public function __construct()
    {
        $this->reflectionModel = new ReflectionModel();
        $this->fileHelper = new FileHelper('reflection');
    }

    public function createReflection()
    {
        include $this->fileHelper->getFilePath('CreateReflection');
    }

    public function editReflection()
    {
        include $this->fileHelper->getFilePath('EditReflection');
    }

    public function viewReflection()
    {
        include $this->fileHelper->getFilePath('ViewReflection');
    }
}
