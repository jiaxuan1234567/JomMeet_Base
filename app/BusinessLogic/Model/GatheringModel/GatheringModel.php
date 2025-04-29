<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use Exception;
use FileHelper;
use DateTime;

date_default_timezone_set('Asia/Kuala_Lumpur'); // or whatever matches your system


class GatheringModel
{
    private $dao;

    public function __construct()
    {
        $this->dao = new GatheringDAO();
    }

    // Fetch all gatherings
    public function getAllGatherings(): array
    {
        return $this->dao->getAllGatherings();
    }

    public function searchGatherings(string $searchTerm): array
    {
        try {
            $results = $this->dao->searchGatherings($searchTerm);
            return $results ?: [];
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in searchGatherings: " . $e->getMessage());
            return [];
        }
    }

    // Fetch a gathering by its ID
    public function getGatheringById(int $id): array
    {
        return $this->dao->getGatheringById($id);
    }

    public function verifyUserInGathering($userID, $gatheringID)
    {
        // Get the gatherings for the user
        $gathering = $this->dao->getProfileGatheringByUserId($userID);

        // Iterate through the gatherings
        foreach ($gathering as $g) {
            // Check if this gathering matches the user and the gathering ID
            if ($g['gatheringID'] == $gatheringID && $g['profileID'] == $userID) {
                error_log("User $userID is already part of gathering $gatheringID.");
                return false; // The user is already part of this gathering
            }
        }
        return true; // User has not joined this gathering
    }

    public function isBeforeStartTime($gatheringID)
    {
        try {
            error_log("[GatheringModel] Starting isBeforeStartTime check for gathering ID: " . $gatheringID);
            
            // Get the specific gathering by gatheringID
            $gathering = $this->dao->getGatheringById($gatheringID);
            if (!$gathering) {
                error_log("[GatheringModel] Gathering not found for ID: " . $gatheringID);
                return false;
            }
            
            // Get current system time
            $currentTime = new DateTime();
            error_log("[GatheringModel] Current system time: " . $currentTime->format('Y-m-d H:i:s'));
            
            // Create DateTime object for gathering
            $gatheringDateTime = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $gathering['date'] . ' ' . $gathering['startTime']
            );
            
            error_log("[GatheringModel] Gathering time: " . $gatheringDateTime->format('Y-m-d H:i:s'));
            
            // Compare current time with gathering time
            if ($currentTime > $gatheringDateTime) {
                error_log("[GatheringModel] Gathering has already started. Returning false");
                return false;
            }
            
            error_log("[GatheringModel] Gathering is in the future. Returning true");
            return true;
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in isBeforeStartTime: " . $e->getMessage());
            error_log("[GatheringModel] Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // Add a user to a gathering (Join gathering)
    public function addUserToGathering(int $userID, int $gatheringID): bool
    {
        if (!$this->verifyUserInGathering($userID, $gatheringID)) {
            error_log("User $userID has already joined gathering $gatheringID.");
            return false;
        }

        $result = $this->dao->addUserToGathering($userID, $gatheringID);

        if ($result) {
            error_log("User $userID successfully joined gathering $gatheringID.");
        } else {
            error_log("User $userID failed to join gathering $gatheringID.");
        }

        return $result;
    }

    public function getJoinedGatheringByUserId($userID)
    {
        return $this->dao->getJoinedGatheringByUserId($userID);
    }

    public function isNewGatheringConflicting($userID, $gatheringID)
    {
        // Log userID and gatheringID for tracking
        error_log("Checking conflict for userID: $userID, gatheringID: $gatheringID");

        // Get the gatherings that the user has already joined
        $joinedGatherings = $this->getJoinedGatheringByUserId($userID);
        error_log("User $userID has joined " . count($joinedGatherings) . " gatherings.");
        // Fetch the new gathering details based on gatheringID
        $newGathering = $this->dao->getGatheringById($gatheringID);

        if (!$newGathering) {
            // Log if the new gathering doesn't exist
            error_log("Error: Gathering with ID $gatheringID does not exist.");
            return false; // or return an error message depending on your logic
        }

        $newGatheringDateTime = $newGathering['date'] . ' ' . $newGathering['startTime'];
        // Convert new gathering's date and time into a DateTime object
        $newGatheringDateTimeObj = new DateTime($newGatheringDateTime);  // Convert to DateTime object
        error_log("New gathering DateTime object: " . $newGatheringDateTimeObj->format('Y-m-d H:i:s'));

        // Loop through each of the user's joined gatherings
        foreach ($joinedGatherings as $gathering) {
            // Combine the existing gathering's date and time into a single datetime string
            $existingGatheringDateTime = $gathering['date'] . ' ' . $gathering['startTime'];
            // Convert to DateTime object for comparison
            $existingGatheringDateTimeObj = new DateTime($existingGatheringDateTime);  // Convert to DateTime object
            error_log("Existing gathering DateTime object: " . $existingGatheringDateTimeObj->format('Y-m-d H:i:s'));

            // Compare the new gathering date/time with the existing one
            if ($newGatheringDateTimeObj == $existingGatheringDateTimeObj) {
                // Log if there's a conflict
                error_log("Conflict found: New gathering time matches with existing gathering ID: " . $gathering['gatheringID']);
                return true;  // Conflict found
            }
        }

        // Log if no conflict is found
        error_log("No conflict found for user $userID and gathering $gatheringID.");
        // If no conflicts, return false
        return false;
    }


    // my-gathering
    public function getMyGatherings($profileId)
    {
        try {
            $rows = $this->dao->getMyGatherings($profileId);
            return array_map(function (array $g) {
                return [
                    'id'        => (int)$g['gatheringID'],
                    'cover'     => $g['image'],
                    'theme'     => $g['theme'],
                    'date'      => date('d F Y', strtotime($g['date'])),
                    'startTime' => date('g:i A',   strtotime($g['startTime'])),
                    'endTime'   => date('g:i A',   strtotime($g['endTime'])),
                    'pax'       => (int)$g['currentParticipant'],
                    'maxPax'       => (int)$g['maxParticipant'],
                    'venue'     => $g['venue'],
                    'status'    => strtolower($g['status']),    // 'new','start','end','cancelled'
                    'isHost'    => (bool)$g['isHost'],
                    'isJoined'  => (bool)$g['isJoined'],
                ];
            }, $rows ?: []);
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in getMyGatherings: " . $e->getMessage());
            return [];
        }
    }

    public function createGathering($data)
    {
        // --- 1) basic validation ---
        if (empty($data['locationId']) || empty($data['theme'])) {
            throw new Exception("Missing required fields");
        }
        // you can add more validation here (e.g. date format, time logic, etc.)

        // --- 2) call the DAO to insert & get new PK ---
        try {
            $newId = $this->dao->createGathering($data);
            $profileId = $_SESSION['profile_id'];
            $this->dao->addUserToGathering($profileId, $newId);
            return $newId;
        } catch (Exception $e) {
            error_log("[GatheringModel] Error creating gathering: " . $e->getMessage());
            throw $e;
        }
    }

    public function matchGathering(int $userID): array
    {
        try {
            // Get user's profile to access their preferences
            $userProfile = $this->dao->getProfileByUserId($userID);
            if (!$userProfile) {
                error_log("[GatheringModel] User profile not found for userID: $userID");
                return [];
            }

            // Get all available gatherings
            $allGatherings = $this->getAllGatherings();
            if (empty($allGatherings)) {
                error_log("[GatheringModel] No gatherings available for matching");
                return [];
            }

            // Get gatherings the user has already joined
            $joinedGatherings = $this->getJoinedGatheringByUserId($userID);
            $joinedGatheringIds = array_column($joinedGatherings, 'gatheringID');

            // Filter and score gatherings based on preferences
            $matchedGatherings = [];
            foreach ($allGatherings as $gathering) {
                // Skip gatherings the user has already joined
                if (in_array($gathering['gatheringID'], $joinedGatheringIds)) {
                    continue;
                }

                // Skip gatherings that are full
                if ($gathering['currentParticipant'] >= $gathering['maxParticipant']) {
                    continue;
                }

                // Skip gatherings that have already started
                if (!$this->isBeforeStartTime($gathering['gatheringID'])) {
                    continue;
                }

                // Calculate matching score based on preferences
                $score = 0;

                // Basic preference matching
                if (isset($userProfile['preference']) && $userProfile['preference'] === $gathering['preference']) {
                    $score += 10;
                }

                // Add more matching criteria here in the future
                // For example:
                // - Location proximity
                // - Time of day preference
                // - Group size preference
                // - Theme matching
                // - MBTI compatibility
                // - Hobbies matching

                if ($score > 0) {
                    $gathering['matchScore'] = $score;
                    $matchedGatherings[] = $gathering;
                }
            }

            // Sort gatherings by match score in descending order
            usort($matchedGatherings, function ($a, $b) {
                return $b['matchScore'] <=> $a['matchScore'];
            });

            return $matchedGatherings;
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in matchGathering: " . $e->getMessage());
            return [];
        }
    }
}
