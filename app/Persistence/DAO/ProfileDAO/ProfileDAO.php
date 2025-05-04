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

    public function getUserByPhoneNumber($phone)
    {
        try {
            $sql = "SELECT p.*, ph.hobby, pp.preference 
            FROM profile p
            LEFT JOIN profile_hobby ph ON p.profileID = ph.profileID
            LEFT JOIN profile_preference pp ON p.profileID = pp.profileID
            WHERE p.phone = :phone";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':phone' => $phone]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Base profile info from the first row
            if (empty($rows)) {
                return false;
            }
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

    public function getUserByProfileID($userId)
    {
        try {
            $sql = "SELECT p.*, ph.hobby, pp.preference 
            FROM `profile` p
            LEFT JOIN profile_hobby ph ON p.profileID = ph.profileID
            LEFT JOIN profile_preference pp ON p.profileID = pp.profileID
            WHERE p.profileID = :profileID";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':profileID' => $userId]);
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
            error_log("Error in getUserByProfileID: " . $e->getMessage());
            return false;
        }
    }

    public function submitProfile(array $data)
    {
        try {

            $this->db->beginTransaction();

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
                $data['aboutme'],
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

            $this->db->commit();
            return $profileId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error in insertProfile: " . $e->getMessage());
            return false;
        }
    }


    public function saveProfile(int $userId, array $data)
    {
        try {
            // 1) begin transaction
            $this->db->beginTransaction();

            // 2) update main profile fields
            $updateStmt = $this->db->prepare(
                'UPDATE `profile`
                SET nickname = :nick,
                    aboutme  = :about,
                    mbti     = :mbti
              WHERE profileID = :id'
            );
            $updateStmt->execute([
                ':nick'  => $data['nickname'],
                ':about' => $data['aboutme'],
                ':mbti'  => $data['mbti'],
                ':id'    => $userId,
            ]);

            // 3) refresh hobbies
            $deleteStmt1 = $this->db->prepare('DELETE FROM profile_hobby WHERE profileID = :id');
            $deleteStmt1->execute([':id' => $userId]);

            $insertEditedHobbies = $this->db->prepare(
                'INSERT INTO profile_hobby (hobby, profileID) VALUES (:hobby, :id)'
            );
            foreach ($data['hobbies'] as $hobby) {
                $insertEditedHobbies->execute([
                    ':hobby' => $hobby,
                    ':id'    => $userId,
                ]);
            }

            // 4) refresh preferences
            $deleteStmt2 = $this->db
                ->prepare('DELETE FROM profile_preference WHERE profileID = :id');
            $deleteStmt2->execute([':id' => $userId]);

            $insertEditedPreferences = $this->db->prepare(
                'INSERT INTO profile_preference (preference, profileID)
             VALUES (:pref, :id)'
            );
            foreach ($data['preferences'] as $pref) {
                $insertEditedPreferences->execute([
                    ':pref' => $pref,
                    ':id'   => $userId,
                ]);
            }

            // 5) commit
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // something went wrong; roll back
            $this->db->rollBack();
            error_log('ProfileDAO::update error: ' . $e->getMessage());
            return false;
        }
    }
}
