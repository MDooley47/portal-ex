<?php

namespace SessionManager\TableModels;

use Group\Model\Group;
use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;

class GroupGroupsTableGateway extends AbstractTableGateway implements CorrelationInterface
{
    use HasColumns;

    /**
     * GroupGroupsTableGateway constructor.
     */
    public function __construct()
    {
        $this->table = 'groupGroups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @param $parentGroup
     * @param $childGroup
     * @param array $options
     *
     * @return int|void
     */
    public function addCorrelation($parentGroup, $childGroup, $options = [])
    {
        if ($parentGroup instanceof Group) {
            $parentGroup = $parentGroup->slug;
        }
        if ($childGroup instanceof Group) {
            $childGroup = $childGroup->slug;
        }

        if ($this->correlationExists($parentGroup, $childGroup, $options)) {
            // correlation already exists
            return;
        }

        $data = [
            'parentGroup' => $parentGroup,
            'childGroup'  => $childGroup,
        ];

        return $this->insert($data);
    }

    /**
     * @deprecated Please use getParent instead.
     */
    public function getParentGroup($childGroup)
    {
        return $this->getParent($childGroup);
    }

    /**
     * TODO: support multiple parent groups.
     *
     * @param $childGroup
     *
     * @return Group|null
     */
    public function getParent($childGroup)
    {
        $childGroup = getSlug($childGroup);

        $groupTable = (new Tables())->getTable('group');

        $rowset = $this->select(function (Select $select) use ($childGroup) {
            $select->where([
                'childGroup' => $childGroup,
            ]);
        });

        $parent = $rowset->current();

        if (isset($parent)) {
            $parent = $groupTable->get($parent->parentGroup);
        }

        return $parent;
    }

    public function correlationExists($parentGroup, $childGroup, $options = [])
    {
        $adapter = $this->getAdapter();

        $clause = '"childGroup"'
                .' = '
                ."'$childGroup'";

        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => 'parentGroup', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($parentGroup);
    }
}
