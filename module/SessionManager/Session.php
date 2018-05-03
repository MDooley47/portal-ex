<?php

namespace SessionManager;

class Session
{
    public static function start($options = [])
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            $session = session_start($options);
            self::setActiveTime();
            return $session;
        }
    }

    public static function destroy()
    {
        return session_destroy();
    }

    public static function end()
    {
        return self::destroy();
    }

    public static function add($name, $value = NULL)
    {
        self::set($name, $value);
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

    public static function setActiveTime()
    {
        self::set('activeTime', time());
    }

    public static function setUser($user)
    {
        return self::set('userSlug', $user->slug);
    }

    public static function remove($name)
    {
        $_SESSION[$name] = null;
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function getUser($table)
    {
        if (! self::isSet('userSlug')) return false;

        return $table->getUser(self::get('userSlug'));
    }

    public static function getId()
    {
        return session_id();
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
