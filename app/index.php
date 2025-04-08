<?php
require_once './fileRegister.php';
require_once './_base.php';

//session_start();

require_once getFilePath("HomeController");
require_once getFilePath("HomeView");
// require_once __DIR__ . '/Presentation/controller/UserProfileController/UserProfileController.php';
// require_once __DIR__ . '/Presentation/controller/selfreflectionController/selfreflectionController.php';
// require_once __DIR__ . '/Presentation/controller/gatheringController/gatheringController.php';

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/':
        if (is_get()) {
            echo get('abc');
            $view = new HomeView();
            $view->index();
        }

        break;
    case '/profile':
        break;
    // case '/profile':
    //     $controller = new UserProfileController();
    //     $controller->viewProfile();
    //     break;
    // case '/selfreflection':
    //     $controller = new selfreflectionController();
    //     $controller->listReflections();
    //     break;
    // case '/gathering':
    //     $controller = new gatheringController();
    //     $controller->listGatherings();
    //     break;
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
