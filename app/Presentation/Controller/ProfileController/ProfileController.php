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
        // $this->profileModel = new ProfileModel();
    }

    public function validateLogin()
    {
        // try {
        //     error_log("[ProfileController] Starting validateLogin");
        //     error_log("[ProfileController] POST data: " . print_r($_POST, true));

        //     $loginSuccess = $this->profileModel->validateLogin();
        //     error_log("[ProfileController] Login result: " . ($loginSuccess ? "Success" : "Failed"));

        //     if ($loginSuccess) {
        //         error_log("[ProfileController] Redirecting after successful login");
        //         header("Location: /");
        //         error_log("Login userid is $_SESSION[profile_id]");
        //         exit;
        //     } else {
        //         error_log("[ProfileController] Login failed, showing error page");
        //         header("Location: /login");
        //     }
        // } catch (Exception $e) {
        //     error_log("[ProfileController] Error in validateLogin: " . $e->getMessage());
        //     error_log("[ProfileController] Stack trace: " . $e->getTraceAsString());
        // }

        //session_start();

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
            header("Location: /profile/create");
            exit;
        } else {
            error_log("login failed");
            header("Location: /login");
            exit;
        }
    }

    // public function viewProfile()
    // {
    //     // 1) Ensure the user is logged in
    //     $userId = (int) ($_SESSION['profile_id'] ?? 0);
    //     // if ($userId <= 0) {
    //     //     header('Location: /login');
    //     //     exit;
    //     // }

    //     // 2) Fetch the latest profile from the DB
    //     $profile = $this->profileModel->getUserByProfileID($userId);

    //     // 3) Include the view which uses $profile
    //     include $this->fileHelper->getFilePath('Profile');
    // }

    public function createProfile()
    {
        $types = $this->profileModel->getAllMbti();
        $hobbyOptions = $this->profileModel->getAllHobby();
        $preferenceOptions = $this->profileModel->getAllPreferences();

        include $this->fileHelper->getFilePath('CreateProfile');
    }

    public function submitProfile()
    {
        $nickname   = trim($_POST['nickname']);
        $aboutMe    = trim($_POST['aboutme']);
        $mbti       = $_POST['mbti'];
        $hobbies     = $_POST['hobbies']
            ? explode(',', $_POST['hobbies'])
            : [];
        $preferences = $_POST['preferences']
            ? explode(',', $_POST['preferences'])
            : [];

        $this->profileModel->submitProfile($nickname, $aboutMe, $mbti, $hobbies, $preferences);

        header('Location: /profile');
        exit;
    }

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

        // if (!$success) {
        //     header('Location: /profile/edit');
        //     exit;
        // }

        $updatedProfile = $this->profileModel->getUserByProfileID($userId);
        $_SESSION['profile']    = $updatedProfile;
        $_SESSION['profile_id'] = $userId;

        header('Location: /profile');
        exit;
    }

    // public function validateProfile(): void
    // {
    //     header('Content-Type: application/json');
    //     // grab field name + value
    //     $field = key($_POST);
    //     $value = $_POST[$field] ?? null;

    //     $result = $this->profileModel->validateProfile($field, $value);
    //     echo json_encode($result);
    // }

    // public function validateProfile(): void
    // {
    //     header('Content-Type: application/json');

    //     // Determine which field was sent
    //     $fields = ['nickname','aboutme','mbti','hobbies','preferences'];
    //     $field  = null;
    //     $value  = null;
    //     foreach ($fields as $f) {
    //         if (isset($_POST[$f])) {
    //             $field = $f;
    //             $value = $_POST[$f];
    //             break;
    //         }
    //     }

    //     // If nothing matched, bail out
    //     if ($field === null) {
    //         echo json_encode([
    //             'success' => false,
    //             'field'   => '',
    //             'message' => 'No data provided.'
    //         ]);
    //         return;
    //     }

    //     // Delegate to model
    //     $result = $this->profileModel->validateProfile($field, $value);
    //     echo json_encode($result);
    // }

    public function validateProfileData()
    {
        header('Content-Type: application/json');

        $nickname    = trim($_POST['nickname']    ?? '');
        $aboutMe     = trim($_POST['aboutme']     ?? '');
        $mbti        = $_POST['mbti']             ?? '';
        $hobbies     = !empty($_POST['hobbies'])
            ? explode(',', $_POST['hobbies'])
            : [];
        $preferences = !empty($_POST['preferences'])
            ? explode(',', $_POST['preferences'])
            : [];

        $result = $this->profileModel->validateProfileData(
            $nickname,
            $aboutMe,
            $mbti,
            $hobbies,
            $preferences
        );

        echo json_encode($result);
        exit;
    }
}
