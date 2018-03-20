<?php

namespace SessionManager;

class Session
{
    public static function start($options = [])
    {
        return session_start($options);
    }

    public static function destroy()
    {
        return session_destroy();
    }

    public static function end()
    {
        return self::destroy();
    }

    public static function set($name, $value = NULL)
    {
        if (!isset($value))
        {
            $value = $name[1];
            $name = $name[0];
        }

        $_SESSION[$name] = $value;
    }

    public static function add($name, $value = NULL)
    {
        self::set($name, $value);
    }

    public static function remove($name)
    {
        $_SESSION[$name] = null;
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function isSet($name)
    {
        return isset($_SESSION[$name]);
    }

    public static function active()
    {
        self::start();

        if (self::isSet('activeTime')
            && self::isSet('userId'))
        {
            // activeTime must be within the hour.
            if ((self::get('activeTime') > (time() - 3600))
                && (self::get('userId') > 0))
            {
                return true;
            }
        }

        return false;
    }
}

?>
