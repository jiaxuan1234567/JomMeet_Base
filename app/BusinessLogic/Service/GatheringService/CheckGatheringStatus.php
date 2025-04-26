<?php
namespace BusinessLogic\Service\GatheringService;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use DateTime;

class CheckGatheringStatus
{
    private $dao;

    public function __construct()
    {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $this->dao = new GatheringDAO();
    }

    public function run(): bool
    {
        $gatherings = $this->dao->getAllGatherings();
        $now = new DateTime();
        $updated = false;

        error_log("[CheckGatheringStatus] Script started at " . $now->format('Y-m-d H:i:s'));
        error_log("[CheckGatheringStatus] Total gatherings fetched: " . count($gatherings));

        foreach ($gatherings as $g) {
            $endDateTimeString = $g['date'] . ' ' . $g['endTime'];
            $endDateTime = new DateTime($endDateTimeString);

            error_log("[CheckGatheringStatus] Checking Gathering ID {$g['gatheringID']} | Gathering EndDateTime: " . $endDateTime->format('Y-m-d H:i:s') . " | Current Status: {$g['status']}");

            if ($now >= $endDateTime && $g['status'] !== 'END') {
                error_log("[CheckGatheringStatus] Condition matched. Updating Gathering ID {$g['gatheringID']} to 'END'");
                $this->dao->updateGatheringStatus($g['gatheringID'], 'END');
                $updated = true;
            } else {
                error_log("[CheckGatheringStatus] Condition NOT matched. No update for Gathering ID {$g['gatheringID']}");
            }
        }

        if ($updated) {
            error_log("[CheckGatheringStatus] One or more gatherings were updated.");
        } else {
            error_log("[CheckGatheringStatus] No gatherings needed updating.");
        }

        return $updated;
    }
}
