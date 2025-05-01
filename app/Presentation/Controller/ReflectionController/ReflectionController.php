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

    public function saveReflection()
    {
        $profileId = $_SESSION['profile_id'];
        $reflectionDate = $_POST['reflectionDate'];
        $reflectionTitle = $_POST['reflectionTitle'];
        $reflectionContent = $_POST['reflectionContent'];

        $this->reflectionModel->saveReflection($profileId,$reflectionDate,$reflectionTitle,$reflectionContent);
        header("Location: /reflection");
    }

    public function editReflection($reflectionId)
    {
        $reflectionSelected = $this->reflectionModel->getReflectionById($reflectionId);
        include $this->fileHelper->getFilePath('EditReflection');
    }

    public function editSaveReflection($reflectionId)
    {
        $reflectionTitle = $_POST['reflectionTitle'];
        $reflectionContent = $_POST['reflectionContent'];

        $this->reflectionModel->editSaveReflection($reflectionId,$reflectionTitle,$reflectionContent);
        header("Location: /reflection");
    }

    public function viewReflection($reflectionId)
    {
        $reflectionViewed = $this->reflectionModel->getReflectionById($reflectionId);
        include $this->fileHelper->getFilePath('ViewReflection');
    }
}
