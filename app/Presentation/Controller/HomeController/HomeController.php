<?php

//define('ROOTPATH', __DIR__);
require_once(ROOTPATH . '/fileRegister.php'); // The file that store the route

class HomeController
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
