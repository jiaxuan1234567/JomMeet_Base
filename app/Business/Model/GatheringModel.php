<?php
require_once __DIR__ . '../../../Persistence/DAO/GatheringDAO.php';
$dao = new GatheringDAO($db);

class GatheringModel
{
    private $dao;

    public function __construct($dao)
    {
        // Just assign the DAO you were given:
        $this->dao = $dao;
    }

    public function getAllGatherings(): array
    {
        return $this->dao->getAllGatherings();
    }

    public function getGatheringById(int $id): array|null
    {
        return $this->dao->getGatheringById($id);
    }
}
