<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;

Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::post('/gathering/join/{userid}/{gatheringid}', [GatheringController::class, 'joinGathering']);
Route::get('/own/create', [GatheringController::class, 'viewCreate']);
Route::post('/gathering/leave', [GatheringController::class, 'leave']);
