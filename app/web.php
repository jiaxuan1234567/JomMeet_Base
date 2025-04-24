<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;

Route::get('/', [HomeController::class, 'home']);
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering/View/{id}', [GatheringController::class, 'viewDetail']);
Route::get('/own', [GatheringController::class, 'myList']);
Route::post('/gathering/join', [GatheringController::class, 'join']);
Route::post('/gathering/leave', [GatheringController::class, 'leave']);
