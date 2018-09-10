<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__DIR__).''));

require_once __DIR__.'/../vendor/autoload.php';

require_once APPLICATION_PATH.'/module/Traits/partials/global_functions.php';

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

if (!class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        ."- Type `composer install` if you are developing locally.\n"
        ."- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        ."- Type `docker-compose run zf composer install` if you are using Docker.\n"
    );
}

// Retrieve configuration
$appConfig = require __DIR__.'/../config/application.config.php';
if (file_exists(__DIR__.'/../config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__.'/../config/development.config.php');
}

boot();

// Run the application!
Application::init($appConfig)->run();
