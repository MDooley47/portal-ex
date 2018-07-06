<?php

namespace SessionManager\TableModels;

use RuntimeException;

use Group\Model\Group;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class GroupGroupsTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'groupGroups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }
}
