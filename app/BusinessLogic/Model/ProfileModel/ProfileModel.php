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

    // public function getPreference()
    // {
    //     return ['FOOD', 'CHILL', 'STUDY', 'NATURAL', 'SHOPPING', 'WORKOUT', 'ENTERTAINMENT', 'MUSIC', 'MOVIE'];
    // }

    public function getAllPreferences()
    {
        return ['Food', 'Chill', 'Study', 'Natural', 'Shopping', 'Workout', 'Entertainment', 'Music', 'Movie'];
    }

    public function validateLogin($phoneNumber, $password)
    {
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
    ) {
        // Nickname must be 1–20 chars
        $nickLen = mb_strlen(trim($nickname));
        if ($nickLen === 0) {
            return ['success' => false, 'field' => 'nickname', 'message' => 'Nickname is required.'];
        } elseif ($nickLen > 20) {
            return ['success' => false, 'field' => 'nickname', 'message' => 'Nickname must not exceed 20 characters.'];
        }

        // About Me must be 1–255 chars
        $aboutLen = mb_strlen(trim($aboutMe));
        if ($aboutLen === 0) {
            return ['success' => false, 'field' => 'aboutme', 'message' => 'About Me is required.'];
        } elseif ($aboutLen > 255) {
            return ['success' => false, 'field' => 'aboutme', 'message' => 'About Me must not exceed 255 characters.'];
        }

        // MBTI must be one of the defined types
        if (!in_array($mbti, $this->getAllMbti(), true)) {
            return ['success' => false, 'field' => 'mbti', 'message' => 'Please select a valid MBTI.'];
        }

        // At least one hobby & one preference
        if (count($hobbies) === 0) {
            return ['success' => false, 'field' => 'hobbies', 'message' => 'Select at least one hobby.'];
        }
        if (count($preferences) === 0) {
            return ['success' => false, 'field' => 'preferences', 'message' => 'Select at least one preference.'];
        }

        return ['success' => true];
    }
}
