<?php

namespace SessionManager\TableModels;

use GroupType\Model\GroupType;
use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class GroupTypeTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'GroupType';

    public function __construct()
    {
        $this->table = 'groupTypes';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds GroupType to database from array
     *
     * @param $data
     *
     * @return GroupType
     */
    public function addGroupType($data)
    {
        return $this->add($data);
    }

    /**
     * Adds GroupType to database from array.
     *
     * @param $data
     *
     * @return GroupType
     */
    public function add($data)
    {
        $groupType = new GroupType($data);

        return $this->save($groupType);
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all GroupTypes from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all GroupTypes from the database.
     */
    public function all()
    {
        return $this->select();
    }

    /**
     * @deprecated Please use the get method.
     *
     * Selects an GroupType from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return GroupType
     */
    public function getGroupType($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an GroupType from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return GroupType
     */
    public function get($id)
    {
        $rowset = $this->select([GroupType::$primaryKey => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %s of type %s',
                $id, GroupType::$primaryKey
            ));
        }

        return $row;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an groupType exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function groupTypeExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an groupType exists in the database.
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
            'field'   => $options['field'] ?? GroupType::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method.
     *
     * Saves an GroupType to the database.
     *
     * If $groupType->slug is not null then attempts to update an groupType with that slug
     *
     * @param GroupType $groupType
     *
     * @throws RuntimeException GroupType does not exist
     *
     * @return GroupType
     */
    public function saveGroupType($groupType)
    {
        return $this->save($groupType);
    }

    /**
     * Saves an GroupType to the database.
     *
     * If $groupType->slug is not null then attempts to update an groupType with that slug
     *
     * @param GroupType $groupType
     *
     * @throws RuntimeException GroupType does not exist
     *
     * @return GroupType
     */
    public function save($groupType)
    {
        $data = [
            'name'        => $groupType->name,
            'description' => $groupType->description,
            'level'       => $groupType->level,
        ];

        $slug = $groupType->slug;

        if ($slug == null) {
            do {
                $data['slug'] = GroupType::generateSlug();
            } while ($this->exists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
        } elseif ($dbGroupType = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update groupType with identifier %d; does not exist',
                $slug
            ));
        }

        $groupType->slug = $data['slug'] ?? $slug;

        return $groupType;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes GroupType and deletes the GroupType's icon.
     *
     * @param string $slug GroupType's slug.
     *
     * @return void
     */
    public function deleteGroupType($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes GroupType and deletes the GroupType's icon.
     *
     * @param string $slug GroupType's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete([GroupType::$primaryKey => $slug]);
    }
}
