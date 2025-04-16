<?php

define('ROOTPATH', __DIR__);
//require_once '/fileRegister.php';
//require_once './_base.php';

require_once 'Presentation/Controller/HomeController/HomeController.php';
require_once 'Presentation/Controller/UserProfileController/UserProfileController.php';
require_once 'Presentation/Controller/SelfReflectionController/SelfReflectionController.php';
require_once 'Presentation/Controller/GatheringController/GatheringController.php';

//session_start();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/':
        include (new HomeController())->redirect('HomePage');
        break;
    case '/profile':
        break;
    case '/gathering':
        if (isset($_GET['action'])) {
            (new GatheringController())->action();
        } else {
            include (new GatheringController())->redirect('GatheringList');
        }
        break;
    case '/login':
        //include getFilePath('Login');
        break;
    case '/join-gathering':
        //include getFilePath('JoinGathering');
        break;
    case '/join-gathering-detail':
        //include getFilePath('JoinGatheringDetail');
        break;
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
