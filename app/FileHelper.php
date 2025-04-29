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
            'Profile' => "/Presentation/View/ProfileView/profile.php",
        ];

        $reflectionPaths = [
            'ReflectionList' => "/Presentation/View/ReflectionView/reflection-list.php",
            'CreateReflection' => "/Presentation/View/ReflectionView/create-reflection.php",
            'EditReflection' => "/Presentation/View/ReflectionView/edit-reflection.php",
            'DeleteReflection' => "/Presentation/View/ReflectionView/delete-reflection.php",
            'ViewReflection' => "/Presentation/View/ReflectionView/view-reflection.php",
        ];

        $gatheringPaths = [
            'MyGatheringList' => "/Presentation/View/GatheringView/my-gathering.php",
            'MyGatheringDetails' => "/Presentation/View/GatheringView/my-gathering-details.php",
            'CreateGathering' => "/Presentation/View/GatheringView/create-gathering.php",
            'SelectLocation' => "/Presentation/View/GatheringView/select-location.php",
            'JoinGathering' => "/Presentation/View/GatheringView/join-gathering.php",
            'GatheringDetail' => "/Presentation/View/GatheringView/gathering-detail.php",
            'GatheringList' => "/Presentation/View/GatheringView/gathering-list.php",
        ];

        $assetPaths = [
            "AppCSS" => "/css/app.css",
            "StylesCSS" => "/css/styles.css",
            "iconPNG" => "/asset/bubble.png",
            "match" => "/asset/Random.png",
            "dinner" => "/asset/dinnerpic.png",
            "map" => "/asset/map.png",
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
