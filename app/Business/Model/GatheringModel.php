<?php
// Business/Model/GatheringModel.php


class GatheringModel
{
    private $dao;

    public function __construct( $dao)
    {
        // Just assign the DAO you were given:
        $this->dao = $dao;
    }

    public function getAllGatherings(): array|false
    {
        return $this->dao->getAllGatherings();
    }

    public function getGatheringById(int $id): array|null
    {
        return $this->dao->getGatheringById($id);
    }
}
