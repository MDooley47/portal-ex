<?php

namespace SessionManager\TableModels;

use SessionManager\Tables;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class UserGroupsTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'userGroups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function getGroups($user)
    {

        $rowset = $this->select(function (Select $select)
                    use ($user, $privilege, $group)
                {
                    $select->where([
                        'userSlug' => $user,
                    ]);
                });

        if (count($rowset) <= 0)
        {
            return null;
        }

        $groupTable = (new Tables())->getTable('group');
        $groups = [];


        foreach ($rowset as $row)
        {
            array_push($groups, $groupTable->getGroup($row['groupSlug']));
        }

        return $groups;
    }
}
