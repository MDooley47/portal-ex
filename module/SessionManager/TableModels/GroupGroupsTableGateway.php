<?php

namespace SessionManager\TableModels;

use Group\Model\Group;
use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

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
     * TODO: support multiple parent groups.
     *
     * @param $childGroup
     */
    public function getParentGroups($childGroup)
    {
        $tables = new Tables();
        $group = null;
        $childGroup = getSlug($childGroup);

        $rowset = $this->select(function (Select $select) use ($childGroup) {
            $select->where("\"childGroup\" = '".$childGroup."'");
        })->toArray();

        if (!empty($rowset)) {
            $groupTable = $tables->getTable('group');
            if (count($rowset) == 1) {
                $group = $groupTable->get($rowset[0]['parentGroup']);
            } else {
                foreach ($rowset as $i=>$row) {
                    $group[$i] = $groupTable->get($row['parentGroup']);
                }
            }
        }

        return $group;
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
