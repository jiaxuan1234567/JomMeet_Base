<?php
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../Route.php';
require_once __DIR__ . '/../web.php';

Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
