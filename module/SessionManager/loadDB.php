<?php

namespace SessionManager;

use Group\Model\Group;
use SessionManager\Tables;
use Tab\Model\Tab;

class loadDB
{

    public function __construct()
    {
        $this->makeTabsForGroupsWithoutTabs();
    }

    public function makeTabsForGroupsWithoutTabs()
    {
        $tables = new Tables();

        foreach ($tables->getTable('group')->fetchAll() as $row)
        {
            $group = (new Group())->exchangeArray($row->getArrayCopy());

            if (! $group->hasTab())
            {

/*                do
                {
                    $slug = Tab::generateSlug();
                }
                while ($tables->getTable('tab')->tabExists($slug));
*/

                $tab = (new Tab())
                    ->exchangeArray([
                        "name" => $group->name,
                    ]);

                $tabTable = $tables->getTable('tab');

                $tab = $tabTable->getTab(
                    $tabTable->saveTab($tab)
                );

                $tables->getTable('ownerTabs')
                    ->addCorrelation($tab->slug, $group->slug, [
                        "type" => $tables->getTable('ownerType')
                            ->getType("group", [
                                "type" => "name",
                            ]),
                    ]);

                //$tables->getTable('tab')->saveTab($tab);
            }
        }
    }
}
