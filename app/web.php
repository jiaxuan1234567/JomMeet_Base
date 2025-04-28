<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;

// home routes
Route::get('/', [HomeController::class, 'home']);

// profile routes


// self-reflection routes


// gathering routes
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::post('/gathering/search', [GatheringController::class, 'searchGatherings']);

Route::post('/gathering/join', [GatheringController::class, 'joinGathering']);

// my-gathering routes
Route::get('/my-gathering', [HomeController::class, 'myGatheringHome']);
Route::get('/my-gathering/create', [GatheringController::class, 'viewCreate']);
Route::get('/my-gathering/create/location', [GatheringController::class, 'viewSelectLocation']);


//AJAX
Route::get('/api/check-gathering-status', [GatheringController::class, 'checkGatheringStatus']);
