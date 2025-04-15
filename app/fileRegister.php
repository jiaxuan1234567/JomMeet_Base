<?php

function getFilePath($key)
{
    $registered = [
        // index files
        "Header" => ROOTPATH . "/Presentation/View/HomeView/_header.php",
        "Footer" => ROOTPATH . "/Presentation/View/HomeView/_footer.php",

        // Home Components
        "HomePage" => ROOTPATH . "/Presentation/View/HomeView/index.php",
        "HomeController" => ROOTPATH . "/Presentation/Controller/HomeController/HomeController.php",
        "HomeModel" => ROOTPATH . "/BusinessLogic/Model/HomeModel/HomeModel.php",
        "Login" => ROOTPATH . "/Presentation/View/HomeView/login.php",

        // UserProfile Components

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
