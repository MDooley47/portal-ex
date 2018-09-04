<?php

namespace SessionManager\TableModels;

use Group\Model\Group;
use RuntimeException;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class GroupTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'groups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Groups from the database.
     *
     * @return Group[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an Group from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return Group
     */
    public function getGroup($id, $options = ['type' => 'slug'])
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
     * Checks if an group exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function groupExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Group to the database.
     *
     * If $group->slug is not null then attempts to update an group with that slug
     *
     * @param Group $group
     *
     * @throws RuntimeException Group does not exist
     *
     * @return void
     */
    public function saveGroup(Group $group)
    {
        $data = [
            'name'        => $group->name,
            'description' => $group->description,
            'groupType'   => $group->grouptype,
        ];

        $slug = $group->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Group::generateSlug();
            } while ($this->groupExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return;
        }

        if ($dbGroup = $this->getGroup($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update group with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Group and deletes the Group's icon.
     *
     * @param string $slug Group's slug.
     *
     * @return void
     */
    public function deleteGroup($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
