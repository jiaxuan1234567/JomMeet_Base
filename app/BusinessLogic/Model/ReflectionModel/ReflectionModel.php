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
        if ($reflectionTitle == '') {
            $_err['reflectionTitle'] = 'Required';
        }
        else if (strlen($reflectionTitle) > 255) {
            $_err['reflectionTitle'] = 'Maximum length 255';
        }
    
        if ($reflectionContent == '') {
            $_err['reflectionContent'] = 'Required';
        }

        if (!$_err) {
            return $this->reflectionDAO->saveReflection($profileId,$reflectionDate,$reflectionTitle,$reflectionContent);
        } else {
            header("Location: /reflection/create");
        }
    }


}
