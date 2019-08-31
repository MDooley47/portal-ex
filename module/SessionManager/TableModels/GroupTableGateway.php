<?php

namespace SessionManager\TableModels;

use Group\Model\Group;
use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class GroupTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'Group';

    public function __construct()
    {
        $this->table = 'groups';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all Groups from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all Groups from the database.
     */
    public function all()
    {
        return $this->select();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds Group to database from array
     *
     * @param $data
     *
     * @return Group
     */
    public function addGroup($data)
    {
        return $this->add($data);
    }

    /**
     * Adds Group to database from array.
     *
     * @param $data
     *
     * @return Group
     */
    public function add($data)
    {
        $model = new Group($data);

        return $this->save($model);
    }

    /**
     * @deprecated Please use the get method.
     *
     * Selects an Group from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return Group
     */
    public function getGroup($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an Group from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return Group
     */
    public function get($id)
    {
        $rowset = $this->select([Group::$primaryKey => $id]);

        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Group could not find row with identifier %s of type %s',
                $id, Group::$primaryKey
            ));
        }

        $group = castModel('groups', $row->getArrayCopy());

        return $group;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an group exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'type' which defines what type of
     *                       identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function groupExists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? Group::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Checks if an group exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'type' which defines what type of
     *                       identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? 'slug',
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method instead.
     *
     * Saves an Group to the database.
     *
     * If $group->slug is not null then attempts to update an group with that slug
     *
     * @param Group $group
     *
     * @throws RuntimeException Group does not exist
     *
     * @return Group
     */
    public function saveGroup($group)
    {
        return $this->save($group);
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
     * @return Group
     */
    public function save($group)
    {
        $data = [
            'name'        => $group->name,
            'description' => $group->description,
            'groupType'   => $group->groupType ?? $group->grouptype,
        ];

        $slug = $group->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Group::generateSlug();
            } while ($this->groupExists($data['slug']));
            $this->insert($data);
        } elseif ($dbGroup = $this->getGroup($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update group with identifier %d; does not exist',
                $slug
            ));
        }

        $group->slug = $data['slug'] ?? $slug;

        return $group;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes Group and deletes the Group's icon.
     *
     * @param string $slug Group's slug.
     *
     * @return void
     */
    public function deleteGroup($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes Group and deletes the Group's icon.
     *
     * @param string $slug Group's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete(['slug' => $slug]);
    }
}
