<?php

namespace SessionManager\TableModels;

use SessionManager\Tables;

use Tab\Model\Tab;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class OwnerTabsTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'ownerTabs';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function getTabs($slug, $options = [])
    {
        $tables = new Tables();

        if (! array_key_exists('type', $options))
        {
            $options['type'] = 'group';
        }

        $options['type'] = $tables
            ->getTable('ownerType')
            ->getType($options['type'], ['type' => 'name'])
            ->slug;

        $rowset = $this->select(function (Select $select)
                    use ($slug, $options)
            {
                $select->where([
                    'ownerSlug' => $slug,
                    'ownerType' => $options['type'],
                ]);
            });

        return $tables
            ->getTable('tab')
            ->getTabs(array_column($rowset->toArray(), 'tabSlug'));
    }

    public function getOwner($slug)
    {
        $rowset = $this->select(['tabSlug' => $slug]);

        $row = $rowset->current();

        if (! $row)
        {
            throw new RuntimeException(sprintf(
                'OwnerTabs could not Find Row with identifier %d of type Tab',
                $slug
            ));
        }

        return $row;
    }
}
