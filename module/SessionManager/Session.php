<?php

namespace SessionManager;

use User\Model\User;

class Session
{
    /* Custom session variables used.
     *
     *  active_time:
     *      The time at which a session became active
     *
     *  user_slug:
     *      The slug of the active user
     */

    public static function init()
    {
        if (!self::isActive()) {
            self::end();
        }

        return self::start();
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public static function start($options = [])
    {
        arrayValueDefault('session_options', $options, []);
        arrayValueDefault('start_active_time', $options, true);

        if (session_status() === PHP_SESSION_NONE) {
            $session = session_start($options['session_options']);

            if ($options['start_active_time'] == true) {
                self::setActiveTime();
            }

            return $session;
        }

        /* session is already started
         * return true since the purpose
         * of the function is have a
         * session running
         */
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @return bool
     */
    public static function destroy()
    {
        session_unset();

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
        self::set('active_time', time());
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
            $user = self::table('user')->get($slug);
        }

        self::set('user_slug', $slug);

        return $user;
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
        if (!self::isSet('user_slug')) {
            return false;
        }

        $table = (new Tables())->getTable('user');

        return $table->getUser(self::get('user_slug'));
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
        //self::start();
        self::start(['start_active_time' => false]);

        note('`isActive()` session has started');

        return
            self::isSet('active_time')
            &&
            self::get('active_time') > (time() - 3600);
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

        return $table->hasPrivilege(self::get('user_slug'), $privilege, $group);
    }

    /**
     * @return array
     */
    public static function getGroups()
    {
        $table = (new Tables())->getTable('userGroups');

        return $table->getGroups(self::get('user_slug'));
    }

    public static function table($name = null)
    {
        $tables = new Tables();

        if ($name === null) {
            return $tables;
        } else {
            return $tables->getTable($name);
        }
    }
}
