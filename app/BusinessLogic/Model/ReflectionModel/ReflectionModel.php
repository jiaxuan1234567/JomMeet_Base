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
}
