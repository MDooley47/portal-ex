<?php

namespace SessionManager\TableModels;

use RuntimeException;

use Tab\Model\Tab;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class TabTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'tabs';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Tabs from the database.
     *
     * @return Tab[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an Tab from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return Tab
     */
    public function getTab($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug')
        {
            $rowset = $this->tableGateway->select(['slug' => $id]);
        }
        else if ($options['type' == 'id'])
        {
            $rowset = $this->tableGateway->select(['id' => $id]);
        }
        $row = $rowset->current();
        if (! $row)
        {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %d of type %s',
                $id, $options['type']
            ));
        }

        return $row;
    }

    /**
    * Checks if an tab exists in the database.
    *
    * @param mixed $id The identifier.
    * @param dictionary $options Contains 'type' which defines what type of
    * identifier $id is. Default value is 'type' => 'id'.
    * @return boolean If value exists
    */
    public function tabExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
            ]))->isValid($id);
    }

    /**
     * Saves an Tab to the database.
     *
     * If $tab->slug is not null then attempts to update an tab with that slug
     *
     * @param Tab $tab
     * @return void
     * @throws RuntimeException Tab does not exist
     */
    public function saveTab(Tab $tab)
    {
        $data = [
            'name' => $tab->name,
            'description' => $tab->description,
        ];

        $slug = $tab->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = Tab::generateSlug();
            }
            while ($this->tabExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbTab = $this->getTab($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update tab with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Tab and deletes the Tab's icon.
     *
     * @param String $slug Tab's slug.
     * @return void
     */
    public function deleteTab($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
