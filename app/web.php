<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;

Route::get('/', [HomeController::class, 'home']);
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::get('/own', [HomeController::class, 'myGatheringHome']);
Route::get('/own/create', [GatheringController::class, 'viewCreate']);
Route::get('/own/create/location', [GatheringController::class, 'viewCreate']);
Route::post('/gathering/join/{userid}/{gatheringid}', [GatheringController::class, 'joinGathering']);
Route::post('/gathering/leave', [GatheringController::class, 'leave']);
