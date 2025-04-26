<?php
namespace BusinessLogic\Service\GatheringService\CheckGatheringStatus;

use Persistence\DAO\GatheringDAO\GatheringDAO;
use DateTime;

$dao = new GatheringDAO();
$gatherings = $dao->getAllGatherings();
$now = new DateTime();
$updated = false;

error_log("[CheckGatheringStatus] Script started at " . $now->format('Y-m-d H:i:s'));
error_log("[CheckGatheringStatus] Total gatherings fetched: " . count($gatherings));

foreach ($gatherings as $g) {
    $endDateTimeString = $g['date'] . ' ' . $g['endTime'];
    $endDateTime = new DateTime($endDateTimeString);

    error_log("[CheckGatheringStatus] Checking Gathering ID {$g['gatheringID']} | Gathering EndDateTime: " . $endDateTime->format('Y-m-d H:i:s') . " | Current Status: {$g['status']}");

    // Only update if the system time >= gathering end time, and status is not yet "ended"
    if ($now >= $endDateTime && $g['status'] !== 'ended') {
        error_log("[CheckGatheringStatus] Condition matched. Updating Gathering ID {$g['gatheringID']} to 'ended'");
        $dao->updateGatheringStatus($g['gatheringID'], 'ended');
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

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['updated' => $updated]);
