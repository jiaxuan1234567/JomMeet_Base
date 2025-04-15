<?php

$_ASSET = [];

$_INDEX = [
    "Header" => ROOTPATH . "/Presentation/View/HomeView/header.php",
    "Footer" => ROOTPATH . "/Presentation/View/HomeView/footer.php",
];

$_HOME = [
    "HomeView" => ROOTPATH . "/Presentation/View/HomeView/HomeView.php",
    "HomeController" => ROOTPATH . "/Presentation/Controller/HomeController/HomeController.php",
    "HomeModel" => ROOTPATH . "/BusinessLogic/Model/HomeModel/HomeModel.php",
    "Login" => ROOTPATH . "/Presentation/View/HomeView/login.php",
];

$_GATHERING = [
    "JoinGathering" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering.php",
    "JoinGatheringDetail" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering-detail.php"
];


function getFilePath($permission)
{
    global $_HOME;
    global $_GATHERING;

        // Home Components
        "HomePage" => ROOTPATH . "/Presentation/View/HomeView/index.php",
        "HomeController" => ROOTPATH . "/Presentation/Controller/HomeController/HomeController.php",
        "HomeModel" => ROOTPATH . "/BusinessLogic/Model/HomeModel/HomeModel.php",
        "Login" => ROOTPATH . "/Presentation/View/HomeView/login.php",

    if ($permission == "home") {
        $registered += $_HOME;
        $registered += $_GATHERING;
    }

        // SelfReflection Components

        // Gathering Components
        "JoinGathering" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering.php",
        "JoinGatheringDetail" => ROOTPATH . "/Presentation/View/GatheringView/join-gathering-detail.php",

        // Test Architecture Components
        "GatheringController" => ROOTPATH . "/Presentation/Controller/GatheringController/GatheringController.php",
        "GatheringModel" => ROOTPATH . "/BusinessLogic/Model/GatheringModel/GatheringModel.php",
        "GatheringDAO" => ROOTPATH . "/Persistence/DAO/GatheringDAO/GatheringDAO.php",
        "GatheringList" => ROOTPATH . "/Presentation/View/GatheringView/gathering_list.php",
        "GatheringDetail" => ROOTPATH . "/Presentation/View/GatheringView/gathering_detail.php",

        "Database" => ROOTPATH . "/Database.php"
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
