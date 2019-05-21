<?php

namespace SessionManager\TableModels;

use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class UserGroupsTableGateway extends AbstractTableGateway implements CorrelationInterface
{
    use HasColumns;

    public function __construct()
    {
        $this->table = 'userGroups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function getGroups($user)
    {
        $rowset = $this->select(function (Select $select) use ($user, $privilege, $group) {
            $select->where([
                        'userSlug' => $user,
                    ]);
        });

        if (count($rowset) <= 0) {
            return;
        }

        $groupTable = (new Tables())->getTable('group');
        $groups = [];

        foreach ($rowset as $row) {
            array_push($groups, $groupTable->getGroup($row['groupSlug']));
        }

        return $groups;
    }

    public function addCorrelation($user, $group, $options = [])
    {
        if ($this->correlationExists($user, $group, $options)) {
            // correlation already exists
            return;
        }

        $data = [
            'userSlug'  => $user,
            'groupSlug' => $group,
        ];

        return $this->insert($data);
    }

    public function correlationExists($user, $group, $options = [])
    {
        $adapter = $this->getAdapter();

        $clause = '"groupSlug"'
                .' = '
                ."'$group'";

        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => 'userSlug', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($user);
    }
}
