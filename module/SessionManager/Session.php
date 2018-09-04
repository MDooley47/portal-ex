<?php

namespace SessionManager;

use User\Model\User;

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
        arrayValueDefault('session_options', $options, []);
        arrayValueDefault('start_active_time', $options, true);

        //$options['start_active_time'] = true;

//        var_dump($options);

        if (session_status() == PHP_SESSION_NONE) {
            $session = session_start($options['session_options']);

            //debug_print_backtrace();
            // dd(self::end());

            note('session has started');
            //dd($options);
            //dd($options['start_active_time']);
            //dd($options['start_active_time'] == true);

            if ($options['start_active_time'] == true ||
                $options['start_active_time'] == 1) {
                note('start_active_time');
                self::setActiveTime();
            }

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

    public static function add($name, $value = null)
    {
        self::set($name, $value);
    }

    public static function set($name, $value = null)
    {
        if (!isset($value)) {
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
        if ($user instanceof User) {
            $slug = $user->slug;
        } else {
            $slug = $user;
        }

        return self::set('userSlug', $slug);
    }

    public static function remove($name)
    {
        $_SESSION[$name] = null;
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function getUser()
    {
        if (!self::isSet('userSlug')) {
            return false;
        }

        $table = (new Tables())->getTable('user');

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

    public static function isActive(): bool
    {
        self::start();
        //self::start(['start_active_time' => false]);

        if (self::isSet('activeTime')
            && self::isSet('userSlug')) {
            note('both are set');
            // activeTime must be within the hour.
            if (self::get('activeTime') > (time() - 3600)) {
                note('active');
                self::setActiveTime(); // update active time
                return true;
            }
        }
        note('not_active');

        return false;
    }

    public static function hasPrivilege($privilege, $group = null): bool
    {
        $table = (new Tables())->getTable('userPrivileges');

        return $table->hasPrivilege(self::get('userSlug'), $privilege, $group);
    }

    public static function getGroups()
    {
        $table = (new Tables())->getTable('userGroups');

        return $table->getGroups(self::get('userSlug'));
    }
}
