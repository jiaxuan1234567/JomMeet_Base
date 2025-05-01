<?php

declare(strict_types=1);

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
Route::post('/reflection/create', [ReflectionController::class, 'saveReflection']);
Route::post('/reflection/validate', [ReflectionController::class, 'validateReflection']);
Route::get('/reflection/edit/{id}', [ReflectionController::class, 'editReflection']);
Route::post('/reflection/edit/{id}', [ReflectionController::class, 'editSaveReflection']);
Route::get('/reflection/delete/{id}', [ReflectionController::class, 'deleteReflection']);
Route::get('/reflection/view/{id}', [ReflectionController::class, 'viewReflection']);

// gathering routes
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering2', [GatheringController::class, 'gatheringPager']); // -> experimental
Route::get('/gathering/view/{id}', [GatheringController::class, 'viewDetail']);
Route::post('/gathering/search', [GatheringController::class, 'searchGatherings']);
Route::post('/gathering/join', [GatheringController::class, 'joinGathering']);

// my-gathering routes
//Route::get('/my-gathering/view/{id}', [GatheringController::class, 'viewGathering']);
Route::get('/my-gathering/create', [GatheringController::class, 'viewCreate']);
Route::post('/my-gathering/create', [GatheringController::class, 'createGathering']);
Route::get('/my-gathering/create/location', [GatheringController::class, 'viewSelectLocation']);
// before you dispatch
Route::post('/my-gathering/create/location', [GatheringController::class, 'selectLocationSubmit']);

//AJAX
Route::get('/api/check-gathering-status', [GatheringController::class, 'checkGatheringStatus']);
Route::get('/api/savedLocations', [GatheringController::class, 'apiSavedLocations']);

// helper route to save location (need delete in future)
Route::post('/gathering/location/save', [GatheringController::class, 'saveLocation']);
