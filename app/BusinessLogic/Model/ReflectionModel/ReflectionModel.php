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

    public function getAllReflections($profileId)
    {
        return $this->reflectionDAO->getAllReflections($profileId);
    }

    public function saveReflection($profileId,$reflectionDate,$reflectionTitle,$reflectionContent)
    {
        // businesss logic validation
        if (empty($reflectionTitle)) {
            $_err['reflectionTitle'] = 'Required';
        } elseif (strlen($reflectionTitle) > 255) {
            $_err['reflectionTitle'] = 'Maximum length 255 characters';
        }
        
        if (empty($reflectionContent)) {
            $_err['reflectionContent'] = 'Required';
        }
        
        if (!empty($_err)) {
            $_SESSION['reflectionErrors'] = $_err;
            $_SESSION['old'] = [
                'reflectionTitle' => $reflectionTitle,
                'reflectionContent' => $reflectionContent,
            ];
            header("Location: /reflection/create");
            exit;
        }
        
        return $this->reflectionDAO->saveReflection($profileId, $reflectionDate, $reflectionTitle, $reflectionContent);
    }

    public function getReflectionById($reflectionId)
    {
        $reflection = $this->reflectionDAO->getReflectionById($reflectionId);

        // If the reflection does not exist, return null
        if (!$reflection) {
            return null; 
        }
        // Return the reflection if it exists
        return $reflection;
    }


    public function editSaveReflection($reflectionId,$reflectionTitle,$reflectionContent)
    {
        // businesss logic validation
        if (empty($reflectionTitle)) {
            $_err['reflectionTitle'] = 'Required';
        } elseif (strlen($reflectionTitle) > 255) {
            $_err['reflectionTitle'] = 'Maximum length 255 characters';
        }
        
        if (empty($reflectionContent)) {
            $_err['reflectionContent'] = 'Required';
        }
        
        if (!empty($_err)) {
            $_SESSION['reflectionErrors'] = $_err;
            $_SESSION['old'] = [
                'reflectionTitle' => $reflectionTitle,
                'reflectionContent' => $reflectionContent,
            ];
            header("Location: /reflection/edit");
            exit;
        }
        
        return $this->reflectionDAO->editSaveReflection($reflectionId,$reflectionTitle, $reflectionContent);
    }

    public function deleteReflectionById($reflectionId) 
    {
        return $this->reflectionDAO->deleteReflectionById($reflectionId);
    }

}
