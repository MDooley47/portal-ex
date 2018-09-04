<?php

namespace SessionManager\TableModels;

use RuntimeException;
use Setting\Model\Setting;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class SettingTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'settings';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Settings from the database.
     *
     * @return Settings[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an Settings from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return Settings
     */
    public function getSetting($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug') {
            $rowset = $this->select(['slug' => $id]);
        } elseif ($options['type' == 'id']) {
            $rowset = $this->select(['id' => $id]);
        }
        $row = $rowset->current();
        if (!$row) {
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
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function settingExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Setting to the database.
     *
     * If $tab->slug is not null then attempts to update an tab with that slug
     *
     * @param Setting $setting
     *
     * @throws RuntimeException Setting does not exist
     *
     * @return void
     */
    public function saveSetting(Setting $setting)
    {
        $data = [
            'data' => $setting->data,
        ];

        $slug = $setting->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Setting::generateSlug();
            } while ($this->settingExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return;
        }

        if ($dbSetting = $this->getSetting($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update tab with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Setting and deletes the Setting's icon.
     *
     * @param string $slug Setting's slug.
     *
     * @return void
     */
    public function deleteSetting($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
