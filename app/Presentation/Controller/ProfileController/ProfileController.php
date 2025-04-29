<?php

namespace Presentation\Controller\ProfileController;

use BusinessLogic\Model\ProfileModel\ProfileModel;
use FileHelper;
use Exception;

class ProfileController
{
    private $fileHelper;
    private $profileModel;

    public function __construct()
    {
        $this->fileHelper = new FileHelper('profile');
        $this->profileModel = new ProfileModel();
    }

    public function validateLogin()
    {
        try {
            error_log("[ProfileController] Starting validateLogin");
            error_log("[ProfileController] POST data: " . print_r($_POST, true));

            $loginSuccess = $this->profileModel->validateLogin();
            error_log("[ProfileController] Login result: " . ($loginSuccess ? "Success" : "Failed"));

            if ($loginSuccess) {
                error_log("[ProfileController] Redirecting after successful login");
                header("Location: /");
                error_log("Login userid is $_SESSION[profile_id]");
                exit;
            } else {
                error_log("[ProfileController] Login failed, showing error page");
                header("Location: /login");
            }
        } catch (Exception $e) {
            error_log("[ProfileController] Error in validateLogin: " . $e->getMessage());
            error_log("[ProfileController] Stack trace: " . $e->getTraceAsString());
        }
    }
}
