<?php

$_ASSET = [];

$_INDEX = [
    "Header" => ROOTPATH . "/Presentation/View/HomeView/header.php",
    "Footer" => ROOTPATH . "/Presentation/View/HomeView/footer.php",
];

$_HOME = [
    "HomePage" => ROOTPATH . "/Presentation/View/HomeView/index.php",
    "HomeController" => ROOTPATH . "/Presentation/Controller/HomeController/HomeController.php",
    "HomeModel" => ROOTPATH . "/BusinessLogic/Model/HomeModel/HomeModel.php",
    "Login" => ROOTPATH . "/Presentation/View/HomeView/login.php",
];

$_PROFILE = [];

$_REFLECTION = [];

$_GATHERING = [
    "JoinGathering" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering.php",
    "JoinGatheringDetail" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering-detail.php"
];


function getFilePath($permission)
{
    global $_INDEX,
        $_HOME,
        $_PROFILE,
        $_REFLECTION,
        $_GATHERING;

    $registered = [];

    switch ($permission) {
        case "home":
            $registered += $_INDEX
                + $_HOME
                + $_PROFILE
                + $_REFLECTION
                + $_GATHERING;
            break;
        case "profile":
            $registered += $_PROFILE;
            break;
        case "reflection":
            $registered += $_REFLECTION;
            break;
        case "gathering":
            $registered += $_GATHERING;
            break;
    }

    return isset($registered) ? $registered : null;
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
