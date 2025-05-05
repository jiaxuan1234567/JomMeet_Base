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

    // public function getAllProfiles()
    // {
    //     try {
    //         $profiles = $this->profileDAO->getAllProfiles();
    //         if ($profiles === false) {
    //             throw new Exception("Failed to fetch profiles");
    //         }
    //         return $profiles;
    //     } catch (Exception $e) {
    //         error_log("Error in getAllProfiles: " . $e->getMessage());
    //         return [];
    //     }
    // }

    public function getAllMbti()
    {
        return [
            'INTJ',
            'INTP',
            'ENTJ',
            'ENTP',
            'INFJ',
            'INFP',
            'ENFJ',
            'ENFP',
            'ISTJ',
            'ISFJ',
            'ESTJ',
            'ESFJ',
            'ISTP',
            'ISFP',
            'ESTP',
            'ESFP'
        ];
    }

    public function getAllHobby()
    {
        return [
            'Basketball',
            'Badminton',
            'Hiking',
            'Singing',
            'Photography',
            'Reading',
            'Jogging',
            'Camping',
            'Traveling',
            'Swimming',
            'Yoga',
            'Meditation',
            'Drawing',
            'Painting',
            'Squash',
            'Gym'
        ];
    }

    public function getAllPreferences()
    {
        return [
            'Entertainment',
            'Sports',
            'Dining',
            'Nature',
            'Hangout',
            'Coffee',
            'Picnic',
            'Chill'
        ];
    }

    public function validateLogin($phoneNumber, $password)
    {
        // $phoneNumber = $_POST['phoneNumber'];
        // $password = $_POST['password'];

        $profile = $this->profileDAO->getUserByPhoneNumber($phoneNumber);

        if (!$profile) {
            //$_SESSION['login_error'] = 'Invalid username or password.';
            return "newAcc";
        } else if (!($password == $profile['password'])) {
            return "invalid";
        } else {
            $_SESSION['profile'] = $profile;
            $_SESSION['profile_id'] = $profile['profileID'];
            return "valid";
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


    // public function getProfileDetails($profileId)
    // {
    //     return $this->profileDAO->getProfileDetails($profileId);
    // }

    // // Fetch a gathering by its ID
    // public function getProfileById(int $id): array
    // {
    //     return $this->profileDAO->getProfileById($id);
    // }


    // public function fetchProfile(string $phone): array|false
    // {
    //     return $this->profileDAO->getUserByPhoneNumber($phone);
    // }

    public function submitProfile(
        string $nickname,
        string $aboutMe,
        string $mbti,
        array  $hobbies,
        array  $preferences
    ) {
        $data = [
            'nickname'    => trim($nickname),
            'aboutme'    => trim($aboutMe),
            'mbti'        => $mbti,
            'hobbies'     => $hobbies,
            'preferences' => $preferences,
            'phone'       => $_SESSION['profile']['phone']    ?? null,
            'password'    => $_SESSION['profile']['password'] ?? null,
        ];
        return $this->profileDAO->submitProfile($data);
    }



    public function saveProfile(
        int    $userId,
        string $nickname,
        string $aboutMe,
        string $mbti,
        array  $hobbies,
        array  $preferences
    ) {
        $data = [
            'nickname'    => $nickname,
            'aboutme'     => $aboutMe,
            'mbti'        => $mbti,
            'hobbies'     => $hobbies,
            'preferences' => $preferences,
        ];

        return $this->profileDAO->saveProfile($userId, $data);
    }

    public function getUserByProfileID(int $userId): array
    {
        $profile = $this->profileDAO->getUserByProfileID($userId);

        if (!is_array($profile) || empty($profile)) {
            return [];
        }

        return $profile;
    }

    public function validateProfileData(
        string $nickname,
        string $aboutMe,
        string $mbti,
        array  $hobbies,
        array  $preferences
    ): array {
        $errors = [];

        // Nickname
        $nickLen = mb_strlen(trim($nickname));
        if ($nickLen === 0) {
            $errors['nickname'] = 'Nickname is required.';
        } elseif ($nickLen > 20) {
            $errors['nickname'] = 'Nickname must not exceed 20 characters.';
        }

        // About Me
        $aboutLen = mb_strlen(trim($aboutMe));
        if ($aboutLen === 0) {
            $errors['aboutme'] = 'About Me is required.';
        } elseif ($aboutLen > 255) {
            $errors['aboutme'] = 'About Me must not exceed 255 characters.';
        }

        // MBTI
        if (!in_array($mbti, $this->getAllMbti(), true)) {
            $errors['mbti'] = 'Please select a valid MBTI.';
        }

        // Hobbies
        if (count($hobbies) === 0) {
            $errors['hobbies'] = 'Select at least one hobby.';
        }

        // Preferences
        if (count($preferences) === 0) {
            $errors['preferences'] = 'Select at least one preference.';
        }

        return [
            'success' => empty($errors),
            'errors'  => $errors
        ];
    }
}