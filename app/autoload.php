<?php
// Autoload Core app with composer
require __DIR__ . '/../vendor/autoload.php';

// Autoload Models
spl_autoload_register(function($class_name){
    require "../app/models/" . ucwords($class_name) . ".php";
});


// Load my helper functions
require_once 'helpers/special_func.php';
require_once 'core/config.php';
