<?php

namespace SessionManager\TableModels;

use Traits\Interfaces\CorrelationInterface;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class UserPrivilegesTableGateway
    extends AbstractTableGateway
    implements CorrelationInterface
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

    public function addCorrelation($user, $privilege, $options = [])
    {
        if ($this->correlationExists($user, $privilege, $options))
        {
            # correlation already exists
            return;
        }

        $data = [
            'userSlug' => $user,
            'privilegeSlug' => $privilege,
        ];

        if (array_key_exists("groupSlug", $options))
        {
            $data['groupSlug'] = $options['groupSlug'];
        }

        return $this->insert($data);
    }

    public function correlationExists($user, $privilege, $options = [])
    {
        $adapter = $this->getAdapter();

        $clause = '"privilegeSlug"'
            . ' = '
            . "'$privilege'";

        if (array_key_exists("groupSlug", $options))
        {
            $groupSlug = $options['groupSlug'];
            $clause .= ' AND "groupSlug"'
                . ' = '
                . "'$groupSlug'";
        }

        return (new RecordExists([
            'table' => $this->getTable(),
            'field' => 'userSlug', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($user);
    }
}
