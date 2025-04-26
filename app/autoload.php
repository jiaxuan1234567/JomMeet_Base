<?php
define('ROOTPATH', __DIR__);

spl_autoload_register(function ($className) {
    $baseDir = ROOTPATH . DIRECTORY_SEPARATOR;
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $fullPath = $baseDir . $classPath . '.php';

    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        // Optional: log or throw error
        // echo "Autoload failed for: $fullPath";
    }
});
