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

    private function prefixWithRoot($paths)
    {
        foreach ($paths as $key => &$path) {
            $path = ROOTPATH . $path;
        }
        return $paths;
    }

    private function initializePaths()
    {
        $basePaths = $this->prefixWithRoot([
            'Header' => "/Presentation/View/HomeView/header.php",
            'Footer' => "/Presentation/View/HomeView/footer.php",
        ]);

        $homePaths = $this->prefixWithRoot([
            'HomePage' => "/Presentation/View/HomeView/home.php",
            'HomeController' => "/Presentation/Controller/HomeController/HomeController.php",
            'HomeModel' => "/BusinessLogic/Model/HomeModel/HomeModel.php",
            'Login' => "/Presentation/View/HomeView/login.php",
        ]);

        $profilePaths = $this->prefixWithRoot([
            'Profile' => "/Presentation/View/ProfileView/profile.php",
            'EditProfile' => "/Presentation/View/ProfileView/edit-profile.php",
            'CreateProfile' => "/Presentation/View/ProfileView/create-profile.php",
        ]);

        $reflectionPaths = $this->prefixWithRoot([
            'ReflectionList' => "/Presentation/View/ReflectionView/reflection-list.php",
            'CreateReflection' => "/Presentation/View/ReflectionView/create-reflection.php",
            'EditReflection' => "/Presentation/View/ReflectionView/edit-reflection.php",
            'DeleteReflection' => "/Presentation/View/ReflectionView/delete-reflection.php",
            'ViewReflection' => "/Presentation/View/ReflectionView/view-reflection.php",
        ]);

        $gatheringPaths = $this->prefixWithRoot([
            'MyGatheringList' => "/Presentation/View/GatheringView/my-gathering.php",
            'MyGatheringDetails' => "/Presentation/View/GatheringView/my-gathering-details.php",
            'LocationFeedback' => "/Presentation/View/GatheringView/location-feedback.php",
            'GatheringFeedback' => "/Presentation/View/GatheringView/gathering-feedback.php",

            'CreateGathering' => "/Presentation/View/GatheringView/create-gathering.php",
            'SelectLocation' => "/Presentation/View/GatheringView/select-location.php",
            'EditGathering' => "/Presentation/View/GatheringView/edit-gathering.php",
            'JoinGathering' => "/Presentation/View/GatheringView/join-gathering.php",
            'GatheringDetail' => "/Presentation/View/GatheringView/gathering-detail.php",
            'GatheringList' => "/Presentation/View/GatheringView/gathering-list.php",
        ]);

        $assetPaths = [
            "AppCSS" => "/css/app.css",
            "StylesCSS" => "/css/styles.css",
            "iconPNG" => "/asset/bubble.png",
            "match" => "/asset/Random.png",
            "dinner" => "/asset/dinnerpic.png",
            "defaultTag" => "/asset/default-tag.png",
            "map" => "/asset/map.png",
            "food" => "/asset/eat.png",
            "chill" => "/asset/chill.png",
            "study" => "/asset/study.png",
            "natural" => "/asset/natural.png",
            "shopping" => "/asset/shopping.png",
            "workout" => "/asset/gym.png",
            "entertainment" => "/asset/game.png",
            "music" => "/asset/music.png",
            "movie" => "/asset/movie.png",
        ];

        switch ($this->permission) {
            case "home":
                $this->allowedPaths = array_merge(
                    $basePaths,
                    $homePaths,
                    $profilePaths,
                    $reflectionPaths,
                    $gatheringPaths,
                    $assetPaths
                );
                break;
            case "profile":
                $this->allowedPaths = array_merge(
                    $profilePaths,
                    $assetPaths
                );
                break;
            case "reflection":
                $this->allowedPaths = array_merge(
                    $reflectionPaths,
                    $assetPaths
                );
                break;
            case "gathering":
                $this->allowedPaths = array_merge(
                    $gatheringPaths,
                    $assetPaths
                );
                break;
            case "asset":
                $this->allowedPaths = array_merge(
                    $assetPaths
                );
                break;
        }
    }

    public function getFilePath($key)
    {
        return $this->allowedPaths[$key] ?? null;
    }
}
