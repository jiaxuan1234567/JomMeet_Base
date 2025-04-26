<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;

Route::get('gathering', [GatheringController::class, 'list']);
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::get('/own/create', [GatheringController::class, 'viewCreate']);
Route::post('/gathering/join', [GatheringController::class, 'join']);
Route::post('/gathering/leave', [GatheringController::class, 'leave']);
