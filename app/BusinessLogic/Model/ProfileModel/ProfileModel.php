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

    public function getAllProfiles()
    {
        try {
            $profiles = $this->profileDAO->getAllProfiles();
            if ($profiles === false) {
                throw new Exception("Failed to fetch profiles");
            }
            return $profiles;
        } catch (Exception $e) {
            error_log("Error in getAllProfiles: " . $e->getMessage());
            return [];
        }
    }

    public function validateLogin()
    {
        $phoneNumber = $_POST['phoneNumber'];
        $password = $_POST['password'];

        $profiles = $this->getAllProfiles();
        // Validate phone number and password
        if (empty($phoneNumber) || empty($password)) {
            return false; // or handle error
        }

        // Here you would typically check the credentials against a database
        foreach ($profiles as $p) {
            if ($p['phone'] == $phoneNumber && $p['password'] == $password) {
                $_SESSION['profile_id'] = $p['profileID'];
                $_SESSION['phoneNumber'] = $p['phoneNumber'];
                $_SESSION['password'] = $p['password'];
                return true;
            } else {
                return false;
            }
        }
    }
}
