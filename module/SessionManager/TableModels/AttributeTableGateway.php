<?php

namespace SessionManager\TableModels;

use Attribute\Model\Attribute;
use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class AttributeTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'Attribute';

    public function __construct()
    {
        $this->table = 'attributes';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all Attributes from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all Attributes from the database.
     */
    public function all()
    {
        return $this->select();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds Attribute to database from array
     *
     * @param array $data
     *
     * @return Attribute
     */
    public function addAttribute($data)
    {
        return $this->add($data);
    }

    /**
     * Adds Attribute to database from array.
     *
     * @param $data
     *
     * @return Attribute
     */
    public function add($data)
    {
        $attribute = new Attribute($data);

        return $this->save($attribute);
    }

    /**
     * Selects an Attribute from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return Attribute
     */
    public function getAttribute($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an Attribute from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return Attribute
     */
    public function get($id)
    {
        $rowset = $this->select([Attribute::$primaryKey => $id]);

        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %d of type %s',
                $id, Attribute::$primaryKey
            ));
        }

        return $row;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an attribute exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function attributeExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an attribute exists in the database.
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
            'field'   => $options['field'] ?? Attribute::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method.
     *
     * Saves an Attribute to the database.
     *
     * If $attribute->slug is not null then attempts to update an attribute with that slug
     *
     * @param Attribute $attribute
     *
     * @throws RuntimeException Attribute does not exist
     *
     * @return Attribute
     */
    public function saveAttribute($attribute)
    {
        return $this->save($attribute);
    }

    /**
     * Saves an Attribute to the database.
     *
     * If $attribute->slug is not null then attempts to update an attribute with that slug
     *
     * @param Attribute $attribute
     *
     * @throws RuntimeException Attribute does not exist
     *
     * @return Attribute
     */
    public function save($attribute)
    {
        $data = [
            'name'        => $attribute->name,
            'description' => $attribute->description,
            'data'        => $attribute->data,
        ];

        $slug = $attribute->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Attribute::generateSlug();
            } while ($this->exists($data['slug']));
            $this->insert($data);
        } elseif ($dbAttribute = $this->getAttribute($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update attribute with identifier %d; does not exist',
                $slug
            ));
        }

        $attribute->slug = $data['slug'] ?? $slug;

        return $attribute;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes Attribute and deletes the Attribute's icon.
     *
     * @param string $slug Attribute's slug.
     *
     * @return void
     */
    public function deleteAttribute($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes Attribute and deletes the Attribute's icon.
     *
     * @param string $slug Attribute's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete([Attribute::$primaryKey => $slug]);
    }
}
