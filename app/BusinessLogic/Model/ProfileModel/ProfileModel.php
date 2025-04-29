<?php

namespace BusinessLogic\Model\ProfileModel;

use Persistence\DAO\ProfileDAO\ProfileDAO;
use Exception;
use FileHelper;

class ProfileModel
{
    private $profileDAO;

    public function __construct()
    {
        $this->profileDAO = new ProfileDAO();
    }

    public function getProfileDetails($profileId)
    {
        return $this->profileDAO->getProfileDetails($profileId);
    }

    // // Fetch a gathering by its ID
    // public function getProfileById(int $id): array
    // {
    //     return $this->profileDAO->getProfileById($id);
    // }
}
