<?php

namespace SessionManager\TableModels;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class UserPrivilegesTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'userPrivileges';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function hasPrivilege($user, $privilege, $group = null): bool
    {
        $rowset = $this->select(function (Select $select)
            use ($user, $privilege, $group)
        {
            $select->where([
                'userSlug' => $user,
                'privilegeSlug' => $privilege,
                'groupSlug' => $group,
            ]);
        });

        $row = $rowset->current();
        return ($row) ? true : false;
    }
}
