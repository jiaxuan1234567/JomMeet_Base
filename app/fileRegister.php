<?php
define('ROOTPATH', __DIR__);

function getFilePath($key)
{
    $registered = [
        // index files
        "Header" => ROOTPATH . "/Presentation/View/HomeView/header.php",
        "Footer" => ROOTPATH . "/Presentation/View/HomeView/footer.php",

        // Home Components
        "HomeView" => ROOTPATH . "/Presentation/View/HomeView/HomeView.php",
        "HomeController" => ROOTPATH . "/Presentation/Controller/HomeController/HomeController.php",
        "HomeModel" => ROOTPATH . "/BusinessLogic/Model/HomeModel/HomeModel.php",
        "Login" => ROOTPATH . "/Presentation/View/HomeView/login.php",

        // UserProfile Components

        // SelfReflection Components

        // Gathering Components
        "JoinGathering" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering.php"
    ];

    return isset($registered[$key]) ? $registered[$key] : null;
}

function getRoutePath($key)
{
    $registered = [
        // index files
        "AppCSS" => "/Presentation/View/HomeView/css/app.css", // frontend file
        "StylesCSS" => "/Presentation/View/HomeView/css/styles.css", // frontend file
        "iconPNG" => "/Presentation/View/HomeView/images/bubble.png",

        // Home Components

        // UserProfile Components

        // SelfReflection Components

        // Gathering Components
    ];

    return isset($registered[$key]) ? $registered[$key] : null;
}
