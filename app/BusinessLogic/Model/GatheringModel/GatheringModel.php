<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use Persistence\DAO\GatheringDAO\LocationDAO;
use Exception;
use FileHelper;
use DateTime;
use Error;

class GatheringModel
{
    private $dao;
    private $locationDAO;
    private $validator;

    public function __construct()
    {
        $this->dao = new GatheringDAO();
        $this->locationDAO = new LocationDAO();
    }

    // Fetch all gatherings
    public function getAllGatherings(): array
    {
        return $this->dao->getAllGatherings();
    }

    // get getAvailableGatherings
    public function getAvailableGatherings($profileId)
    {
        return $this->dao->getAvailableGatherings($profileId);
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
        return $this->dao->verifyUserInGathering($userID, $gatheringID);

        // // Get the gatherings for the user
        // $gathering = $this->dao->getProfileGatheringByUserId($userID);

        // // Iterate through the gatherings
        // foreach ($gathering as $g) {
        //     // Check if this gathering matches the user and the gathering ID
        //     if ($g['gatheringID'] == $gatheringID && $g['profileID'] == $userID) {
        //         error_log("User $userID is already part of gathering $gatheringID.");
        //         return false; // The user is already part of this gathering
        //     }
        // }
        // return true; // User has not joined this gathering
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
        // if (!$this->verifyUserInGathering($userID, $gatheringID)) {
        //     error_log("User $userID has already joined gathering $gatheringID.");
        //     return false;
        // }

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
        try {
            $newId = $this->dao->createGathering($data);
            $profileId = $_SESSION['profile']['profileID'];
            $this->dao->addUserToGathering($profileId, $newId);
            return $newId;
        } catch (Exception $e) {
            error_log("[GatheringModel] Error creating gathering: " . $e->getMessage());
            throw $e;
        }
    }

    // Host Cancel Gathering
    public function cancelGathering($id)
    {
        $profileID = $_SESSION['profile']['profileID'];
        $gathering = $this->dao->getGatheringById($id);

        // validate user is host
        if ($profileID != $gathering['hostProfileID']) {
            $_SESSION['flash_message'] = "Unauthorized action.";
            $_SESSION['flash_type'] = "error";
            return false;
        }

        // validate time constraint
        $start = new DateTime($gathering['date'] . ' ' . $gathering['startTime']);
        $hoursDiff = ($start->getTimestamp() - (new DateTime())->getTimestamp()) / 3600;

        error_log('diff: ' . $hoursDiff);
        if ($hoursDiff < 3) {
            $_SESSION['flash_message'] = "Gathering can only be cancelled at least 3 hours before it starts.";
            $_SESSION['flash_type'] = "error";
            return false;
        }

        $result = $this->dao->cancelWithParticipant($id);

        if (is_array($result)) {
            // notify participants (skeleton function)
            foreach ($result as $p) {
                error_log("Would notify profile ID $p about cancellation of gathering $id");
            }

            $_SESSION['flash_message'] = "Gathering has been cancelled successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Something went wrong. Please try again.";
            $_SESSION['flash_type'] = "error";
        }

        return $result;
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

    // validate
    public function validateGatheringFields($post)
    {
        $errors = [];

        // 1. Theme: required, ≤100 chars, at least one letter
        $theme = trim($post['inputTheme'] ?? '');
        $errors = $this->validateTheme($theme, $errors);

        // 3. Pax: integer between 3 and 8
        $pax = (int)($post['inputPax'] ?? 0);
        $errors = $this->validatePax($pax, $errors);

        // 4. Location: ID + name must match DB
        $locId   = $post['locationId']    ?? '';
        $locName = trim($post['inputLocation'] ?? '');
        $errors = $this->validateLocation($locId, $locName, $errors);

        // 2. Date + Time: required, valid format, future, end>start, 3-hour buffer
        $date  = $post['inputDate']  ?? '';
        $start = $post['startTime']  ?? '';
        $end   = $post['endTime']    ?? '';

        // 2.1 Date presence & format
        $errors = $this->validateDate($date, $errors);

        // 2.2 Time presence
        $errors = $this->validateTime($start, $end, $errors);

        // only proceed if date, start & end are present and dateObj is valid
        if (empty($errors['inputDate']) && empty($errors['startTime']) && empty($errors['endTime'])) {
            $errors = $this->validateDateTime($date, $start, $end, $errors);
        }

        if (!empty($errors['startTime'])) {
            unset($errors['startTime']);
        }

        // 5. Overlap: re‐run on *every* valid date+start parse (so stale errors get cleared)
        $errors = $this->checkJoinedGathering($date, $start, $errors);

        return $errors;
    }

    private function validateTheme($theme, $errors)
    {
        if ($theme === '') {
            $errors['inputTheme'] = 'Theme is required.';
        } elseif (strlen($theme) > 100) {
            $errors['inputTheme'] = 'Theme cannot exceed 100 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $theme)) {
            $errors['inputTheme'] = 'Theme must contain at least one letter.';
        }
        return $errors;
    }

    private function validatePax($pax, $errors)
    {
        if ($pax < 3 || $pax > 8) {
            $errors['inputPax'] = 'Pax must be between 3 and 8.';
        }
        return $errors;
    }

    private function validateLocation($locId, $locName, $errors)
    {
        if ($locId === '' || $locName === '') {
            $errors['inputLocation'] = 'Please select a valid location.';
        } else {
            $row = $this->locationDAO->getLocationById($locId);
            if (!$row || strcasecmp($row['locationName'], $locName) !== 0) {
                $errors['inputLocation'] = 'Selected location is invalid.';
            }
        }
        return $errors;
    }

    private function validateDate($date, $errors)
    {
        if ($date === '') {
            $errors['inputDate'] = 'Date is required.';
        } else {
            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateObj) {
                $errors['inputDate'] = 'Invalid date format.';
            } elseif ($dateObj < new DateTime('today')) {
                $errors['inputDate'] = 'Date cannot be in the past.';
            }
        }
        return $errors;
    }

    private function validateTime($start, $end, $errors)
    {
        if ($start === '') {
            $errors['startTime'] = 'Start time is required.';
        }
        if ($end   === '') {
            $errors['endTime']   = 'End time is required.';
        }
        return $errors;
    }

    private function validateDateTime($date, $start, $end, $errors)
    {
        $startDT = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
        $endDT   = DateTime::createFromFormat('Y-m-d H:i', "$date $end");

        // 2.3 Valid time formats
        if (!$startDT || !$endDT) {
            $errors['startTime'] = 'Invalid time format.';
        }
        // 2.4 End must be after start
        elseif ($startDT >= $endDT) {
            $errors['endTime'] = 'End time must be after start time.';
        }
        // 2.5 3-hour buffer from “now”
        else {
            $minStart = (new DateTime())->modify('+3 hours');
            if ($startDT < $minStart) {
                $errors['startTime'] = 'Start time must be at least 3 hours from now.';
            }
        }
        return $errors;
    }

    private function checkJoinedGathering($date, $start, $errors)
    {
        unset($errors['startTime']);

        if ($date === '' || $start === '') {
            return $errors;
        }

        $profileID = $_SESSION['profile']['profileID'] ?? null;
        if (!$profileID) {
            return $errors;
        }

        $newStart = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
        if (!$newStart) {
            return $errors;
        }


        $joined = $this->dao->getJoinedGatheringByUserId($profileID);
        foreach ($joined as $g) {
            if (in_array(strtoupper($g['status']), ['END', 'CANCELLED'], true)) {
                continue;
            }
            $jStart = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['startTime']}"
            );
            $jEnd = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['endTime']}"
            );

            if ($jStart && $jEnd && $newStart >= $jStart && $newStart < $jEnd) {
                $errors['startTime'] = sprintf(
                    "You have another gathering from %s to %s.",
                    $jStart->format('d M Y g:i A'),
                    $jEnd->format('d M Y g:i A')
                );
                // only log when we actually set an error
                error_log($errors['startTime']);
                break;
            }
        }

        return $errors;
    }
}
