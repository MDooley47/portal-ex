<?php

namespace SessionManager\TableModels;

use RuntimeException;
use Tab\Model\Tab;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class TabTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'Tab';

    public function __construct()
    {
        $this->table = 'tabs';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds Tab to database from array
     *
     * @param array $data
     *
     * @return Tab
     */
    public function addTab($data)
    {
        return $this->add($data);
    }

    /**
     * Adds Tab to database from array.
     *
     * @param array $data
     *
     * @return Tab
     */
    public function add($data)
    {
        $tab = new Tab($data);

        return $this->save($tab);
    }

    /**
     * @deprecated Please use the all method
     *
     * Selects all Tabs from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all Tabs from the database.
     */
    public function all()
    {
        $select = $this->getSql()->select();
        $select->order('name ASC');

        return $this->selectWith($select);
    }

    /**
     * @deprecated Please use the get method.
     *
     * Selects an Tab from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return Tab
     */
    public function getTab($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an Tab from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return Tab
     */
    public function get($id)
    {
        $rowset = $this->select([Tab::$primaryKey => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %s of type %s.',
                $id, 'slug'
            ));
        }

        return new Tab($row->getArrayCopy());
    }

    public function getTabs($tabSlugs)
    {
        $tabs = [];
        foreach ($tabSlugs as $tab) {
            array_push($tabs, $this->getTab($tab));
        }

        return $tabs;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an tab exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function tabExists($id, $options = ['type' => 'slug'])
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an tab exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? Tab::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method.
     *
     * Saves an Tab to the database.
     *
     * If $tab->slug is not null then attempts to update an tab with that slug
     *
     * @param Tab $tab
     *
     * @throws RuntimeException Tab does not exist
     *
     * @return Tab
     */
    public function saveTab($tab)
    {
        return $this->save($tab);
    }

    /**
     * Saves an Tab to the database.
     *
     * If $tab->slug is not null then attempts to update an tab with that slug
     *
     * @param Tab $tab
     *
     * @throws RuntimeException Tab does not exist
     *
     * @return Tab
     */
    public function save($tab)
    {
        $data = [
            'name'        => $tab->name,
            'description' => $tab->description,
        ];

        $slug = $tab->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Tab::generateSlug();
            } while ($this->exists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
        } elseif ($dbTab = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update tab with identifier %s does not exist',
                $slug
            ));
        }

        $tab->slug = $data['slug'] ?? $slug;

        return $tab;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes Tab and deletes the Tab's icon.
     *
     * @param string $slug Tab's slug.
     */
    public function deleteTab($slug)
    {
        return $this->delete($slug);
    }

    /**
     * Deletes Tab and deletes the Tab's icon.
     *
     * @param string $slug Tab's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete(['slug' => $slug]);
    }
}
