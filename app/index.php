<?php
require '_base.php';
// Load route map
$routes = include 'FileRegister.php';

// Parse current path
$requestUri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($requestUri, PHP_URL_PATH), '/');

// Default route if root
if ($path === '') {
    $path = 'home';
} else if ($path === 'gathering') {
    $path = 'join-gathering';
}

if (!isset($routes[$path])) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>No route for '$path'</p>";
    exit;
}

// Route to correct file
$routeFile = __DIR__ . $routes[$path];



// {GATHERING}
//Instantiate controller
require_once __DIR__ . '/Presentation/Controller/GatheringController.php';
$controller = new GatheringController($gatheringModel);


// Handle action parameter if needed
$action = $_GET['action'] ?? null;



if (is_get()) {
    $gatheringid = req('id');

    // You can optionally check for specific actions here:
    if ($action === 'view' && $path === 'join-gathering') {
        $gathering = $controller->viewGathering($gatheringid);
        require __DIR__ . '/Presentation/View/GatheringView/gathering-detail.php';
    } else {
        $gatherings = $controller->listGatherings();
        require $routeFile;
    }
}

