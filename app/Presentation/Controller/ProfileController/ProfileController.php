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

        if ($status) {
            error_log("login success");
            header("Location: /");
        } else {
            error_log("login failed");
            header("Location: /login");
        }
    }

    // public function viewProfile($id)
    // {
    //     $profile = $this->profileModel->getProfileById($id);
    //     include $this->fileHelper->getFilePath('ProfileDetail');
    // }

    public function createProfile()
    {
        include $this->fileHelper->getFilePath('CreateProfile');
    }

    public function submitProfile()
    {
        $nickname   = trim($_POST['nickname']);
        $aboutMe    = trim($_POST['about_me']);
        $mbti       = $_POST['mbti'];
        $hobbies    = $_POST['hobbies'] ?? [];
        $preferences = $_POST['preferences'] ?? [];

        $this->profileModel->submitProfile($nickname, $aboutMe, $mbti, $hobbies, $preferences);

        header('Location: /profile');
    }

    public function editProfile()
    {
        $types = $this->profileModel->getAllMbti();
        $hobbyOptions = $this->profileModel->getAllHobby();
        include $this->fileHelper->getFilePath('EditProfile');
    }

    public function saveProfile() {}
}
