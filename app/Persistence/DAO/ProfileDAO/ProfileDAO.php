<?php

namespace Persistence\DAO\ProfileDAO;

use PDO;
use PDOException;
use Database;


class ProfileDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
