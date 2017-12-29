<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;
use Dotenv\Dotenv;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__DIR__) . ''));


/**
 * Run composer autoload.
 * Loads .env file
 */
require __DIR__ . '/../vendor/autoload.php';
(new Dotenv(__DIR__ . "/../"))->load();

/**
 * Global function for removing the base path.
 *
 * Takes in a path and removes realpath(getenv('storage_path')).
 *
 * @param String $data
 * @return String
 */
function removeBasePath($data)
{
    return str_replace(realpath(getenv('storage_path')), '', $data);
}

/**
 * Global function for adding the base path.
 *
 * Takes in a path and prepends realpath(getenv('storage_path')).
 *
 * @param String $data
 * @return String
 */
function addBasePath($data)
{
    return realpath(getenv('storage_path')) . $data;
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

if (! class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        . "- Type `composer install` if you are developing locally.\n"
        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
    );
}

// Retrieve configuration
$appConfig = require __DIR__ . '/../config/application.config.php';
if (file_exists(__DIR__ . '/../config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/../config/development.config.php');
}

// Run the application!
Application::init($appConfig)->run();
