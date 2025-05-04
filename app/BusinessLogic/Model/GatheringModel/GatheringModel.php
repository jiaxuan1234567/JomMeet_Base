<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use Persistence\DAO\GatheringDAO\LocationDAO;
use BusinessLogic\Service\GatheringService\CheckGatheringStatusService;
use BusinessLogic\Service\GatheringService\GatheringValidationService;
use Exception;
use FileHelper;
use DateTime;
use Error;

class GatheringModel
{
    private $gatheringDAO;
    private $locationDAO;
    private $chkStatusService;
    private $validatorService;
    private $fileHelper;

    public function __construct()
    {
        $this->gatheringDAO = new GatheringDAO();
        $this->locationDAO = new LocationDAO();
        $this->chkStatusService = new CheckGatheringStatusService();
        $this->validatorService = new GatheringValidationService();
        $this->fileHelper = new FileHelper('gathering');
    }

    public function getPreference()
    {
        return ['FOOD', 'CHILL', 'STUDY', 'NATURAL', 'SHOPPING', 'WORKOUT', 'ENTERTAINMENT', 'MUSIC', 'MOVIE'];
    }

    public function getPreferenceTags()
    {
        $tags = [];

        foreach ($this->getPreference() as $preference) {
            $value = strtolower($preference);
            $tags[] = [
                'label' => ucfirst($value),
                'value' => $value,
                'image' => $this->fileHelper->getFilePath($value)
            ];
        }

        return $tags;
    }

    public function getPaxLimit()
    {
        return ['minPax' => 3, 'maxPax' => 8];
    }

    public function getEditPaxLimit($gathering)
    {
        return [
            'minPax' => ($gathering['currentParticipant'] > 3) ? ($gathering['currentParticipant']) : 3,
            'maxPax' => 8
        ];
    }

    public function getCreateAllowedDate()
    {
        return (new DateTime())->format('Y-m-d');
    }

    public function isValidTimeConstraint($startTime, $date)
    {
        $start = new DateTime($date . ' ' . $startTime);
        $hoursDiff = ($start->getTimestamp() - (new DateTime())->getTimestamp()) / 3600;

        return $hoursDiff > 3;
    }

    // Fetch all gatherings
    public function getAllGatherings()
    {
        return $this->gatheringDAO->getAllGatherings();
    }

    // get getAvailableGatherings
    public function getAvailableGatherings($profileId)
    {
        $allGatherings = $this->gatheringDAO->getAvailableGatherings($profileId);
        if (!$allGatherings) {
            error_log("[getAvailableGatherings] DAO returned false or empty");
            return [];
        }

        $validGatherings = [];
        foreach ($allGatherings as $gathering) {
            $valid = $this->getPublicGatheringById($profileId, $gathering['gatheringID']);
            if ($valid) {
                $validGatherings[] = $valid;
            } else {
                error_log("[getAvailableGatherings] Skipping gathering {$gathering['gatheringID']}: not eligible.");
            }
        }

        return $validGatherings;
    }


    // replace getAvailableGatherings
    public function getPublicGatheringById($profileId, $gatheringId)
    {
        $gathering = $this->gatheringDAO->getGatheringById($gatheringId);
        if (!$gathering) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId not found.");
            return false;
        }

        // 1. Must be ACTIVE
        if ($gathering['status'] !== 'NEW') {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: status is '{$gathering['status']}', not 'NEW'.");
            return false;
        }

        // 2. Not hosted by user
        if ($gathering['hostProfileID'] == $profileId) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: hosted by profile ID $profileId.");
            return false;
        }

        // 3. No.Pax not full
        if ($gathering['currentParticipant'] >= $gathering['maxParticipant']) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: max participants reached ({$gathering['currentParticipant']}/{$gathering['maxParticipant']}).");
            return false;
        }

        // 4. Not joined already
        if ($this->gatheringDAO->isUserJoined($gatheringId, $profileId)) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: user $profileId already joined.");
            return false;
        }

        // 5. Not started
        $now = new DateTime();
        $startTime = new DateTime($gathering['date'] . ' ' . $gathering['startTime']);
        if ($startTime <= $now) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: already started at {$startTime->format('Y-m-d H:i:s')}.");
            return false;
        }

        // 5. Not started
        $now = new DateTime();
        $startTime = new DateTime($gathering['date'] . ' ' . $gathering['startTime']);
        if ($startTime <= $now) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: already started at {$startTime->format('Y-m-d H:i:s')}.");
            return false;
        }


        // 6. Not clashing with user’s active joined gatherings
        if ($this->gatheringDAO->hasTimeConflict($profileId, $startTime)) {
            error_log("[getPublicGatheringById] Gathering ID $gatheringId rejected: time conflict with another joined gathering.");
            return false;
        }

        error_log("[getPublicGatheringById] Passed all checks before time conflict for gathering $gatheringId.");

        return $gathering;
    }

    public function searchGatherings($searchTerm)
    {
        try {
            $results = $this->gatheringDAO->searchGatherings($searchTerm);
            return $results ?: [];
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in searchGatherings: " . $e->getMessage());
            return [];
        }
    }

    // Fetch a gathering by its ID
    public function getGatheringById($id)
    {
        return $this->gatheringDAO->getGatheringById($id);
    }

    // Fetch user only gathering by gathering ID
    public function getUserGatheringById($profileId, $gatheringId)
    {
        $gathering = $this->gatheringDAO->getGatheringById($gatheringId);
        if (!$gathering) return false;

        if (!$this->gatheringDAO->isProfileInvolved($gatheringId, $profileId) && !$this->gatheringDAO->isHostInvolved($gatheringId, $profileId)) {
            return false;
        }
        return $gathering;
    }

    // Fetch user editable gathering by gathering ID (hosted AND start > 3 hours from current)
    public function getEditableGatheringById($gatheringId, $profileId)
    {
        $isHost = $this->gatheringDAO->isHostInvolved($gatheringId, $profileId);

        if (!$isHost) return ['error' => 'Not Host'];

        $gathering = $this->gatheringDAO->getGatheringById($gatheringId);

        if (!$this->isValidTimeConstraint($gathering['startTime'], $gathering['date'])) {
            return ['error' => 'Invalid Time Constraint'];
        }

        return $gathering;
    }

    public function verifyUserInGathering($userID, $gatheringID)
    {
        return $this->gatheringDAO->verifyUserInGathering($userID, $gatheringID);

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
            // Get the specific gathering by gatheringID
            $gathering = $this->gatheringDAO->getGatheringById($gatheringID);
            if (!$gathering) {
                error_log("[GatheringModel] Gathering not found for ID: " . $gatheringID);
                return false;
            }

            // Get current system time
            $currentTime = new DateTime();

            // Create DateTime object for gathering
            $gatheringDateTime = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $gathering['date'] . ' ' . $gathering['startTime']
            );

            // Compare current time with gathering time
            if ($currentTime > $gatheringDateTime) {
                error_log("[GatheringModel] Gathering has already started. Returning false");
                return false;
            }
            return true;
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in isBeforeStartTime: " . $e->getMessage());
            error_log("[GatheringModel] Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    // Add a user to a gathering (Join gathering)
    public function addUserToGathering($userID, $gatheringID)
    {
        $result = $this->gatheringDAO->addUserToGathering($userID, $gatheringID);

        if ($result) {
            error_log("User $userID successfully joined gathering $gatheringID.");
        } else {
            error_log("User $userID failed to join gathering $gatheringID.");
        }

        return $result;
    }

    public function getJoinedGatheringByUserId($userID)
    {
        return $this->gatheringDAO->getJoinedGatheringByUserId($userID);
    }

    public function isNewGatheringConflicting($userID, $gatheringID)
    {
        // Log userID and gatheringID for tracking
        error_log("Checking conflict for userID: $userID, gatheringID: $gatheringID");

        // Get the gatherings that the user has already joined
        $joinedGatherings = $this->getJoinedGatheringByUserId($userID);
        error_log("User $userID has joined " . count($joinedGatherings) . " gatherings.");
        // Fetch the new gathering details based on gatheringID
        $newGathering = $this->gatheringDAO->getGatheringById($gatheringID);

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
    // public function getMyGatheringsWithTab($profileId)
    // {
    //     $gatherings = (new GatheringDAO())->getUserAllGatherings($profileId);

    //     // Ensure all required keys are set with default values
    //     return array_map(function ($g) use ($profileId) {
    //         return [
    //             'id' => $g['gatheringID'] ?? null,
    //             'isHost' => isset($g['hostProfileID']) && $g['hostProfileID'] == $profileId,
    //             'status' => $g['status'] ?? 'unknown',
    //             'theme' => $g['theme'] ?? 'No Theme',
    //             'date' => $g['date'] ?? null,
    //             'startTime' => $g['startTime'] ?? null,
    //             'endTime' => $g['endTime'] ?? null,
    //             'venue' => $g['venue'] ?? 'Unknown Venue',
    //             'pax' => $g['currentParticipant'] ?? 0,
    //             'maxPax' => $g['maxParticipant'] ?? 0,
    //             'cover' => $g['image'] ?? 'default-image.png',
    //         ];
    //     }, $gatherings);
    // }

    public function getMyGatheringsWithTab($profileId)
    {
        try{
            $this->gatheringDAO->updateGatheringStatuses();
            $rows = $this->gatheringDAO->getUserAllGatherings($profileId);
            return array_map(function (array $g) {
                return [
                    'id' => (int)$g['gatheringID'],
                    'locationID' => (int)$g['locationID'],
                    'cover' => $g['image'] ?: 'bubble.png',
                    'theme' => $g['theme'],
                    'date' => date('d F Y', strtotime($g['date'])),
                    'startTime' => date('g:i A', strtotime($g['startTime'])),
                    'endTime' => date('g:i A', strtotime($g['endTime'])),
                    'pax' => (int)$g['currentParticipant'],
                    'maxpax' => (int)$g['maxParticipant'],
                    'venue' => $g['venue'],
                    'status' => strtolower($g['status']),
                    'isHost' => (bool)$g['isHost'],
                    'isJoined' => (bool)$g['isJoined'],
                ];
            }, $rows ?: []);
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in getMyGatherings: " + $e->getMessage());
            return [];
        }
        }
    

    // Create Gathering
    public function createGathering($data)
    {
        try {
            $newId = $this->gatheringDAO->createGathering($data);
            $profileId = $_SESSION['profile']['profileID'];
            $this->gatheringDAO->addUserToGathering($profileId, $newId);
            return $newId;
        } catch (Exception $e) {
            error_log("[GatheringModel] Error creating gathering: " . $e->getMessage());
            return false;
        }
    }

    // Edit Gathering
    public function updateGathering($data, $profileId, $gatheringId)
    {
        try {
            return $this->gatheringDAO->updateGathering($data, $profileId, $gatheringId);
        } catch (Exception $e) {
            error_log("[GatheringModel] Error updating gathering: " . $e->getMessage());
            return false;
        }
    }

    // Host Cancel Gathering
    public function cancelGathering($id)
    {
        $profileID = $_SESSION['profile']['profileID'];
        $gathering = $this->gatheringDAO->getGatheringById($id);

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

        $result = $this->gatheringDAO->cancelWithParticipant($id);

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

    // Participant Leave Gathering
    public function leaveGathering($profileId, $gatheringId)
    {
        try {
            // Validate Gathering is Valid
            $gathering = $this->gatheringDAO->getGatheringById($gatheringId);
            if (!$gathering) {
                return false;
            }

            // Is Host?
            if ($gathering['hostProfileID'] == $profileId) {
                // Host cannot leave
                return false;
            }

            // Leave
            $result = $this->gatheringDAO->leaveGathering($profileId, $gatheringId);


            if ($result) {
                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'You have successfully left the gathering.';
            } else {
                $_SESSION['flash_type'] = 'error';
                $_SESSION['flash_message'] = 'Something went wrong.';
            }
            return $result;
        } catch (Exception $e) {
            $this->gatheringDAO->rollback(); // in case of uncaught error
            error_log("[GatheringModel] Error in leaveGathering: " . $e->getMessage());
            return false;
        }
    }

    public function matchGathering($userID)
    {
        try {
            error_log("[matchGathering] Start matching for userID: $userID");

            // Get user's profile to access their preferences and hobbies
            $userProfile = $this->gatheringDAO->getProfileByUserId($userID);
            $userHobbies = $this->gatheringDAO->getAllProfileHobby($userID); // array of strings
            $userPreferences = $this->gatheringDAO->getAllProfilePreference($userID); // array of strings
            $allGatherings = $this->getAvailableGatherings($userID);

            // Log user data for debugging
            error_log("[matchGathering] Retrieved profile for userID $userID: " . json_encode($userProfile));
            error_log("[matchGathering] User hobbies: " . json_encode($userHobbies));
            error_log("[matchGathering] User preferences: " . json_encode($userPreferences));

            // Check if profile exists
            if (!$userProfile) {
                error_log("[matchGathering] User profile not found for userID: $userID");
                return [];
            }

            // Check if there are gatherings to match
            if (empty($allGatherings)) {
                error_log("[matchGathering] No gatherings available for matching");
                return [];
            }
            error_log("[matchGathering] Total gatherings retrieved: " . count($allGatherings));

            // Get gatherings the user has already joined
            $joinedGatherings = $this->getJoinedGatheringByUserId($userID);
            $joinedGatheringIds = array_column($joinedGatherings, 'gatheringID');
            error_log("[matchGathering] User already joined gatherings: " . json_encode($joinedGatheringIds));

            $matchedGatherings = [];

            // Normalize user preferences and hobbies to lowercase for comparison
            $userPreferences = array_map('strtolower', $userPreferences);
            $userHobbies = array_map('strtolower', $userHobbies);

            // Loop through all gatherings to check for matching preferences and hobbies
            foreach ($allGatherings as $gathering) {
                $gatheringID = $gathering['gatheringID'];
                $hostProfileID = $gathering['hostProfileID'];

                // Skip gatherings the user has already joined
                if (in_array($gatheringID, $joinedGatheringIds)) {
                    error_log("[matchGathering] Skipping gathering $gatheringID: already joined");
                    continue;
                }

                // Skip gatherings that are full
                if ($gathering['currentParticipant'] >= $gathering['maxParticipant']) {
                    error_log("[matchGathering] Skipping gathering $gatheringID: full");
                    continue;
                }

                // Skip gatherings that have already started
                if (!$this->isBeforeStartTime($gatheringID)) {
                    error_log("[matchGathering] Skipping gathering $gatheringID: already started");
                    continue;
                }

                $score = 0;
                $matchedPrefs = [];
                $matchedHobbies = [];

                // Match user preferences (case-insensitive)
                if (isset($gathering['preference']) && !empty($gathering['preference'])) {
                    error_log("[matchGathering] Checking if gathering preference '{$gathering['preference']}' is in userPreferences");
                    if (in_array(strtolower($gathering['preference']), $userPreferences)) {
                        $matchedPrefs[] = $gathering['preference'];
                        $score += 5; // Award 5 points for each matched preference
                        error_log("[matchGathering] Matched preference '{$gathering['preference']}' for gathering $gatheringID. Score: 5");
                    }
                }

                // Match user hobbies (case-insensitive)
                if (isset($gathering['hobby']) && !empty($gathering['hobby'])) {
                    error_log("[matchGathering] Checking if gathering hobby '{$gathering['hobby']}' is in userHobbies");
                    if (in_array(strtolower($gathering['hobby']), $userHobbies)) {
                        $matchedHobbies[] = $gathering['hobby'];
                        $score += 3; // Award 3 points for each matched hobby
                    }
                }

                // Fetch host's hobbies
                $hostHobbies = $this->gatheringDAO->getAllProfileHobby($hostProfileID);
                $hostHobbies = array_map('strtolower', $hostHobbies); // Normalize host hobbies to lowercase

                // Match host's hobbies (case-insensitive)
                if (!empty($hostHobbies)) {
                    error_log("[matchGathering] Checking if gathering host hobbies " . json_encode($hostHobbies) . " match user hobbies");
                    $commonHobbies = array_intersect($hostHobbies, $userHobbies);
                    if (!empty($commonHobbies)) {
                        $matchedHobbies = array_merge($matchedHobbies, $commonHobbies);
                        $score += 2; // Award 2 points for each common hobby with the host
                    }
                }

                // Log match score and details for debugging
                error_log("[matchGathering] Gathering $gatheringID match score: $score (Prefs: " . json_encode($matchedPrefs) . ", Hobbies: " . json_encode($matchedHobbies) . ")");

                // If there's a match, add gathering to the matched list
                if ($score > 0) {
                    $gathering['matchScore'] = $score;
                    $matchedGatherings[] = $gathering;
                }
            }

            // Sort matched gatherings by match score in descending order
            usort($matchedGatherings, function ($a, $b) {
                return $b['matchScore'] <=> $a['matchScore'];
            });

            // Log total matched gatherings for debugging
            error_log("[matchGathering] Total matched gatherings: " . count($matchedGatherings));

            return $matchedGatherings;
        } catch (Exception $e) {
            error_log("[matchGathering] Error: " . $e->getMessage());
            return [];
        }
    }



    // validate Gathering Fields
    public function validateGatheringFields($data, $fields, $editingId = null)
    {
        // 1) fetch persistence‐backed facts
        $validTags = $this->getPreference();
        $locRow = $this->locationDAO->getLocationById($post['locationId'] ?? '');
        if ($locRow === false) $locRow = null;
        $joined = $this->gatheringDAO->getJoinedGatheringByUserId(
            $_SESSION['profile']['profileID'] ?? 0
        );

        // 2) call pure helper
        return $this->validatorService->validate($data, $validTags, $locRow, $joined, $fields, $editingId);
    }

    // Status Service (run job)
    public function checkAndTransitionGatherings()
    {
        // 1) DAO gives only the candidates
        $candidates = $this->gatheringDAO->fetchGatheringsToTransition();

        // 2) Service returns a map [id=>newStatus]
        $toUpdate = $this->chkStatusService->identifyTransitions($candidates);

        // 3) Persist each change
        $updated = false;
        foreach ($toUpdate as $id => $newStatus) {
            if ($this->gatheringDAO->updateGatheringStatus($id, $newStatus)) {
                $updated = true;
            }
        }

        return $updated;
    }

    // Helper Function
    private function determineActions($status, $isHost, $isJoined)
    {
        if ($status === 'cancelled') {
            return [];
        }

        if ($status === 'new') {
            return $isHost
                ? ['send reminder', 'edit gathering', 'cancel gathering']
                : ['reply reminder', 'leave gathering'];
        }

        if ($status === 'start') {
            return $isHost
                ? ['send reminder', 'reply reminder']
                : ['reply reminder'];
        }

        if ($status === 'end') {
            return ['gathering feedback', 'location feedback'];
        }

        return [];
    }

    // Location Feedback
    public function getLocationFeedback($locationId)
    {
        return $this->gatheringDAO
            ->getLocationFeedbackByLocation($locationId);
    }

    /**
     * Save new feedback if the user hasn’t already posted one.
     */
    public function saveLocationFeedback($profileId, $gatheringId, $locationId, $desc)
    {
        return $this->gatheringDAO
            ->insertLocationFeedback($profileId, $gatheringId, $locationId, $desc);
    }

    // fetch all gathering feedback (anonymous)
    public function getGatheringFeedback(int $gatheringId): array
    {
        return $this->gatheringDAO
            ->getGatheringFeedbackByGathering($gatheringId);
    }

    // insert a new gathering feedback record
    public function addGatheringFeedback(int $profileId, int $gatheringId, string $desc): bool
    {
        // load the gathering row so we know its locationID
        $g = $this->gatheringDAO->getGatheringById($gatheringId);
        if (!$g) {
            throw new Exception("Gathering #{$gatheringId} not found");
        }

        $locationId = (int)$g['locationID'];
        return $this->gatheringDAO->insertGatheringFeedback(
            $profileId,
            $gatheringId,
            $locationId,
            $desc
        );
    }
}
