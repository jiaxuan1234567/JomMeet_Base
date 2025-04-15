<?php
define('ROOTPATH', __DIR__);
require_once ROOTPATH . '/fileRegister.php';
require_once './_base.php';

//session_start();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/':
        include getFilePath('HomePage');
        break;
    case '/profile':
        break;
    case '/gathering':
        require_once getFilePath("GatheringController");
        $controller = new GatheringController();
        if (isset($_GET['action'])) {
            $controller->action();
        } else {
            include getFilePath('GatheringList');
        }
        break;
    case '/login':
        include getFilePath('Login');
        break;
    case '/join-gathering':
        include getFilePath('JoinGathering');
        break;
    case '/join-gathering-detail':
        include getFilePath('JoinGatheringDetail');
        break;
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
