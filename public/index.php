<?php

use Dotenv\Dotenv;
use Traits\Interfaces\HasSlug;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

/*
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__DIR__).''));

/**
 * Run composer autoload.
 * Loads .env file.
 */
require __DIR__.'/../vendor/autoload.php';
(new Dotenv(__DIR__.'/../'))->load();

/**
 * From laravel/framework
 * Gets the value of an environment variable. Supports boolean, empty and null.
 *
 * @param string $key
 * @param mixed  $default
 *
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
 * Makes logging easier
 *
 * Modify this method to change log files or logging methods
 * the note method will use this method to automatically
 * grab the correct Logger.
 *
 * By default it is all done in the same file.
 *
 * @param string $type
 *
 * @return Logger */
function logger($type)
{
    $logger = new Logger;
    $logPath = APPLICATION_PATH . '/data/logs/';

    $logFileInfo = $logFileDebug = $logFileDefault = 'log.log';

    switch (strtolower($type)) {
        case 'debug':
            $logFile = $logPath . $logFileDebug;
            break;
        case 'info':
            $logFile = $logPath . $logFileInfo;
            break;
        default:
            $logFile = $logPath . $logFileDefault;
            break;
    }

    $writer = new Stream($logFile);
    $logger->addWriter($writer);

    return $logger;
}

/**
 * Makes logging super easy
 *
 * @param string $value
 * @param string|null $type
 */
function note($value, $type = null)
{
    if (!isset($type)) {
        $type = (env('debug')) ? 'DEBUG' : 'INFO';
    }

    $logger = logger($type);

    switch (strtolower($type)) {
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
 * @param string $data
 *
 * @return string
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
 * @param string $data
 *
 * @return string
 */
function addBasePath($data)
{
    return realpath(getenv('storage_path')).$data;
}

/**
 * @param \Traits\Interfaces\HasSlug|object(ArrayObject)|ArrayObject|string $model
 *
 * @return string
 */
function getSlug($model)
{
    if (($model instanceof HasSlug) ||
        ($model instanceof ArrayObject && $model->offsetExists('slug')))
    {
        return $model->slug;
    }
    else {
        return $model;
    }
}

/**
 * Dump and Die
 *
 * @param mixed $data
 */
function dd($data)
{
    var_dump($data);
    die();
}

/**
 * Get array value by key or set and return default value
 *
 * @param string $key
 * @param &array &$search
 * @param mixed|null $default
 *
 * @return mixed
 */
function arrayValueDefault($key, &$search, $default = null)
{
    if (!array_key_exists($key, $search)) {
        $search[$key] = $default;
    }

    return $search[$key];
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Composer autoloading
include __DIR__.'/../vendor/autoload.php';

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

// Run the application!
Application::init($appConfig)->run();
