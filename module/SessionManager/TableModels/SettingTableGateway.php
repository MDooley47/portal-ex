<?php

namespace SessionManager\TableModels;

use RuntimeException;
use Setting\Model\Setting;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class SettingTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'Setting';

    public function __construct()
    {
        $this->table = 'settings';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds Setting to database from array
     *
     * @param array $data
     *
     * @return Setting
     */
    public function addSetting($data)
    {
        return $this->add($data);
    }

    /**
     * Adds Setting to database from array.
     *
     * @param array $data
     *
     * @return Setting
     */
    public function add($data)
    {
        $app = new Setting($data);

        return $this->save($app);
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all Settings from the database.
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects all Settings from the database.
     */
    public function all()
    {
        return $this->select();
    }

    /**
     * @deprecated Please use the get method.
     *
     * Selects an Settings from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return Setting
     */
    public function getSetting($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an Settings from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return Setting
     */
    public function get($id)
    {
        $rowset = $this->select([Setting::$primaryKey => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %s of type %s',
                $id, Setting::$primaryKey
            ));
        }

        return $row;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an setting exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function settingExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an setting exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['type' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? Setting::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method.
     *
     * Saves an Setting to the database.
     *
     * If $tab->slug is not null then attempts to update an tab with that slug
     *
     * @param Setting $setting
     *
     * @throws RuntimeException Setting does not exist
     *
     * @return Setting
     */
    public function saveSetting($setting)
    {
        return $this->save($setting);
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
     * @return Setting
     */
    public function save($setting)
    {
        $data = [
            'data' => $setting->data,
        ];

        $slug = $setting->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Setting::generateSlug();
            } while ($this->exists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
        } elseif ($dbSetting = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update tab with identifier %s does not exist',
                $slug
            ));
        }

        $setting->slug = $data['slug'] ?? $slug;

        return $setting;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes Setting and deletes the Setting's icon.
     *
     * @param string $slug Setting's slug.
     *
     * @return void
     */
    public function deleteSetting($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes Setting and deletes the Setting's icon.
     *
     * @param string $slug Setting's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete(['slug' => $slug]);
    }
}
