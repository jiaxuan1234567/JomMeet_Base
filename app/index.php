<?php
// Load route map
$routes = include 'FileRegister.php';

// Parse current path
$requestUri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($requestUri, PHP_URL_PATH), '/');

// Default route if root
if ($path === '') {
    $path = 'home';
}else if($path === 'gathering'){
    $path = 'join-gathering';
}

if (!isset($routes[$path])) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>No route for '$path'</p>";
    exit;
}

// Instantiate dependencies
require_once __DIR__ . '/Persistence/DAO/Database.php';
require_once __DIR__ . '/Persistence/DAO/GatheringDAO.php';
require_once __DIR__ . '/Business/Model/GatheringModel.php';
require_once __DIR__ . '/Presentation/Controller/GatheringController.php';

$db = new Database();
$gatheringDAO = new GatheringDAO($db);
$gatheringModel = new GatheringModel($gatheringDAO);
$controller = new GatheringController($gatheringModel);

// Handle action parameter if needed
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

// Route to correct file
$routeFile = __DIR__ . $routes[$path];

// You can optionally check for specific actions here:
if ($action === 'view' && $path === 'join-gathering') {
    $gathering = $controller->viewGathering($id);
    require __DIR__ . '/Presentation/View/GatheringView/gathering-detail.php';
} else {
    $gatherings = $controller->listGatherings();
    require $routeFile;
}
