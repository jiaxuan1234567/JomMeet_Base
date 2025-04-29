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

    public function getProfileDetails($profileId)
    {
        try {
            // Basic profile fields
            $stmt = $this->db->prepare(
                "SELECT phone, nickname, mbti, aboutme, hobbies, preference FROM `profile` WHERE profileID = $profileId"
            );
            
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return [];
            }

            // Split comma-lists into arrays
            $hobbies     = array_filter(array_map('trim', explode(',', $row['hobbies'] ?? '')));
            $preferences = array_filter(array_map('trim', explode(',', $row['preference'] ?? '')));

            return [
                'phone'         => $row['phone'],
                'nickname'      => $row['nickname'],
                'mbti'          => $row['mbti'],
                'aboutme'       => $row['aboutme'],
                'hobbies'       => $hobbies,
                'preferences'   => $preferences,
            ];
        } catch (PDOException $e) {
            error_log("Error in getProfileID: " . $e->getMessage());
            return [];
        }
    }
}
