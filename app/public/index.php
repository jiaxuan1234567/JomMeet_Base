<?php
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../Route.php';
require_once __DIR__ . '/../web.php';

session_start();

$_SESSION['profile']['profileID'] = 1;

Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
