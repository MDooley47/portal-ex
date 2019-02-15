<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__));
require_once APPLICATION_PATH.'/vendor/autoload.php';

require_once APPLICATION_PATH.'/module/Traits/partials/global_functions.php';

// Retrieve configuration
$appConfig = require __DIR__.'/config/application.config.php';
if (file_exists(__DIR__.'/config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__.'/config/development.config.php');
}

// Make the application!
$application = Application::init($appConfig);