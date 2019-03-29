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

    /**
     * @param array $options
     *
     * @return bool
     */
    public static function start($options = [])
    {
        arrayValueDefault('session_options', $options, []);
        arrayValueDefault('start_active_time', $options, true);

        if (session_status() == PHP_SESSION_NONE) {
            $session = session_start($options['session_options']);

            // note("session has started");

            if ($options['start_active_time'] == true ||
                $options['start_active_time'] == 1) {
                // note("start_active_time");
                self::setActiveTime();
            }

            return $session;
        }

        /* session is already started
         * return true since the purpose
         * of the function is have a
         * session running
         */
        return true;
    }

    /**
     * @return bool
     */
    public static function destroy()
    {
        return session_destroy();
    }

    /**
     * @return bool
     */
    public static function end()
    {
        return self::destroy();
    }

    /**
     * @param string|array $name
     * @param mixed|null   $value
     *
     * @return mixed
     */
    public static function add($name, $value = null)
    {
        return self::set($name, $value);
    }

    /**
     * @param string|array $name
     * @param mixed|null   $value
     *
     * @return mixed
     */
    public static function set($name, $value = null)
    {
        if (!isset($value)) {
            $value = $name[1];
            $name = $name[0];
        }

        return $_SESSION[$name] = $value;
    }

    /**
     * @return void
     */
    public static function setActiveTime()
    {
        self::set('activeTime', time());
    }

    /**
     * @param \User\Model\User|string $user
     *
     * @return mixed
     */
    public static function setUser($user)
    {
        if ($user instanceof User) {
            $slug = $user->slug;
        } else {
            $slug = $user;
        }

        return self::set('userSlug', $slug);
    }

    /**
     * @param string $name
     */
    public static function remove($name)
    {
        $_SESSION[$name] = null;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    /**
     * @return \User\Model\User|bool
     */
    public static function getUser()
    {
        if (!self::isSet('userSlug')) {
            return false;
        }

        $table = (new Tables())->getTable('user');

        return $table->getUser(self::get('userSlug'));
    }

    /**
     * @return string
     */
    public static function getId()
    {
        return session_id();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function isSet($name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     * @return bool
     */
    public static function isActive()
    {
        self::start();
        //self::start(['start_active_time' => false]);
        if (self::isSet('activeTime')
            && self::isSet('userSlug')) {
            // note('both are set');
            // activeTime must be within the hour.
            if (self::get('activeTime') > (time() - 3600)) {
                // note('active');
                self::setActiveTime(); // update active time
                return true;
            }
        }
        // note('not_active');
        return false;
    }

    /**
     * @param \Privilege\Model\Privilege|string $privilege
     * @param \Group\Model\Group|string|null    $group
     *
     * @return bool
     */
    public static function hasPrivilege($privilege, $group = null)
    {
        $table = (new Tables())->getTable('userPrivileges');

        return $table->hasPrivilege(self::get('userSlug'), $privilege, $group);
    }

    /**
     * @return array
     */
    public static function getGroups()
    {
        $table = (new Tables())->getTable('userGroups');

        return $table->getGroups(self::get('userSlug'));
    }
}
