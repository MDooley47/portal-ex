<?php

namespace SessionManager\TableModels;

use Privilege\Model\Privilege;
use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class UserPrivilegesTableGateway extends AbstractTableGateway implements CorrelationInterface
{
    use HasColumns;

    public static $thisTime = 0;

    public function __construct()
    {
        $this->table = 'userPrivileges';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

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
        $user = getSlug($user);
        $group = getSlug($group);

        $tables = new Tables();

        $privilegeTable = $tables->getTable('privilege');

        if ($privilege instanceof Privilege || $privilege instanceof \ArrayObject) {
            $requestedPrivilegeLevel = $privilege->level;
        } else {
            // the following line prevents recursion from making
            // unnecessary database calls.
            $privilege = $privilegeTable->get($privilege);
            $requestedPrivilegeLevel = $privilege->level;
        }

        if (empty($group) && $privilege->slug != 'sudo') {
            throw new \InvalidArgumentException('$group should not be null unless checking sudo level access');
        }

        $existingPrivilegeLevel = $this->getUserPrivilege($user, $group)->level;

        if ($existingPrivilegeLevel >= $requestedPrivilegeLevel) {
            return true;
        } elseif (isset($group)) {
            $parentGroup = $tables->getTable('groupGroups')->getParent($group);
            if (!empty($parentGroup)) {
                /* If a user only has auth access on a parent group
                 * they will not be given auth access on the child group by inference;
                 * however, if they have admin access or above it will
                 * transfer to child groups by inference.
                 */
                if ($privilege->slug == 'auth') {
                    $privilege = 'admin';
                }

                return $this->hasPrivilege($user, $privilege, $parentGroup);
            }
        }

        return false;
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
        $group = getSlug($group);

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
        } else {
            $output = $output->getArrayCopy();
            $output = (new Tables())
                ->getTable('privilege')
                ->getPrivilege($output['privilegeSlug']);
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
