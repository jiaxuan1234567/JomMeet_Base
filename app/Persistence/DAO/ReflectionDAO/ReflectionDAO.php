<?php

namespace Persistence\DAO\ReflectionDAO;

use PDO;
use PDOException;
use Database;


class ReflectionDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllReflections() {}
}
