<?php

namespace SessionManager\TableModels;

use SessionManager\Tables;
use Traits\Interfaces\CorrelationInterface;
use Traits\Tables\HasColumns;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class TabAppsTableGateway extends AbstractTableGateway implements CorrelationInterface
{
    use HasColumns;

    public function __construct()
    {
        $this->table = 'tabApps';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function getApps($slug, $options = [])
    {
        if (!array_key_exists('type', $options)) {
            $options['type'] = null;
        }

        $rowset = $this->select(function (Select $select) use ($slug, $options) {
            switch (strtolower($options['type'])) {
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

    public function addCorrelation($tab, $app, $options = [])
    {
        if ($this->correlationExists($tab, $app, $options)) {
            // correlation already exists
            return;
        }

        $data = [
            'tabSlug' => $tab,
            'appSlug' => $app,
        ];

        return $this->insert($data);
    }

    public function correlationExists($tab, $app, $options = [])
    {
        $adapter = $this->getAdapter();

        $clause = '"appSlug"'
                .' = '
                ."'$app'";

        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => 'tabSlug', // change
            'adapter' => $adapter,
            'exclude' => $clause,
        ]))->isValid($tab);
    }

    /**
     * Selects apps applied to the given tab slug
     */
    public function fetchRelated($tabSlug)
    {
      $select = new Select();
      $select->from('tabApps');
      $select->where(['tabSlug' => $tabSlug]);
      $select->columns(['tabSlug','appSlug', 'appOrder']);
      $select->join('apps', 'tabApps.appSlug = apps.slug', ['name'], Select::JOIN_LEFT);
      $select->order("appOrder ASC");

      return $this->selectWith($select);
    }

}
