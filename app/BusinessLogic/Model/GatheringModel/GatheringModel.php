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

    public function getPreferenceTags()
    {
        return [
            ['label' => 'Food', 'value' => 'food', 'image' =>  $this->fileHelper->getFilePath('food')],
            ['label' => 'Chill', 'value' => 'chill', 'image' =>  $this->fileHelper->getFilePath('chill')],
            ['label' => 'Study', 'value' => 'study', 'image' =>  $this->fileHelper->getFilePath('study')],
            ['label' => 'Natural', 'value' => 'natural', 'image' =>  $this->fileHelper->getFilePath('natural')],
            ['label' => 'Shopping', 'value' => 'shopping', 'image' =>  $this->fileHelper->getFilePath('shopping')],
            ['label' => 'Workout', 'value' => 'workout', 'image' =>  $this->fileHelper->getFilePath('workout')],
            ['label' => 'Entertainment', 'value' => 'entertainment', 'image' =>  $this->fileHelper->getFilePath('entertainment')],
            ['label' => 'Music', 'value' => 'music', 'image' =>  $this->fileHelper->getFilePath('music')],
            ['label' => 'Movie', 'value' => 'movie', 'image' =>  $this->fileHelper->getFilePath('movie')],
        ];
    }

    public function getPaxLimit()
    {
        return ['minPax' => 3, 'maxPax' => 8];
    }

    public function getCreateAllowedDate()
    {
        return (new DateTime())->format('Y-m-d');
    }

    // Fetch all gatherings
    public function getAllGatherings()
    {
        return $this->gatheringDAO->getAllGatherings();
    }

    // get getAvailableGatherings
    public function getAvailableGatherings($profileId)
    {
        return $this->gatheringDAO->getAvailableGatherings($profileId);
    }

    // replace getAvailableGatherings
    public function getPublicGatheringById($profileId, $gatheringId)
    {
        $gathering = $this->gatheringDAO->getGatheringById($gatheringId);
        if (!$gathering) return false;

        // 1. Must be ACTIVE
        if ($gathering['status'] !== 'NEW') return false;

        // 2. Not hosted by user
        if ($gathering['hostProfileID'] == $profileId) return false;

        // 3. No.Pax not full
        if ($gathering['currentParticipant'] >= $gathering['maxParticipant']) return false;

        // 4. Not joined already
        if ($this->gatheringDAO->isUserJoined($gatheringId, $profileId)) return false;

        // 5. Not started
        $now = new DateTime();
        $startTime = new DateTime($gathering['date'] . ' ' . $gathering['startTime']);
        if ($startTime <= $now) return false;

        // 6. Not clashing with user’s active joined gatherings
        if ($this->gatheringDAO->hasTimeConflict($profileId, $startTime)) return false;

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
        if (!$gathering || !$this->gatheringDAO->isProfileInvolved($gatheringId, $profileId)) {
            return false;
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
            error_log("[GatheringModel] Starting isBeforeStartTime check for gathering ID: " . $gatheringID);
            // Get the specific gathering by gatheringID
            $gathering = $this->gatheringDAO->getGatheringById($gatheringID);
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
    public function getMyGatherings($profileId)
    {
        try {
            $rows = $this->gatheringDAO->getUserAllGatherings($profileId);
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
                    'status'    => strtolower($g['status']),
                    'isHost'    => (bool)$g['isHost'],
                    'isJoined'  => (bool)$g['isJoined'],
                ];
            }, $rows ?: []);
        } catch (Exception $e) {
            error_log("[GatheringModel] Error in getMyGatherings: " . $e->getMessage());
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
            throw $e;
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
            // Get user's profile to access their preferences
            $userProfile = $this->gatheringDAO->getProfileByUserId($userID);
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

    // validate
    public function validateGatheringFields($post)
    {
        // 1) fetch persistence‐backed facts
        $locRow = $this->locationDAO->getLocationById($post['locationId'] ?? '');
        $joined = $this->gatheringDAO->getJoinedGatheringByUserId(
            $_SESSION['profile']['profileID'] ?? 0
        );

        // 2) call pure helper
        return $this->validatorService->validate($post, $locRow, $joined);
    }
}
