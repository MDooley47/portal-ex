<?php

namespace SessionManager\TableModels;

use Group\Model\Group;
use Privilege\Model\Privilege;
use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class UserPrivilegesTableGateway extends AbstractTableGateway implements CorrelationInterface
{
    use HasColumns;

    public function __construct()
    {
        $this->table = 'userPrivileges';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    // TODO: support multiple parents on a group.

    /**
     * Does user have privilege?
     *
     * @param \User\Model\User|string        $user
     * @param \Privilege\Model\Privilege|int $privilege
     * @param \Group\Model\Group|string|null $group
     *
     * @return bool
     */
    public function hasPrivilege($user, $privilege, $group = null)
    {
        $tables = new Tables();

        $privilegeTable = $tables->getTable('privilege');

        if ($privilege instanceof Privilege) {
            $requestedPrivilegeLevel = $privilege->level;
        } else {
            // the following line prevents recursion from making
            // unnecessary database calls.
            $privilege = $privilegeTable->getPrivilege($privilege);
            $requestedPrivilegeLevel = $privilege->level;
        }

        $existingPrivilegeLevel = $this->getUserPrivilege($user, $group);

        if ($existingPrivilegeLevel <= $requestedPrivilegeLevel) {
            return true;
        } elseif (isset($group)) {
            $parentGroup = $tables->getTable('groupGroups')->getParent($group);

            return $this->hasPrivilege($user, $privilege, $parentGroup);
        }
    }

    /**
     * Get the privilege attached to the user [and group.].
     *
     * @param \User\Model\User|string        $user
     * @param \Group\Model\Group|string|null $group
     *
     * @return \Privilege\Model\Privilege
     */
    public function getUserPrivilege($user, $group = null)
    {
        $user = getSlug($user);

        if (isset($group)) {
            $group = getSlug($group);
        }

        $rowset = $this->select(function (Select $select) use ($user, $group) {
            $select->where([
                'userSlug'  => $user,
                'groupSlug' => $group,
            ]);
        });

        $output = $rowset->current();

        if (empty($output)) {
            $output = (new Tables())
                ->getTable('privilege')
                ->getPrivilege('anon');
        }

        return $output;
    }

    /**
     * @param \User\Model\User|string           $user
     * @param \Privilege\Model\Privilege|string $privilege
     * @param array                             $options
     *
     * @return bool|null
     */
    public function addCorrelation($user, $privilege, $options = [])
    {
        if ($this->correlationExists($user, $privilege, $options)) {
            // correlation already exists
            return;
        }

        $data = [
            'userSlug'      => $user,
            'privilegeSlug' => $privilege,
        ];

        if (array_key_exists('groupSlug', $options)) {
            $data['groupSlug'] = $options['groupSlug'];
        }

        return $this->insert($data);
    }

    /**
     * @param \User\Model\User|string           $user
     * @param \Privilege\Model\Privilege|string $privilege
     * @param array                             $options
     *
     * @return bool
     */
    public function correlationExists($user, $privilege, $options = [])
    {
        $adapter = $this->getAdapter();

        $clause = '"privilegeSlug"'
            .' = '
            ."'$privilege'";

        if (array_key_exists('groupSlug', $options)) {
            $groupSlug = $options['groupSlug'];
            $clause .= ' AND "groupSlug"'
                .' = '
                ."'$groupSlug'";
        }

        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => 'userSlug', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($user);
    }
}
