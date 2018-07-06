<?php

namespace SessionManager\TableModels;

use SessionManager\Tables;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class TabAppsTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'tabApps';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function getApps($slug, $options = [])
    {

        if (! array_key_exists('type', $options))
        {
            $options['type'] = null;
        }

        $rowset = $this->select(function (Select $select)
                    use ($slug, $options)
                {
                    switch (strtolower($options['type']))
                    {
                        case 'tab':
                        default:
                            $select->where([
                                'tabSlug' => $slug,
                            ]);
                            break;
                    }
                });

        return (new Tables())
            ->getTable('app')
            ->getApps(array_column($rowset->toArray(), 'appSlug'));
    }
}
