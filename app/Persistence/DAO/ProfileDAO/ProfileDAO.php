<?php

namespace Persistence\DAO\ProfileDAO;

use PDO;
use PDOException;
use Database;


class ProfileDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // public function getAllProfiles()
    // {
    //     try {
    //         $stmt = $this->db->prepare("SELECT * FROM `profile`");
    //         $stmt->execute();
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         error_log("Error in getAllProfiles: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function getUserByPhoneNumber($phone)
    {
        try {
            $sql = "SELECT p.*, ph.hobby, pp.preference 
            FROM `profile` p
            LEFT JOIN profile_hobby ph ON p.profileID = ph.profileID
            LEFT JOIN profile_preference pp ON p.profileID = pp.profileID
            WHERE p.phone = :phone";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':phone' => $phone]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Base profile info from the first row
            $base = $rows[0];
            $profile = [
                'profileID'     => (int)$base['profileID'],
                'nickname'      => $base['nickname'],
                'aboutme'       => $base['aboutme'],
                'mbti'          => $base['mbti'],
                'profileStatus' => $base['profileStatus'],
                'phone'         => $base['phone'],
                'password'      => $base['password'],
                'hobbies'       => [],
                'preferences'   => [],
            ];

            // Aggregate hobbies & preferences without duplicates
            foreach ($rows as $r) {
                if (!empty($r['hobby']) && !in_array($r['hobby'], $profile['hobbies'], true)) {
                    $profile['hobbies'][] = $r['hobby'];
                }
                if (!empty($r['preference']) && !in_array($r['preference'], $profile['preferences'], true)) {
                    $profile['preferences'][] = $r['preference'];
                }
            }

            return $profile;
        } catch (PDOException $e) {
            error_log("Error in getAllProfiles: " . $e->getMessage());
            return false;
        }
    }

    // public function getProfileDetails($profileId)
    // {
    //     try {
    //         // Basic profile fields
    //         $stmt = $this->db->prepare(
    //             "SELECT phone, nickname, mbti, aboutme, hobbies, preference FROM `profile` WHERE profileID = $profileId"
    //         );

    //         $stmt->execute();

    //         $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //         if (!$row) {
    //             return [];
    //         }

    //         // Split comma-lists into arrays
    //         $hobbies     = array_filter(array_map('trim', explode(',', $row['hobbies'] ?? '')));
    //         $preferences = array_filter(array_map('trim', explode(',', $row['preference'] ?? '')));

    //         return [
    //             'phone'         => $row['phone'],
    //             'nickname'      => $row['nickname'],
    //             'mbti'          => $row['mbti'],
    //             'aboutme'       => $row['aboutme'],
    //             'hobbies'       => $hobbies,
    //             'preferences'   => $preferences,
    //         ];
    //     } catch (PDOException $e) {
    //         error_log("Error in getProfileID: " . $e->getMessage());
    //         return [];
    //     }
    // }

    public function submitProfile(array $data)
    {
        try {
            // 1) insert into profile table
            $stmt = $this->db->prepare(
                'INSERT INTO profile
                   (nickname, aboutme, mbti, profileStatus, phone, `password`)
                 VALUES
                   (?, ?, ?, ?, ?, ?)'
            );
            // you can adjust profileStatus, phone, password as needed:
            $status   = 'active';
            $phone    = $data['phone']    ?? null;
            $password = $data['password'] ?? null;

            $stmt->execute([
                $data['nickname'],
                $data['about_me'],
                $data['mbti'],
                $status,
                $phone,
                $password
            ]);

            // 2) get the new profileID
            $profileId = (int)$this->db->lastInsertId();

            // 3) insert into profile_hobby
            if (!empty($data['hobbies'])) {
                $hobbyStmt = $this->db->prepare(
                    'INSERT INTO profile_hobby (hobby, profileID)
                     VALUES (?, ?)'
                );
                foreach ($data['hobbies'] as $hobby) {
                    $hobbyStmt->execute([$hobby, $profileId]);
                }
            }

            // 4) insert into profile_preference
            if (!empty($data['preferences'])) {
                $prefStmt = $this->db->prepare(
                    'INSERT INTO profile_preference (preference, profileID)
                     VALUES (?, ?)'
                );
                foreach ($data['preferences'] as $pref) {
                    $prefStmt->execute([$pref, $profileId]);
                }
            }

            return $profileId;

        } catch (PDOException $e) {
            error_log("Error in insertProfile: " . $e->getMessage());
            return false;
        }
    }

}
