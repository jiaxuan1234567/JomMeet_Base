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

    // ------------------------------------------ Create Part ------------------------------------------//
    // Create Page Calling
    public function createReflection()
    {
        include $this->fileHelper->getFilePath('CreateReflection');
    }

    // Create Process
    public function saveReflection()
    {
        $profileId = $_SESSION['profile']['profileID'];
        $reflectionDate = $_POST['reflectionDate'];
        $reflectionTitle = $_POST['reflectionTitle'];
        $reflectionContent = $_POST['reflectionContent'];

        $this->reflectionModel->saveReflection($profileId, $reflectionDate, $reflectionTitle, $reflectionContent);
        $_SESSION['flash_message'] = "Your self-reflection has been successfully created.";
        $_SESSION['flash_type']= "success";
        header("Location: /reflection");
    }

    // ------------------------------------------ View Detail Part ------------------------------------------//
    public function viewReflection($reflectionId)
    {
        // Call the model
        $profileId = $_SESSION['profile']['profileID'];
        $reflectionViewed = $this->reflectionModel->getReflectionById($profileId,$reflectionId);

        if(!$reflectionViewed) {
            $reflectionViewed = $this->reflectionModel->getReflectionById($profileId,$reflectionId);
            if ($reflectionViewed) {
                header('Location: /reflection/view/'.$reflectionViewed['selfreflectID']);
            } else {
                $_SESSION['flash_message'] = "You are not authorized to view this reflection.";
                $_SESSION['flash_type']= "error";
                header("Location: /reflection");
                exit;
            }
        }

        // Pass the result 
        include $this->fileHelper->getFilePath('ViewReflection');
    }

    // ------------------------------------------ Edit Part ------------------------------------------//
    // Get Reflection First
    public function editReflection($reflectionId)
    {
        // Call the model
        $profileId = $_SESSION['profile']['profileID'];
        $reflectionSelected = $this->reflectionModel->getReflectionById($profileId,$reflectionId);

        if(!$reflectionSelected) {
            $reflectionSelected = $this->reflectionModel->getReflectionById($profileId,$reflectionId);
            if ($reflectionSelected) {
                header('Location: /reflection/view/'.$reflectionSelected['selfreflectID']);
            } else {
                $_SESSION['flash_message'] = "You are not authorized to edit this reflection.";
                $_SESSION['flash_type']= "error";
                header("Location: /reflection");
                exit;
            }
        }

        include $this->fileHelper->getFilePath('EditReflection');
    }


    // Save the editing 
    public function editSaveReflection($reflectionId)
    {
        $reflectionTitle = $_POST['reflectionTitle'];
        $reflectionContent = $_POST['reflectionContent'];

        $this->reflectionModel->editSaveReflection($reflectionId, $reflectionTitle, $reflectionContent);
        $_SESSION['flash_message'] = "Your self-reflection has been successfully updated.";
        $_SESSION['flash_type']= "success";
        header("Location: /reflection");
    }

    // ------------------------------------------ Delete Part ------------------------------------------//
    public function deleteReflection($reflectionId)
    {
        $this->reflectionModel->deleteReflectionById($reflectionId);
        $_SESSION['flash_message'] = "Your self-reflection has been successfully deleted.";
        $_SESSION['flash_type']= "success";
        header("Location: /reflection");
    }


    // ------------------------------------------ Validation Part ------------------------------------------//
    public function validateReflection()
    {
        header('Content-Type: application/json');

        $title = $_POST['reflectionTitle'] ?? null;
        $content = $_POST['reflectionContent'] ?? null;

        $result = $this->reflectionModel->validateReflection($title, $content);

        echo json_encode($result);
    }
}
