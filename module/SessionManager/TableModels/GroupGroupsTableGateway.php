<?php

namespace SessionManager\TableModels;

use Group\Model\Group;
use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class GroupGroupsTableGateway extends AbstractTableGateway implements CorrelationInterface
{
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
     * TODO: support multiple parent groups.
     *
     * @param $childGroup
     */
    public function getParentGroup($childGroup)
    {
        $groupTable = (new Tables())->getTable('group');

        if ($childGroup instanceof Group) {
            $childGroup = $childGroup->slug;
        }

        $rowset = $this->select(function (Select $select) use ($childGroup) {
            $select->where('childGroup', '=', $childGroup);
        });

        dd($rowset->current);
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
