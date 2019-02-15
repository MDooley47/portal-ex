<?php

namespace SessionManager\TableModels;

use OwnerType\Model\OwnerType;
use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class OwnerTypeTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'OwnerType';

    public function __construct()
    {
        $this->table = 'ownerTypes';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds OwnerType to database from array
     *
     * @param array $data
     *
     * @return OwnerType
     */
    public function addOwnerType($data) {
        return $this->add($data);
    }

    /**
     * Adds OwnerType to database from array
     *
     * @param array $data
     *
     * @return OwnerType
     */
    public function add($data) {
        $ownerType = new OwnerType($data);

        return $this->save($ownerType);
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all OwnerTypes from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all OwnerTypes from the database.
     */
    public function all() {
        return $this->select();
    }

    /**
     * @deprecated Please use the get method.
     *
     * @param $id
     * @param array $options
     * @return OwnerType
     */
    public function getType($id, $options = [])
    {
        return $this->get($id, $options);
    }

    /**
     * @deprecated Please use the get method.
     *
     * Selects an OwnerType from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'slug'.
     *
     * @return OwnerType
     */
    public function getOwnerType($id, $options = ['type' => 'slug'])
    {
        return $this->get($id, $options);
    }

    /**
     * Selects an OwnerType from the database.
     *
     * @param mixed      $id      The identifier.
     * @param array      $options
     *
     * @return OwnerType
     */
    public function get($id, array $options = [])
    {
        $options['type'] = $options['type'] ?? OwnerType::$primaryKey;

        $rowset = $this->select(function (Select $select) use ($id, $options) {
            switch (strtolower($options['type'])) {
                case 'name':
                    $select->where([
                        'name' => $id,
                    ]);
                    break;
                case OwnerType::$primaryKey:
                default:
                    $select->where([
                        OwnerType::$primaryKey => $id,
                    ]);
            }
        });


        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %d of type %s',
                $id, $options['type']
            ));
        }

        return new OwnerType($rowset->toArray());
    }


    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an ownerType exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param array      $options Contains 'field' which defines what type of
     *                            identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function ownerTypeExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an ownerType exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param array      $options Contains 'field' which defines what type of
     *                            identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['slug'] ?? OwnerType::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }


    /**
     * @deprecated Please use the save method.
     *
     * Saves an OwnerType to the database.
     *
     * If $ownerType->slug is not null then attempts to update an ownerType with that slug
     *
     * @param OwnerType $ownerType
     *
     * @throws RuntimeException OwnerType does not exist
     *
     * @return OwnerType
     */
    public function saveOwnerType($ownerType)
    {
        return $this->save($ownerType);
    }

    /**
     * Saves an OwnerType to the database.
     *
     * If $ownerType->slug is not null then attempts to update an ownerType with that slug
     *
     * @param OwnerType $ownerType
     *
     * @throws RuntimeException OwnerType does not exist
     *
     * @return OwnerType
     */
    public function save($ownerType)
    {
        $data = [
            'name'        => $ownerType->name,
            'description' => $ownerType->description,
        ];

        $slug = $ownerType->slug;

        if ($slug == null) {
            do {
                $data['slug'] = OwnerType::generateSlug();
            } while ($this->exists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
        } else if ($dbOwnerType = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update ownerType with identifier %d does not exist using identifier type %s',
                $slug, 'slug'
            ));
        }

        $ownerType->slug = $data['slug'] ?? $slug;

        return $ownerType;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes OwnerType and deletes the OwnerType's icon.
     *
     * @param string $slug OwnerType's slug.
     *
     * @return void
     */
    public function deleteOwnerType($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes OwnerType and deletes the OwnerType's icon.
     *
     * @param string $slug OwnerType's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete(['slug' => $slug]);
    }
}
