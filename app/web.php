<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;

Route::get('/', [HomeController::class, 'home']);
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::get('/my-gathering', [HomeController::class, 'myGatheringHome']);
Route::get('/my-gathering/create', [GatheringController::class, 'viewCreate']);
Route::get('/my-gathering/create/location', [GatheringController::class, 'viewCreate']);
Route::post('/gathering/join/{userid}/{gatheringid}', [GatheringController::class, 'joinGathering']);
Route::get('/own/create', [GatheringController::class, 'viewCreate']);
Route::post('/gathering/leave', [GatheringController::class, 'leave']);


//AJAX
Route::get('/api/check-gathering-status', [GatheringController::class, 'checkGatheringStatus']);
