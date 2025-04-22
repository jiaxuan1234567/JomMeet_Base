<?php
// Autoload or manual includes
require_once __DIR__ . '/Persistence/DAO/Database.php';
require_once __DIR__ . '/Persistence/DAO/GatheringDAO.php';
require_once __DIR__ . '/Business/Model/GatheringModel.php';
require_once __DIR__ . '/Presentation/Controller/GatheringController.php';
include 'FileRegister.php';
include  '_base.php';

// Instantiate dependencies
$db = new Database();
$gatheringDAO = new GatheringDAO($db);
$gatheringModel = new GatheringModel($gatheringDAO);
$controller = new GatheringController($gatheringModel);

$routes = include __DIR__ . '/FileRegister.php';
$requestUri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($requestUri, PHP_URL_PATH), '/');

if ($path === '') {
    $path = getLinks('join-gathering');
}

if (isset($routes[$path])) {
    require __DIR__ . $routes[$path];
} else {
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>No route for '$path'</p>";
}


// Simple Router Logic
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'view':
        $gathering = $controller->viewGathering($id);
        require __DIR__ . '/Presentation/View/GatheringView/gathering-detail.php';
        break;

    case 'list':
    default:
        $gatherings = $controller->listGatherings();
        require __DIR__ . '/Presentation/View/GatheringView/gathering-list.php';
        break;
}
