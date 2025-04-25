<?php
require_once __DIR__ . '../../../Persistence/DAO/GatheringDAO.php';
$dao = new GatheringDAO($db);

class GatheringModel
{
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    // Fetch all gatherings
    public function getAllGatherings(): array
    {
        return $this->dao->getAllGatherings();
    }

    // Fetch a gathering by its ID
    public function getGatheringById(int $id): array|null
    {
        return $this->dao->getGatheringById($id);
    }

    public function verifyUserInGathering($userID, $gatheringID)
    {
        // Get the gatherings for the user
        $gathering = $this->dao->getProfileGatheringByUserId($userID);

        // Log the raw result from the database
        error_log("Gathering data for user $userID: " . var_export($gathering, true));

        if (empty($gathering)) {
            // If no gatherings found for the user, log it
            error_log("No gatherings found for user $userID.");
        }

        // Iterate through the gatherings
        foreach ($gathering as $g) {
            // Log each gathering being checked
            error_log("Checking gathering: " . var_export($g, true));

            // Check if this gathering matches the user and the gathering ID
            if ($g['gatheringID'] == $gatheringID && $g['profileID'] == $userID) {
                error_log("User $userID is already part of gathering $gatheringID.");
                return false; // The user is already part of this gathering
            }
        }

        // Log if the user is not found in the gathering
        error_log("User $userID has not joined gathering $gatheringID.");
        return true; // User has not joined this gathering
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
}
