<?php

namespace SessionManager\TableModels;

use RuntimeException;

use Group\Model\Group;
use Traits\Interfaces\CorrelationInterface;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class GroupGroupsTableGateway
    extends AbstractTableGateway
    implements CorrelationInterface
{
    public function __construct()
    {
        $this->table      = 'groupGroups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function addCorrelation($parentGroup, $childGroup, $options = [])
    {
        if ($this->correlationExists($parentGroup, $childGroup, $options))
        {
            # correlation already exists
            return;
        }

        $data = [
            'parentGroup' => $parentGroup,
            'childGroup' => $childGroup,
        ];

        return $this->insert($data);
    }

    public function correlationExists($parentGroup, $childGroup, $options = [])
    {
        $adapter = $this->getAdapter();

        $clause = '"childGroup"'
                . ' = '
                . "'$childGroup'";

        return (new RecordExists([
            'table' => $this->getTable(),
            'field' => 'parentGroup', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($parentGroup);
    }
}
