<?php

namespace OwnerType\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class OwnerTypeTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs OwnerTypeTable
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
     * Selects all OwnerTypes from the database.
     *
     * @return OwnerType[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an OwnerType from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return OwnerType
     */
    public function getOwnerType($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug')
        {
            $rowset = $this->tableGateway->select(['slug' => $id]);
        }
        else if ($options['type'] == 'id')
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
     * Checks if an ownerType exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function ownerTypeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an OwnerType to the database.
     *
     * If $ownerType->slug is not null then attempts to update an ownerType with that slug
     *
     * @param OwnerType $ownerType
     * @return void
     * @throws RuntimeException OwnerType does not exist
     */
    public function saveOwnerType(OwnerType $ownerType)
    {
        $data = [
            'name' => $ownerType->name,
            'description' => $ownerType->description
        ];

        $slug = $ownerType->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = OwnerType::generateSlug();
            }
            while ($this->ownerTypeExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbOwnerType = $this->getOwnerType($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update ownerType with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes OwnerType and deletes the OwnerType's icon.
     *
     * @param String $slug OwnerType's slug.
     * @return void
     */
    public function deleteOwnerType($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
