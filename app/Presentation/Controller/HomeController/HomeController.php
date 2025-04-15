<?php
define('ROOTPATH', __DIR__);
require('fileResgister.php'); // The file that store the route
require_once __DIR__ . '/../../../BusinessLogic/model/homeModel/homeModel.php';
require_once __DIR__ . '/../../../Presentation/view/homeView/homeView.php';

class homeController
{

    private $path;

    public function __construct()
    {
        $this->path = getFilePath("home");
    }

    public function redirect($key)
    {
        return $this->path[$key];
    }
}
