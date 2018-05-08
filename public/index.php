<?php

use Dotenv\Dotenv;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

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
* From laravel/framework
* Gets the value of an environment variable. Supports boolean, empty and null.
*
* @param  string  $key
* @param  mixed   $default
* @return mixed
*/
function env($key, $default = null)
{
   $value = getenv($key);
   if ($value === false) {
       return $default;
   }
   switch (strtolower($value)) {
       case 'true':
       case '(true)':
           return true;
       case 'false':
       case '(false)':
           return false;
       case 'empty':
       case '(empty)':
           return '';
       case 'null':
       case '(null)':
           return;
   }

   return $value;
}

/**
 * Make a logging easy
 */
function logger($type)
{
    $logger = new Logger;

    switch (strtolower($type))
    {
        case 'debug':
            $writer = new Stream(APPLICATION_PATH . '/data/logs/debug.log');
            $logger->addWriter($writer);
            break;
        case 'info':
        default:
            $writer = new Stream(APPLICATION_PATH . '/data/logs/info.log');
            $logger->addWriter($writer);
    }

    return $logger;
}


function note($value, $type = null)
{
    if (! isset($type))
    {
        $type = (env('debug')) ? 'DEBUG' : 'INFO';
    }
    $logger = logger($type);

    switch (strtolower($type))
    {
        case 'debug':
            $logger->log(Logger::DEBUG, $value);
            break;
        case 'info':
        default:
            $logger->log(Logger::INFO, $value);
    }
}

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

function dd($data)
{
    var_dump($data);
    die();
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
