<?php

namespace Setting\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class SettingTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs SettingTable
     *
     * Sets $this->tableGateway to passed in tableGateway.
     *
     * @param TableGateway $tableGateway
     * @return void
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Selects all Settings from the database.
     *
     * @return Settings[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an Settings from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return Settings
     */
    public function getSetting($id, $options = ['type' => 'slug'])
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
     * Checks if an setting exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function settingExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Setting to the database.
     *
     * If $tab->slug is not null then attempts to update an tab with that slug
     *
     * @param Setting $setting
     * @return void
     * @throws RuntimeException Setting does not exist
     */
    public function saveSetting(Setting $setting)
    {
        $data = [
            'data' => $setting->data
        ];

        $slug = $setting->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = Setting::generateSlug();
            }
            while ($this->settingExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbSetting = $this->getSetting($slug))
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
     * Deletes Setting and deletes the Setting's icon.
     *
     * @param String $slug Setting's slug.
     * @return void
     */
    public function deleteSetting($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
