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

    public function validateLogin($phoneNumber, $password)
    {
        // $phoneNumber = $_POST['phoneNumber'];
        // $password = $_POST['password'];

        $profile = $this->profileDAO->getUserByPhoneNumber($phoneNumber);

        //if (!$profile || !password_verify($password, $profile['password'])) {
        if (!$profile || !($password == $profile['password'])) {
            //$_SESSION['login_error'] = 'Invalid username or password.';
            return false;
        } else {
            $_SESSION['profile'] = $profile;
            $_SESSION['profile_id'] = $profile['profileID'];
            return true;
        }

        // $profiles = $this->getAllProfiles();
        // // Validate phone number and password
        // if (empty($phoneNumber) || empty($password)) {
        //     return false; // or handle error
        // }

        // // Here you would typically check the credentials against a database
        // foreach ($profiles as $p) {
        //     error_log("Checking profile: " . print_r($p, true));
        //     if ($p['phone'] == $phoneNumber && $p['password'] == $password) {
        //         $_SESSION['profile_id'] = $p['profileID'];
        //         $_SESSION['name'] = $p['nickname'];
        //         $_SESSION['phone'] = $p['phone'];
        //         $_SESSION['password'] = $p['password'];
        //         return true;
        //     }
        // }
        // return false;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // Optional: regenerate session ID for safety
        session_start();
        session_regenerate_id(true);

        return true;
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
