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

    public function getReflectionID($profileId)
    {
        return $this->reflectionDAO->getReflectionID($profileId);
    }

    public function saveReflections($profileId)
    {
        return $this->reflectionDAO->saveReflections($profileId);
    }


}
