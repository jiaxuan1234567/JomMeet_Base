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


    // public function getProfileDetails($profileId)
    // {
    //     return $this->profileDAO->getProfileDetails($profileId);
    // }

    // // Fetch a gathering by its ID
    // public function getProfileById(int $id): array
    // {
    //     return $this->profileDAO->getProfileById($id);
    // }

    public function submitProfile(
        string $nickname,
        string $aboutMe,
        string $mbti,
        array  $hobbies,
        array  $preferences
    ): int {
        // 1) Business‐logic validation
        $_err = [];

        // Nickname: required, max 20 chars
        if (trim($nickname) === '') {
            $_err['nickname'] = 'Nickname is required';
        } elseif (strlen($nickname) > 20) {
            $_err['nickname'] = 'Maximum length is 20 characters';
        }

        // About Me: required, max 255 chars
        if (trim($aboutMe) === '') {
            $_err['about_me'] = 'About Me is required';
        } elseif (strlen($aboutMe) > 255) {
            $_err['about_me'] = 'Maximum length is 255 characters';
        }

        // MBTI: required, must be one of the defined types
        $validMbti = [
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
        if (!in_array($mbti, $validMbti, true)) {
            $_err['mbti'] = 'Please select a valid MBTI';
        }

        // Hobbies: optional—but if provided, each must be nonempty
        foreach ($hobbies as $i => $h) {
            if (trim($h) === '') {
                $_err["hobbies[$i]"] = 'Invalid hobby selection';
            }
        }

        // Preferences: optional—but if provided, each must be nonempty
        foreach ($preferences as $i => $p) {
            if (trim($p) === '') {
                $_err["preferences[$i]"] = 'Invalid preference selection';
            }
        }

        // 2) If any errors, store them and the old input, then redirect back
        if (!empty($_err)) {
            $_SESSION['profileErrors'] = $_err;
            $_SESSION['old'] = [
                'nickname'    => $nickname,
                'about_me'    => $aboutMe,
                'mbti'        => $mbti,
                'hobbies'     => $hobbies,
                'preferences' => $preferences,
            ];
            header('Location: /profile/create');
            exit;
        }

        // 3) No errors → hand off to DAO to insert into the database
        $data = [
            'nickname'    => trim($nickname),
            'about_me'    => trim($aboutMe),
            'mbti'        => $mbti,
            'hobbies'     => $hobbies,
            'preferences' => $preferences,
            'phone'       => $_SESSION['profile']['phone']    ?? null,
            'password'    => $_SESSION['profile']['password'] ?? null,
        ];
        return $this->profileDAO->submitProfile($data);
    }
}
