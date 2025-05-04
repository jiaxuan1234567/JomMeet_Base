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
        } else if (!($password == $profile['password'])){
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

        //Validation
        $nickLen = mb_strlen($nickname);
        if ($nickLen === 0) {
            $errors['nickname'] = 'Nickname is required.';
        } elseif ($nickLen > 20) {
            $errors['nickname'] = 'Nickname must not exceed 20 characters.';
        }

        $aboutLen = mb_strlen($aboutMe);
        if ($aboutLen === 0) {
            $errors['aboutme'] = 'About Me is required.';
        } elseif ($aboutLen > 255) {
            $errors['aboutme'] = 'About Me must not exceed 255 characters.';
        }

        $validMbti = $this->getAllMbti();
        if (trim($mbti) === '') {
            $errors['mbti'] = 'Please select your MBTI.';
        } elseif (!in_array($mbti, $validMbti, true)) {
            $errors['mbti'] = 'Invalid MBTI selection.';
        }

        if (empty($hobbies)) {
            $errors['hobbies'] = 'Select at least one hobby.';
        }

        if (empty($preferences)) {
            $errors['preferences'] = 'Select at least one preference.';
        }

        // // 2) If any errors, store them and the old input, then redirect back
        // if (!empty($errors)) {
        //     $_SESSION['profileErrors'] = $errors;
        //     $_SESSION['old'] = [
        //         'nickname'    => $nickname,
        //         'aboutme'    => $aboutMe,
        //         'mbti'        => $mbti,
        //         'hobbies'     => $hobbies,
        //         'preferences' => $preferences,
        //     ];
        //     header('Location: /profile/create');
        //     exit;
        // }

        // 3) No errors → hand off to DAO to insert into the database
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

        //Validation
        $nickLen = mb_strlen($nickname);
        if ($nickLen === 0) {
            $errors['nickname'] = 'Nickname is required.';
        } elseif ($nickLen > 20) {
            $errors['nickname'] = 'Nickname must not exceed 20 characters.';
        }

        $aboutLen = mb_strlen($aboutMe);
        if ($aboutLen === 0) {
            $errors['aboutme'] = 'About Me is required.';
        } elseif ($aboutLen > 255) {
            $errors['aboutme'] = 'About Me must not exceed 255 characters.';
        }

        $validMbti = $this->getAllMbti();
        if (trim($mbti) === '') {
            $errors['mbti'] = 'Please select your MBTI.';
        } elseif (!in_array($mbti, $validMbti, true)) {
            $errors['mbti'] = 'Invalid MBTI selection.';
        }

        if (empty($hobbies)) {
            $errors['hobbies'] = 'Select at least one hobby.';
        }

        if (empty($preferences)) {
            $errors['preferences'] = 'Select at least one preference.';
        }

        // 3) If validation failed, stash errors + old input and bail
        if (!empty($errors)) {
            $_SESSION['profileErrors'] = $errors;
            $_SESSION['oldProfile']    = [
                'nickname'    => $nickname,
                'aboutme'    => $aboutMe,
                'mbti'        => $mbti,
                'hobbies'     => $hobbies,
                'preferences' => $preferences,
            ];
            header("Location: /profile/edit");
            exit;
        }

        // 4) All good → update in DB
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
        // Delegate to the DAO
        $profile = $this->profileDAO->getUserByProfileID($userId);

        // If the DAO returned false or empty, ensure we return an empty array
        if (!is_array($profile) || empty($profile)) {
            return [];
        }

        return $profile;
    }

    // public function validateProfile(string $field, $value): array
    // {
    //     switch ($field) {
    //         case 'nickname':
    //             $len = mb_strlen(trim((string)$value));
    //             if ($len === 0) {
    //                 return ['success'=>false, 'field'=>'nickname', 'message'=>'Nickname cannot be empty.'];
    //             }
    //             if ($len > 20) {
    //                 return ['success'=>false, 'field'=>'nickname', 'message'=>'Nickname must be ≤20 characters.'];
    //             }
    //             break;

    //         case 'aboutme':
    //             $len = mb_strlen(trim((string)$value));
    //             if ($len === 0) {
    //                 return ['success'=>false, 'field'=>'aboutme', 'message'=>'About Me cannot be empty.'];
    //             }
    //             if ($len > 255) {
    //                 return ['success'=>false, 'field'=>'aboutme', 'message'=>'About Me must be ≤255 characters.'];
    //             }
    //             break;

    //         case 'mbti':
    //             $valid = $this->getAllMbti();
    //             if (trim((string)$value) === '' || !in_array($value, $valid, true)) {
    //                 return ['success'=>false, 'field'=>'mbti', 'message'=>'Please select a valid MBTI.'];
    //             }
    //             break;

    //         case 'hobbies':
    //             // value arrives as CSV string
    //             $arr = array_filter(array_map('trim', explode(',', (string)$value)));
    //             if (count($arr) === 0) {
    //                 return ['success'=>false, 'field'=>'hobbies', 'message'=>'Select at least one hobby.'];
    //             }
    //             break;

    //         case 'preferences':
    //             $arr = array_filter(array_map('trim', explode(',', (string)$value)));
    //             if (count($arr) === 0) {
    //                 return ['success'=>false, 'field'=>'preferences', 'message'=>'Select at least one preference.'];
    //             }
    //             break;

    //         default:
    //             return ['success'=>false, 'field'=>$field, 'message'=>'Unknown field.'];
    //     }

    //     return ['success'=>true];
    // }

    // public function validateProfile(string $field, $value): array
    // {
    //     switch ($field) {
    //         case 'nickname':
    //             $len = mb_strlen(trim((string)$value));
    //             if ($len === 0) {
    //                 return ['success'=>false,'field'=>'nickname','message'=>'Nickname cannot be empty.'];
    //             }
    //             if ($len > 20) {
    //                 return ['success'=>false,'field'=>'nickname','message'=>'Nickname must be ≤20 chars.'];
    //             }
    //             break;

    //         case 'aboutme':
    //             $len = mb_strlen(trim((string)$value));
    //             if ($len === 0) {
    //                 return ['success'=>false,'field'=>'aboutme','message'=>'About Me cannot be empty.'];
    //             }
    //             if ($len > 255) {
    //                 return ['success'=>false,'field'=>'aboutme','message'=>'About Me must be ≤255 chars.'];
    //             }
    //             break;

    //         case 'mbti':
    //             $valid = $this->getAllMbti();
    //             if (!in_array((string)$value, $valid, true)) {
    //                 return ['success'=>false,'field'=>'mbti','message'=>'Please select a valid MBTI.'];
    //             }
    //             break;

    //         case 'hobbies':
    //             // value is comma‐separated
    //             $arr = array_filter(array_map('trim', explode(',', (string)$value)));
    //             if (count($arr) === 0) {
    //                 return ['success'=>false,'field'=>'hobbies','message'=>'Select at least one hobby.'];
    //             }
    //             break;

    //         case 'preferences':
    //             $arr = array_filter(array_map('trim', explode(',', (string)$value)));
    //             if (count($arr) === 0) {
    //                 return ['success'=>false,'field'=>'preferences','message'=>'Select at least one preference.'];
    //             }
    //             break;

    //         default:
    //             return ['success'=>false,'field'=>$field,'message'=>'Unknown field.'];
    //     }

    //     return ['success'=>true,'field'=>$field,'message'=>''];
    // }

    public function validateProfileData(
        string $nickname,
        string $aboutMe,
        string $mbti,
        array  $hobbies,
        array  $preferences
    ): array {
        $errors = [];

        // Nickname must be 1–20 chars
        $nickLen = mb_strlen(trim($nickname));
        if ($nickLen === 0) {
            $errors['nickname'] = 'Nickname is required.';
        } elseif ($nickLen > 20) {
            $errors['nickname'] = 'Must not exceed 20 characters.';
        }

        // About Me must be 1–255 chars
        $aboutLen = mb_strlen(trim($aboutMe));
        if ($aboutLen === 0) {
            $errors['aboutme'] = 'About Me is required.';
        } elseif ($aboutLen > 255) {
            $errors['aboutme'] = 'Must not exceed 255 characters.';
        }

        // MBTI must be one of the defined types
        if (!in_array($mbti, $this->getAllMbti(), true)) {
            $errors['mbti'] = 'Please select a valid MBTI.';
        }

        // At least one hobby & one preference
        if (count($hobbies) === 0) {
            $errors['hobbies'] = 'Select at least one hobby.';
        }
        if (count($preferences) === 0) {
            $errors['preferences'] = 'Select at least one preference.';
        }

        return empty($errors)
            ? ['success' => true,  'errors' => []]
            : ['success' => false, 'errors' => $errors];
    }
}
