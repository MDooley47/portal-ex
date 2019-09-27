<?php

/**
 *  Class Migration0000
 *
 * HOW TO USE MIGRATION
 *
 * First, connect to the docker container and launch the psysh shell.
 *      ./vendor/psy/psysh/bin/psysh
 * Then, include this file.
 *      include(__DIR__ . '/data/migrations/0000_2019_09_26.usergroups-to-userprivileges.php');
 * Finally, run the migrate method on a new instance of Migration0000.
 *      (new Migration0000())->migrate();
 */

class Migration0000 {
    private $pdo;
    private $pdo_dns;
    private $pdo_options;
    private $users;
    private $privileges;
    private $groups;

    public function __construct()
    {
        $this->pdo_dns = "pgsql:"
            . "dbname=" . env('db_name') . ";"
            . "host=" . env('db_host') . ";";
        $this->pdo_options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
        ];
        $this->pdo = new PDO($this->pdo_dns, env('db_username'), env('db_password'), $this->pdo_options);
    }

    public function safeQuery(Closure $try, Closure $catch = null, Closure $finally = null)
    {
        $output = [];

        try {
            $this->pdo->beginTransaction();
            $output['try'] = $try();
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            if (isset($catch))
                $output['catch'] = $catch();
            else return true;
        } finally {
            if (isset($finally))
                $output['finally'] = $finally();
        }
        return $output;
    }

    public function migrate()
    {
        if (! $this->fetchCurrentDB()) return false;
        else if (! $this->addSystemAccess()) return false;
        else if (! $this->addGroupAccess()) return false;
        else return true;
    }

    protected function fetchCurrentDB()
    {
        $sql = [
            'SELECT slug FROM users',
            'SELECT "userSlug", "groupSlug" FROM "userPrivileges"',
            'SELECT "userSlug", "groupSlug" FROM "userGroups"',
        ];

        // get the slugs of users
        $result = $this->safeQuery(function () use ($sql) {
            $query = $this->pdo->prepare($sql[0]);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_COLUMN);
        });
        // return false if rolled back
        if (isset($result['catch'])) {
            return false;
        }
        $this->users = $result['try'];
        $result = [];

        // get all the slugs associated with userPrivileges
        $result = $this->safeQuery(function () use ($sql) {
            $query = $this->pdo->prepare($sql[1]);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        });
        // return false if rolled back
        if (isset($result['catch'])) {
            return false;
        }
        $this->privileges = $result['try'];
        $result = [];

        // get group and user slugs from userGroups
        $result = $this->safeQuery(function () use ($sql) {
            $query = $this->pdo->prepare($sql[2]);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        });
        // return false if rolled back
        if (isset($result['catch'])) {
            return false;
        }
        $this->groups = $result['try'];
        $result = [];

        return true;
    }

    protected function addSystemAccess()
    {
        $users = $this->users;
        $privileges = $this->privileges;
        $sql = [
            'INSERT INTO "userPrivileges" VALUES (DEFAULT, :user, \'auth\', NULL)',
        ];

        // remove users from array that already have their system privilege set
        foreach ($privileges as $row) {
            if (empty($row['groupSlug'])) {
                $users = array_diff($users, [$row['userSlug']]);
            }
        }

        // add auth privilege for remaining users
        $result = $this->safeQuery(function () use ($sql, $users) {
            $query = $this->pdo->prepare($sql[0]);
            $output = [];
            foreach ($users as $user) {
                array_push($output, $query->execute(['user' => $user]));
            }
            return $output;
        });
        // return false if rolled back
        if (isset($result['catch'])) {
            return false;
        }

        return true;
    }

    protected function addGroupAccess() {
        $privileges = $this->privileges;
        $groups = $this->groups;
        $sql = [
            'INSERT INTO "userPrivileges" VALUES (DEFAULT, :user, \'auth\', :group)',
        ];

        // remove user group combos with existing privilege records
        $groups = self::arrayRecursiveDiff($groups, $privileges);

        // add auth privilege for remaining users
        $result = $this->safeQuery(function () use ($sql, $groups) {
            $query = $this->pdo->prepare($sql[0]);
            $output = [];
            foreach ($groups as $group) {
                if (empty($group['groupSlug'])) continue;
                array_push($output, $query->execute([
                    'user' => $group['userSlug'],
                    'group' => $group['groupSlug'],
                ]));
            }
            return $output;
        });
        // return false if rolled back
        if (isset($result['catch'])) {
            return false;
        }

        return true;
    }

    // https://stackoverflow.com/questions/3876435/recursive-array-diff
    private static function arrayRecursiveDiff($aArray1, $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = self::arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }
}
