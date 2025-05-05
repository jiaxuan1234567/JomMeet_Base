<?php

namespace BusinessLogic\Model\ReflectionModel;

use Persistence\DAO\ReflectionDAO\ReflectionDAO;
use Exception;
use FileHelper;

class ReflectionModel
{
    private $reflectionDAO;

    public function __construct()
    {
        $this->reflectionDAO = new ReflectionDAO();
    }

    // ------------------------------------------ View List Part ------------------------------------------//
    public function getAllReflections($profileId)
    {
        return $this->reflectionDAO->getAllReflections($profileId);
    }

    // ------------------------------------------ Create Part ------------------------------------------//
    public function saveReflection($profileId, $reflectionDate, $reflectionTitle, $reflectionContent)
    {
        return $this->reflectionDAO->saveReflection($profileId, $reflectionDate, $reflectionTitle, $reflectionContent);
    }

    // ------------------------------------------ View Detail Part ------------------------------------------//
    public function getReflectionById($profileId,$reflectionId)
    {
        $reflection = $this->reflectionDAO->getReflectionById($profileId,$reflectionId);

        // If the reflection does not exist, return null
        if (!$reflection) {
            return null;
        }
        // Return the reflection if it exists
        return $reflection;
    }

    // ------------------------------------------ Edit Part ------------------------------------------//
    // Save the editing
    public function editSaveReflection($reflectionId, $reflectionTitle, $reflectionContent)
    {
        return $this->reflectionDAO->editSaveReflection($reflectionId, $reflectionTitle, $reflectionContent);
    }

    // ------------------------------------------ Delete Part ------------------------------------------//
    public function deleteReflectionById($reflectionId)
    {
        return $this->reflectionDAO->deleteReflectionById($reflectionId);
    }

    // ------------------------------------------ Validation Part ------------------------------------------//
    public function validateReflection($reflectionTitle, $reflectionContent)
    {
        
        // Validate Title
        if ($reflectionTitle !== null) {
            if (trim($reflectionTitle) === '') {
                return ['success' => false, 'field' => 'reflectionTitle', 'message' => 'The input field cannot be empty. Please enter the title in the input field.'];
            }
            if (mb_strlen($reflectionTitle) > 50) {
                return ['success' => false, 'field' => 'reflectionTitle', 'message' => 'Your message is too long. Please limit it to 50 characters.'];
            }
        }

        // Validate Content
        if ($reflectionContent !== null) {
            if (trim($reflectionContent) === '') {
                return ['success' => false, 'field' => 'reflectionContent', 'message' => 'The input field cannot be empty. Please enter the content in the input field.'];
            }
            if (mb_strlen($reflectionContent) > 5000) {
                return ['success' => false, 'field' => 'reflectionContent', 'message' => 'Your message is too long. Please limit it to 5000 characters.'];
            }
        }

        return ['success' => true];
    }
}
