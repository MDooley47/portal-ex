<?php

namespace SessionManager;

use Group\Model\Group;
use Tab\Model\Tab;

class loadDB
{
    public static function makeTabsForGroupsWithoutTabs()
    {
        $tables = new Tables();

        foreach ($tables->getTable('group')->all() as $group) {
            if (!$group->hasTab()) {
                self::makeTabFromGroup($group);
            }
        }
    }

    public static function makeTabFromGroup($group)
    {
        $tables = new Tables();

        $tab = (new Tab())
            ->exchangeArray([
                'name' => $group->name,
            ]);

        $tabTable = $tables->getTable('tab');

        $tab = $tabTable->save($tab);

        $tables->getTable('ownerTabs')
            ->addCorrelation($tab->slug, $group->slug, [
                'type' => $tables->getTable('ownerType')
                    ->getType('group', [
                        'type' => 'name',
                    ]),
            ]);
    }
}
