<?php

namespace BusinessLogic\Service\GatheringService;

class CheckGatheringStatusService
{
    public function identifyToClose($toClose)
    {
        $now = new \DateTime();
        $ids = [];

        foreach ($toClose as $g) {
            $endDT = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['endTime']}"
            );
            if ($endDT && $now >= $endDT) {
                $ids[] = (int)$g['gatheringID'];
            }
            error_log('Service Check on ' . $g['gatheringID'] . ' ...');
        }

        return $ids;
    }
}
