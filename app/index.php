<?php
require '_base.php';
require 'FileRegister.php';
// Load route map
$routes = include 'FileRegister.php';

// Parse current path
$requestUri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($requestUri, PHP_URL_PATH), '/');

// Default route if root
if ($path === '') {
    $path = 'home';
} else if ($path === 'gathering') {
    require_once 'Presentation/Controller/GatheringController.php';
    $controller = new GatheringController($gatheringModel);
    
    $action = $_GET['action'] ?? null;

    if ($action === 'view') {
        $path = 'gathering-detail';
        $routeFile = __DIR__ . $routes[$path];
        require $routeFile;
    } else if ($action === 'join') {
        $controller->joinGathering();
        $path = 'gathering-list';
        $routeFile = __DIR__ . $routes[$path];
        require $routeFile;
    } else {
        $path = 'gathering-list';
        $routeFile = __DIR__ . $routes[$path];
        require $routeFile;
    }
}




if (!isset($routes[$path])) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>No route for '$path'</p>";
    exit;
}
