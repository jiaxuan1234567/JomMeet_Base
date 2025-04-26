<?php
require_once __DIR__ . '/autoload.php';
require_once 'Route.php';
require_once 'web.php';

Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
