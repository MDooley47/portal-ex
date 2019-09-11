<?php

namespace SessionManager\TableModels;

use OwnerType\Model\OwnerType;
use SessionManager\Tables;
use Tab\Model\Tab;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
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

        foreach ($rowset as $row) {
            $rowArray[count($rowArray)] = $row;
        }

        if (is_array($rowArray)) {
            return $tables
            ->getTable('tab')
            ->getTabs(array_column($rowArray, 'tabSlug'));
        } else {
            return;
        }
    }

    public function getOwner($slug)
    {
        $tables = (new Tables());

        $slug = getSlug($slug);

        $rowset = $this->select(['tabSlug' => $slug]);

        $owner = $rowset->current();

        // if (!$owner) {
        //     throw new RuntimeException(sprintf(
        //         'OwnerTabs could not Find Row with identifier %d of type Tab',
        //         $slug
        //     ));
        // }

        if ($owner->ownerType === $tables->getTable('ownerType')
                ->get('group', ['type' => 'name'])->slug) {
            $owner = $tables->getTable('group')->get($owner->ownerSlug);
        }

        return $owner;
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
