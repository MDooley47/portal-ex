<?php

namespace Attribute\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class AttributeTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs AttributeTable
     *
     * Sets $this->tableGateway to passed in tableGateway.
     *
     * @param TableGateway $tableGateway
     * @return void
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Selects all Attributes from the database.
     *
     * @return Attribute[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an Attribute from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return Attribute
     */
    public function getAttribute($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug')
        {
            $rowset = $this->tableGateway->select(['slug' => $id]);
        }
        else if ($options['type' == 'id'])
        {
            $rowset = $this->tableGateway->select(['id' => $id]);
        }
        $row = $rowset->current();
        if (! $row)
        {
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
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function attributeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Attribute to the database.
     *
     * If $attribute->slug is not null then attempts to update an attribute with that slug
     *
     * @param Attribute $attribute
     * @return void
     * @throws RuntimeException Attribute does not exist
     */
    public function saveAttribute(Attribute $attribute)
    {
        $data = [
            'name' => $attribute->name,
            'description' => $attribute->description,
            'data' => $attribute->data,
        ];

        $slug = $attribute->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = Attribute::generateSlug();
            }
            while ($this->attributeExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbAttribute = $this->getAttribute($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update attribute with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Attribute and deletes the Attribute's icon.
     *
     * @param String $slug Attribute's slug.
     * @return void
     */
    public function deleteAttribute($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
