<?php

namespace Presentation\Controller\ProfileController;

use BusinessLogic\Model\ProfileModel\ProfileModel;
use FileHelper;
use Exception;

class ProfileController
{
    private $profileModel;
    private $fileHelper;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
        $this->fileHelper = new FileHelper('profile');
    }

    public function validateLogin()
    {
        // Get submitted credentials
        $phoneNumber = $_POST['phoneNumber'] ?? '';
        $password = $_POST['password'] ?? '';

        // Basic input validation
        if (empty($phoneNumber) || empty($password)) {
            $_SESSION['login_error'] = 'phoneNumber and password are required.';
            header('Location: /login');
            exit;
        }

        error_log("p and p :" . $phoneNumber . $phoneNumber);

        $status = $this->profileModel->validateLogin($phoneNumber, $password);

        if ($status == "valid") {
            error_log("login success");
            header("Location: /");
            exit;
        } else if ($status == "newAcc") {
            $_SESSION['new_profile'] = [
                'phone'    => $phoneNumber,
                'password' => $password,
            ];
            header("Location: /profile/create");
            exit;
        } else {
            error_log("login failed");
            header("Location: /login");
            exit;
        }
    }

    // ------------------------------------------ Create Profile ------------------------------------------//
    public function createProfile()
    {
        // Only allow here if new_profile exists
        if (empty($_SESSION['new_profile'])) {
            header('Location: /login');
            exit;
        }
        $types = $this->profileModel->getAllMbti();
        $hobbyOptions = $this->profileModel->getAllHobby();
        $preferenceOptions = $this->profileModel->getAllPreferences();

        include $this->fileHelper->getFilePath('CreateProfile');
    }

    public function submitProfile()
    {
        if (empty($_SESSION['new_profile'])) {
            throw new Exception('Session expired — please log in again.');
        }

        $_SESSION['profile'] = [
            'phone'       => $_SESSION['new_profile']['phone'],
            'password'    => $_SESSION['new_profile']['password'],
        ];
        $nickname   = trim($_POST['nickname']);
        $aboutMe    = trim($_POST['aboutme']);
        $mbti       = $_POST['mbti'];
        $hobbies     = $_POST['hobbies']
            ? explode(',', $_POST['hobbies'])
            : [];
        $preferences = $_POST['preferences']
            ? explode(',', $_POST['preferences'])
            : [];

        $newId = $this->profileModel->submitProfile(
            $nickname,
            $aboutMe,
            $mbti,
            $hobbies,
            $preferences
        );

        $_SESSION['flash_message'] = "Your profile has been created successfully.";
        $_SESSION['flash_type'] = "success";

        if ($newId !== false) {
            $_SESSION['profile_id'] = $newId;
            header('Location: /profile');
            exit;
        }

        throw new Exception('Failed to create profile.');
    }

    // ------------------------------------------ Edit Profile ------------------------------------------//
    public function editProfile()
    {
        $types = $this->profileModel->getAllMbti();
        $hobbyOptions = $this->profileModel->getAllHobby();
        $preferenceOptions = $this->profileModel->getAllPreferences();

        include $this->fileHelper->getFilePath('EditProfile');
    }

    public function saveProfile()
    {
        $userId = (int)$_SESSION['profile_id'] ?? null;
        $nickname   = trim($_POST['nickname']);
        $aboutMe    = trim($_POST['aboutme']);
        $mbti       = $_POST['mbti'];
        $hobbies     = $_POST['hobbies']
            ? explode(',', $_POST['hobbies'])
            : [];
        $preferences = $_POST['preferences']
            ? explode(',', $_POST['preferences'])
            : [];

        $this->profileModel->saveProfile($userId, $nickname, $aboutMe, $mbti, $hobbies, $preferences);

        $updatedProfile = $this->profileModel->getUserByProfileID($userId);
        $_SESSION['profile']    = $updatedProfile;
        $_SESSION['profile_id'] = $userId;

        $_SESSION['flash_message'] = "Your profile has been updated successfully.";
        $_SESSION['flash_type'] = "success";

        header('Location: /profile');
        exit;
    }

    // ------------------------------------------ Validate Profile ------------------------------------------//
    public function validateProfileData()
    {
        header('Content-Type: application/json');

        $nickname    = trim($_POST['nickname']    ?? '');
        $aboutMe     = trim($_POST['aboutme']     ?? '');
        $mbti        = $_POST['mbti']             ?? '';
        $hobbies     = !empty($_POST['hobbies']) ? explode(',', $_POST['hobbies']) : [];
        $preferences = !empty($_POST['preferences']) ? explode(',', $_POST['preferences']) : [];

        $response = $this->profileModel->validateProfileData(
            $nickname,
            $aboutMe,
            $mbti,
            $hobbies,
            $preferences
        );
        echo json_encode($response);
        exit;
    }
}
