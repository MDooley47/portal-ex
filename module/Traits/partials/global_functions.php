<?php

use Dotenv\Dotenv;
use Traits\Interfaces\HasSlug;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/*
 * Loads .env file.
 */
(new Dotenv(APPLICATION_PATH.'/'))->load();

/*
 * Adds the function dd if not already declared
 */
if (!function_exists('dd')) {
    /**
     * Dump and Die.
     *
     * @param mixed $data
     */
    function dd($data)
    {
        var_dump($data);
        die();
    }
}

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
 * Makes logging easier.
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
    $logger = new Logger();
    $logPath = APPLICATION_PATH.'/data/logs/';

    $logFileDebug = 'debug.log';
    $logFileInfo = 'informational.log';
    $logFileNotice = 'notice.log';
    $logFileWarn = 'warning.log';
    $logFileErr = 'error.log';
    $logFileCrit = 'critical.log';
    $logFileAlert = 'alert.log';
    $logFileEmerg = 'emergency.log';
    $logFileDefault = 'log.log';

    switch (strtolower($type)) {
        case 'debug':
            $logFile = $logPath.$logFileDebug;
            break;
        case 'info':case 'informational':
            $logFile = $logPath.$logFileInfo;
            break;
        case 'notice':
            $logFile = $logPath.$logFileNotice;
            break;
        case 'warn':case 'warning':
            $logFile = $logPath.$logFileWarn;
            break;
        case 'err':case 'error':
            $logFile = $logPath.$logFileErr;
            break;
        case 'crit':case 'critical':
            $logFile = $logPath.$logFileCrit;
            break;
        case 'alert':
            $logFile = $logPath.$logFileAlert;
            break;
        case 'emerg':case 'emergency':
            $logFile = $logPath.$logFileEmerg;
            break;
        default:
            $logFile = $logPath.$logFileDefault;
            break;
    }

    $writer = new Stream($logFile);
    $logger->addWriter($writer);

    if ($logFile !== $logPath.$logFileDefault) {
        $completeWriter = new Stream($logPath.$logFileDefault);
        $logger->addWriter($completeWriter);
    }

    return $logger;
}

/**
 * Makes logging super easy.
 *
 * @param string      $value
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
        case 'info':case 'informational':
        default:
            $logger->log(Logger::INFO, $value);
            break;
        case 'notice':
            $logger->log(Logger::NOTICE, $value);
            break;
        case 'warn':case 'warning':
            $logger->log(Logger::WARN, $value);
            break;
        case 'err':case 'error':
            $logger->log(Logger::ERR, $value);
            break;
        case 'crit':case 'critical':
            $logger->log(Logger::CRIT, $value);
            break;
        case 'alert':
            $logger->log(Logger::ALERT, $value);
            break;
        case 'emerg':case 'emergency':
            $logger->log(Logger::EMERG, $value);
            break;
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
        ($model instanceof ArrayObject && $model->offsetExists('slug'))) {
        return $model->slug;
    } else {
        return $model;
    }
}

/**
 * Get array value by key or set and return default value.
 *
 * @param string     $key
 * @param &array     &$search
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

/**
 * Guarantee that data is an array.
 *
 * Check if the data is an array.
 * If it is not, put the data into an array.
 *
 * @param mixed $data
 *
 * @return array
 */
function guaranteeArray(&$data)
{
    if (!is_array($data)) {
        $data = [$data];
    }

    return $data;
}

/**
 * If the datum is an array, it will return the value at the key.
 * Otherwise, if the datum is not an array, it will return the datum.
 *
 * @param string $key
 * @param mixed  $possibleArray
 *
 * @return mixed
 */
function schrodingerArrayValue($key, $possibleArray)
{
    if (is_array($possibleArray) && array_key_exists($key, $possibleArray)) {
        return $possibleArray[$key];
    } elseif (is_array($possibleArray)) {
        return;
    } else {
        return $possibleArray;
    }
}

/**
 * Get an array attribute with the given key and value set.
 *
 * @author laravel/framework
 *
 * @param mixed    $value
 * @param callable $callback
 *
 * @return mixed
 */
function tap($value, $callback)
{
    $callback($value);

    return $value;
}

/**
 * @param $haystack
 * @param $needles
 *
 * @return bool
 */
function StrContains($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
            return true;
        }
    }

    return false;
}

function databaseAdapter()
{
    return new Zend\Db\Adapter\Adapter([
        'driver'   => env('db_driver') ?? 'Pdo_Pgsql',
        'database' => env('db_name'),
        'hostname' => env('db_host'),
        'port'     => env('db_port') ?? 5432,
        'username' => env('db_username'),
        'password' => env('db_password'),
    ]);
}

function boot()
{
    \User\Model\User::boot();
    \App\Model\App::boot();
    \Attribute\Model\Attribute::boot();
    \Group\Model\Group::boot();
    \GroupType\Model\GroupType::boot();
    \IpAddress\Model\IpAddress::boot();
    \OwnerType\Model\OwnerType::boot();
    \Privilege\Model\Privilege::boot();
    \Setting\Model\Setting::boot();
    \Tab\Model\Tab::boot();
}

function castModel($table, array $attributes)
{
    $model = null;

    switch (strtolower($table)) {
        case 'app':case 'apps':
            $model = \App\Model\App::cast($attributes);
            break;
        case 'user':case 'users':
            $model = \User\Model\User::cast($attributes);
            break;
        case 'privilege':case 'privileges':
            $model = \Privilege\Model\Privilege::cast($attributes);
            break;
        case 'tab':case 'tabs':
            $model = \Tab\Model\Tab::cast($attributes);
            break;
        case 'group':case 'groups':
            $model = \Group\Model\Group::cast($attributes);
            break;
        default:
            throw new \Traits\Exceptions\CastException($table.' cast not created.');
    }

    return $model;
}

function resolveModel($model_name)
{
    switch (strtolower($model_name)) {
        case 'app':
            return \App\Model\App::class;
        case 'user':
            return \User\Model\User::class;
        case 'attribute':
            return \Attribute\Model\Attribute::class;
        case 'group':
            return \Group\Model\Group::class;
        case 'grouptype':
            return \GroupType\Model\GroupType::class;
        case 'ipaddress':
            return \IpAddress\Model\IpAddress::class;
        case 'ownertype':
            return \OwnerType\Model\OwnerType::class;
        case 'privilege':
            return \Privilege\Model\Privilege::class;
        case 'setting':
            return \Setting\Model\Setting::class;
        case 'tab':
            return \Tab\Model\Tab::class;
    }
}

function guaranteeUniversalTableGateway(\Zend\Db\TableGateway\AbstractTableGateway $gateway)
{
    if (!$gateway instanceof \Traits\Tables\UniversalTableGatewayInterface) {
        $gateway = new \SessionManager\TableModels\UniversalTableGatewayDecorator($gateway);
    }

    return $gateway;
}

function getModelsArray() {
    return [
        'attribute' => 'attributes',
        'app' => 'apps',
        'group' => 'groups',
        'grouptype' => 'grouptype',
        'ipaddress' => 'ipaddresses',
        'ownertype' => 'ownertypes',
        'privilege' => 'privileges',
        'tab' => 'tabs',
        'user' => 'users',
    ];
}

