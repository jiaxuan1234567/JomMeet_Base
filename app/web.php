<?php

use Presentation\Controller\HomeController\HomeController;
use Presentation\Controller\GatheringController\GatheringController;
use Presentation\Controller\ReflectionController\ReflectionController;
use Presentation\Controller\ProfileController\ProfileController;

// home routes
Route::get('/', [HomeController::class, 'home']);
Route::get('/profile', [HomeController::class, 'profileHome']);
Route::get('/reflection', [HomeController::class, 'reflectionHome']);
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/my-gathering', [HomeController::class, 'myGatheringHome']);

// profile routes


// self-reflection routes
Route::get('/reflection/create', [ReflectionController::class, 'createReflection']);

// gathering routes
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::post('/gathering/search', [GatheringController::class, 'searchGatherings']);
Route::post('/gathering/join', [GatheringController::class, 'joinGathering']);

// my-gathering routes
Route::get('/my-gathering/create', [GatheringController::class, 'viewCreate']);
Route::get('/my-gathering/create/location', [GatheringController::class, 'viewSelectLocation']);


//AJAX
Route::get('/api/check-gathering-status', [GatheringController::class, 'checkGatheringStatus']);
