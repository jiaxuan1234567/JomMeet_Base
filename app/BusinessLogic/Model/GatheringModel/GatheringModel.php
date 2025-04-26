<?php

namespace BusinessLogic\Model\GatheringModel;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use Exception;
use FileHelper;
use DateTime;

date_default_timezone_set('Asia/Kuala_Lumpur'); // ✅ Set your local timezone

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
        return $this->dao->fetchAllGatherings();
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

        if (empty($gathering)) {
            // If no gatherings found for the user, log it
            error_log("No gatherings found for user $userID.");
        }

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
        $currentTime = new DateTime();  // Current system time (including date and time)

        // Get the specific gathering by gatheringID
        $gathering = $this->dao->getGatheringById($gatheringID);

        if (!$gathering) {
            // error_log("Gathering with ID $gatheringID not found.");
            return false;  // Gathering not found
        }

        // Combine the date (from 'date') and time (from 'startTime')
        $startDateTimeString = $gathering['date'] . ' ' . $gathering['startTime'];  // Concatenate date and time

        // Convert the combined date and time string into a DateTime object
        $startDateTime = new DateTime($startDateTimeString);  // Gathering start datetime

        // Log the comparison for debugging
        // error_log("Comparing current time: " . $currentTime->format('Y-m-d H:i:s') . " with gathering start time: " . $startDateTime->format('Y-m-d H:i:s'));

        // Check if the current time is before the gathering start datetime
        if ($currentTime < $startDateTime) {
            // Log if gathering has not started yet
            // error_log("Gathering with ID: " . $gathering['gatheringID'] . " has not started yet. Start Date/Time: " . $startDateTime->format('Y-m-d H:i:s'));
            return true; // Gathering has not started yet
        }

        // Gathering has already started
        return false;
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

    public function verifyEnded($gatheringID)
    {
        $gathering = $this->dao->getGatheringById($gatheringID);
        if ($gathering['status'] == 'END') {
            return true;
        } else {
            return false;
        }
    }

    public function createGathering($postData) {}
}
