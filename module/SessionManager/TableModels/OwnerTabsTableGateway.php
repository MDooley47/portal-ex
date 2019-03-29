<?php

namespace SessionManager\TableModels;

use OwnerType\Model\OwnerType;
use SessionManager\Tables;
use Tab\Model\Tab;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class OwnerTabsTableGateway extends AbstractTableGateway implements CorrelationInterface
{
    use HasColumns;

    public function __construct()
    {
        $this->table = 'ownerTabs';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function getTabs($slug, $options = [])
    {
        $tables = new Tables();

        if (!array_key_exists('type', $options)) {
            $options['type'] = 'group';
        }

        $options['type'] = $tables
            ->getTable('ownerType')
            ->getType($options['type'], ['type' => 'name'])
            ->slug;

        $rowset = $this->select(function (Select $select) use ($slug, $options) {
            $select->where([
                    'ownerSlug' => $slug,
                    'ownerType' => $options['type'],
                ]);
        });

        return $tables
            ->getTable('tabs')
            ->getTabs(array_column($rowset->toArray(), 'tabSlug'));
    }

    public function getOwner($slug)
    {
        $rowset = $this->select(['tabSlug' => $slug]);

        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'OwnerTabs could not Find Row with identifier %d of type Tab',
                $slug
            ));
        }

        return $row;
    }

    public function correlationExists($tab, $owner, $options = [])
    {
        arrayValueDefault('type', $options, 'group');

        if ($options['type'] instanceof OwnerType) {
            $options['type'] = $options['type']->slug;
        } else {
            $options['type'] = (new Tables())
                ->getTable('ownerType')
                ->getType($options['type'], ['type' => 'name'])
                ->slug;
        }

        $adapter = $this->getAdapter();

        $clause = '"ownerSlug"'
                .' = '
                ."'$owner'"
            .' AND '
                .'"ownerType"'
                .' = '
                ."'".$options['type']."'";

        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => 'tabSlug', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($tab);
    }

    public function addCorrelation($tab, $owner, $options = [])
    {
        arrayValueDefault('type', $options, 'group');

        if ($this->correlationExists($tab, $owner, $options)) {
            // correlation already exists
            return;
        }

        if ($options['type'] instanceof OwnerType) {
            $options['type'] = $options['type']->slug;
        }

        $data = [
            'tabSlug'   => $tab,
            'ownerSlug' => $owner,
            'ownerType' => $options['type'],
        ];

        return $this->insert($data);
    }
}
