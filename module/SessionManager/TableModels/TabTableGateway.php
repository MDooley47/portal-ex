<?php

namespace SessionManager\TableModels;

use RuntimeException;
use Tab\Model\Tab;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class TabTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'tabs';
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
        return $this->select();
    }

    /**
     * Selects an Tab from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return Tab
     */
    public function getTab($id, $options = [])
    {
        $rowset = $this->select(['slug' => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %s of type %s.',
                $id, 'slug'
            ));
        }

        return (new Tab())->exchangeArray($row->getArrayCopy());
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
     * Checks if an tab exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function tabExists($id, $options = ['type' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
            ]))->isValid($id);
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
     * @return void
     */
    public function saveTab(Tab $tab)
    {
        $data = [
            'name'        => $tab->name,
            'description' => $tab->description,
        ];

        $slug = $tab->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Tab::generateSlug();
            } while ($this->tabExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return $data['slug'];
        }

        if ($dbTab = $this->getTab($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update tab with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Tab and deletes the Tab's icon.
     *
     * @param string $slug Tab's slug.
     *
     * @return void
     */
    public function deleteTab($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
