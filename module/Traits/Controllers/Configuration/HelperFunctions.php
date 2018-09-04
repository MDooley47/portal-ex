<?php

namespace Traits\Controllers\Configuration;

use PDO;

trait HelperFunctions
{
    public static function database($options = [])
    {
        switch (strtolower($options['action'])) {
            case 'test_connection':
                try {
                    self::database_getPDO($options['database']);

                    return [
                        'status' => true,
                    ];
                } catch (PDOException $e) {
                    die($e);

                    return [
                        'status'  => false,
                        'message' => $e->getMessage(),
                    ];
                }
                break;
            case 'try_connection':
                try {
                    return self::database_getPDO(null);
                } catch (\PDOException $e) {
                    echo $e;
                }
                break;
            case 'tables_exist':
                $db = self::database(['action' => 'try_connection']);

                return self::database_tableExist($db, $options['table']);
                break;
        }
    }

    public static function database_getPDO($database)
    {
        if ($database === null) {
            $database = [
                'host'     => env('db_host'),
                'name'     => env('db_name'),
                'username' => env('db_username'),
                'password' => env('db_password'),

            ];
        }

        if (isset($database['name'])) {
            $db = new PDO(
                'pgsql:host='
                .$database['host']
                .';dbname='
                .$database['name'],
                $database['username'],
                $database['password']
            );
        } else {
            $db = new PDO(
                'pgsql:host='.$database['host'],
                $database['username'],
                $database['password']
            );
        }

        return $db;
    }

    public static function database_tableExist($db, $table)
    {
        if (!isset($db)) {
            return false;
        }

        return $db->query("SELECT * FROM \"$table\" LIMIT 1") != false;
    }

    public static function internet($options = [])
    {
        switch (strtolower($options['action'])) {
            case 'test_connection':
                return self::internet_isConnected();
                break;
            case 'get_external_ip':
                return self::internet_getIP();
                break;
        }
    }

    public static function internet_isConnected($options = ['uri' => 'google.com', 'port' => 80])
    {
        $connected = @fsockopen($options['uri'], $options['port']);
        if ($connected) {
            fclose($connected);

            return true;
        }

        return false;
    }

    public static function internet_getIP($options = ['type' => 'external'])
    {
        switch (strtolower($options['type'])) {
            case 'external':
                return curl_exec(curl_init('ipecho.net/plain'));
                break;
        }
    }

    public static function envWithError($property, $options = [])
    {
        $val = env($property);

        return (!isset($val)) ? 'ERROR: Cannot find value: '.$property : $val;
    }
}
