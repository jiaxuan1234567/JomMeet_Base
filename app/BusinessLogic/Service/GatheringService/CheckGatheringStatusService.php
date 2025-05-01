<?php

namespace BusinessLogic\Service\GatheringService;

use DateTime;

class CheckGatheringStatusService
{
    public function identifyTransitions($rows)
    {
        $now = new DateTime();
        $transitions = [];

        foreach ($rows as $g) {
            // parse datetimes
            $startDT = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['startTime']}"
            );
            $endDT = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['endTime']}"
            );

            // NEW → START
            if (strtoupper($g['status']) === 'NEW' && $startDT && $now >= $startDT && $now < $endDT) {
                $transitions[$g['gatheringID']] = 'START';

                print("[Gathering ID: " . $g['gatheringID'] . "] => [NEW => START]\n");

                // NEW → END (in case start and end both passed)
            } elseif (strtoupper($g['status']) === 'NEW' && $endDT && $now >= $endDT) {
                $transitions[$g['gatheringID']] = 'END';

                print("[Gathering ID: " . $g['gatheringID'] . "] => [NEW => END]\n");

                // START → END
            } elseif (strtoupper($g['status']) === 'START' && $endDT && $now >= $endDT) {
                $transitions[$g['gatheringID']] = 'END';

                print("[Gathering ID: " . $g['gatheringID'] . "] => [START => END]\n");
            }
        }

        return $transitions;
    }
}
