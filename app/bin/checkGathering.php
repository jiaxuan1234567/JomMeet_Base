<?php
require __DIR__ . '/../autoload.php';

use Presentation\Controller\GatheringController\GatheringController;

date_default_timezone_set('Asia/Kuala_Lumpur');

set_time_limit(0);

$gatheringController = new GatheringController();

while (true) {
    error_log('running...');
    $gatheringController->runGatheringJob();

    // run on every 30 seconds
    sleep(30);
}
