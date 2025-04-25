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

    // Add a user to a gathering (Join gathering)
    public function addUserToGathering(int $userID, int $gatheringID): bool
    {
        return $this->dao->addUserToGathering($userID, $gatheringID);
    }
}
