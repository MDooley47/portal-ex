<?php

namespace SessionManager\TableModels;

use Attribute\Model\Attribute;
use RuntimeException;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class AttributeTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'attributes';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Attributes from the database.
     *
     * @return Attribute[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an Attribute from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return Attribute
     */
    public function getAttribute($id, $options = ['type' => 'slug'])
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
     * Checks if an attribute exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function attributeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
            ]))->isValid($id);
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
     * @return void
     */
    public function saveAttribute(Attribute $attribute)
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
            } while ($this->attributeExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return;
        }

        if ($dbAttribute = $this->getAttribute($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update attribute with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Attribute and deletes the Attribute's icon.
     *
     * @param string $slug Attribute's slug.
     *
     * @return void
     */
    public function deleteAttribute($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
