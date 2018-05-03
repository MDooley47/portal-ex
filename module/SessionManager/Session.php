<?php

namespace SessionManager;

use SessionManager\TableModels\UserPrivilegesTableGateway;

class Session
{
    /* Custom session variables used.
     *
     *  activeTime:
     *      The time at which a session became active
     *
     *  userSlug:
     *      The slug of the active user
     */

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

    public static function isActive()
    {
        self::start();

        if (self::isSet('activeTime')
            && self::isSet('userSlug'))
        {
            // activeTime must be within the hour.
            if (self::get('activeTime') > (time() - 3600))
            {
                return true;
            }
        }

        return false;
    }

    public static function hasPrivilege($privilege, $group = null)
    {
        $table = new UserPrivilegesTableGateway();
        return $table->hasPrivilege(self::get('userSlug'), $privilege, $group);
    }
}

?>
