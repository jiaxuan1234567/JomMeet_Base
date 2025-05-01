#!/usr/bin/env php
<?php
require __DIR__ . '/../autoload.php';

use Presentation\Controller\GatheringController\GatheringController;
use BusinessLogic\Model\GatheringModel\GatheringModel;

date_default_timezone_set('Asia/Kuala_Lumpur');

set_time_limit(0);

$gatheringController = new GatheringController();
//$model = new GatheringModel();

while (true) {
    error_log('running...');
    // run your “close gatherings” logic
    //$model->checkAndCloseGatherings();
    $gatheringController->runGatheringJob();

    // wait before next run
    sleep(1);
}
