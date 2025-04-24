<?php

class FileHelper
{
    private $allowedPaths = [];
    private $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
        $this->initializePaths();
    }

    private function initializePaths()
    {
        $basePaths = [
            'Header' => "/Presentation/View/HomeView/header.php",
            'Footer' => "/Presentation/View/HomeView/footer.php",
        ];

        $homePaths = [
            'HomePage' => "/Presentation/View/HomeView/home.php",
            'HomeController' => "/Presentation/Controller/HomeController/HomeController.php",
            'HomeModel' => "/BusinessLogic/Model/HomeModel/HomeModel.php",
            'Login' => "/Presentation/View/HomeView/login.php",
        ];

        $profilePaths = [
            'ProfileDetail' => "/Presentation/View/ProfileView/profile.php", // sample path
        ];

        $reflectionPaths = [
            'ReflectionList' => "/Presentation/View/ReflectionView/list.php", // sample path
        ];

        $gatheringPaths = [
            'MyGatheringList' => "/Presentation/View/GatheringView/my-gathering.php",
            'CreateGathering' => "/Presentation/View/GatheringView/create-gathering.php",
            'JoinGathering' => "/Presentation/View/GatheringView/join-gathering.php",
            'JoinGatheringDetail' => "/Presentation/View/GatheringView/join-gathering-detail.php",
            'GatheringList' => "/Presentation/View/GatheringView/gathering_list.php",
        ];

        $assetPaths = [
            "AppCSS" => "/Presentation/View/HomeView/css/app.css",
            "StylesCSS" => "/Presentation/View/HomeView/css/styles.css",
            "iconPNG" => "/Presentation/View/HomeView/images/bubble.png",
        ];

        switch ($this->permission) {
            case "home":
                $this->allowedPaths = array_merge(
                    $basePaths,
                    $homePaths,
                    $profilePaths,
                    $reflectionPaths,
                    $gatheringPaths
                );
                break;
            case "profile":
                $this->allowedPaths = $profilePaths;
                break;
            case "reflection":
                $this->allowedPaths = $reflectionPaths;
                break;
            case "gathering":
                $this->allowedPaths = $gatheringPaths;
                break;
            case "asset":
                $this->allowedPaths = $assetPaths;
                break;
        }
    }

    public function getFilePath($key)
    {
        return $this->permission === 'asset' ? $this->allowedPaths[$key] ?? null
            : ROOTPATH . $this->allowedPaths[$key] ?? null;
    }
}
