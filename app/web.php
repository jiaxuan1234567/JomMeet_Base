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
Route::get('/login', [HomeController::class, 'loginHome']);
Route::post('/login/process', [ProfileController::class, 'validateLogin']);
Route::get('/logout', [HomeController::class, 'logoutHome']);

// profile routes
Route::get('/profile', [ProfileController::class, 'validateLogin']);
Route::get('/profile', [ProfileController::class, 'viewProfile']);
Route::get('/profile/edit', [ProfileController::class, 'editProfile']);
Route::post('/profile/edit', [ProfileController::class, 'saveProfile']);
Route::get('/profile/create', [ProfileController::class, 'createProfile']);
Route::post('/profile/create', [ProfileController::class, 'submitProfile']);

// self-reflection routes
Route::get('/reflection/create', [ReflectionController::class, 'createReflection']);
Route::post('/reflection/create', [ReflectionController::class, 'saveReflection']);
Route::get('/reflection/edit/{id}', [ReflectionController::class, 'editReflection']);
Route::post('/reflection/edit/{id}', [ReflectionController::class, 'editSaveReflection']);
Route::get('/reflection/delete/{id}', [ReflectionController::class, 'deleteReflection']);
Route::get('/reflection/view/{id}', [ReflectionController::class, 'viewReflection']);

// gathering routes
Route::get('/gathering', [HomeController::class, 'gatheringHome']);
Route::get('/gathering2', [GatheringController::class, 'gatheringPager']); // -> experimental
Route::get('/gathering/view/{gatheringId}', [GatheringController::class, 'viewDetail']);
Route::post('/gathering/search', [GatheringController::class, 'searchGatherings']);
Route::post('/gathering/join', [GatheringController::class, 'joinGathering']);
Route::get('/gathering/match/{userid}', [GatheringController::class, 'matchGathering']);

// my-gathering routes
Route::get('/my-gathering/view/{id}', [GatheringController::class, 'viewMyGatheringDetail']);
Route::get('/my-gathering/create', [GatheringController::class, 'viewCreate']);
Route::post('/my-gathering/create', [GatheringController::class, 'createGathering']);
Route::get('/my-gathering/create/location', [GatheringController::class, 'viewSelectLocation']);
// before you dispatch
Route::post('/my-gathering/create/location', [GatheringController::class, 'selectLocationSubmit']);
Route::post('/my-gathering/leave/{gatheringId}', [GatheringController::class, 'leaveGathering']);
Route::post('/my-gathering/cancel/{id}', [GatheringController::class, 'cancelGathering']);

//AJAX
Route::get('/api/savedLocations', [GatheringController::class, 'apiSavedLocations']);

// AJAX Validation
Route::post('/api/validate-profile', [ProfileController::class, 'validateProfile']);
Route::post('/api/validate-reflection', [ReflectionController::class, 'validateReflection']);
Route::post('/api/validate-gathering', [GatheringController::class, 'ajaxValidateGathering']);

// helper route to save location (need delete in future)
Route::post('/gathering/location/save', [GatheringController::class, 'saveLocation']);
