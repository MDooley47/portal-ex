<?php

namespace Owner\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class OwnerTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs OwnerTable
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
     * Selects all Owners from the database.
     *
     * @return Owner[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an Owner from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return Owner
     */
    public function getOwner($id, $options = ['type' => 'slug'])
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
     * Checks if an owner exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function ownerExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Owner to the database.
     *
     * If $owner->slug is not null then attempts to update an owner with that slug
     *
     * @param Owner $owner
     * @return void
     * @throws RuntimeException Owner does not exist
     */
    public function saveOwner(Owner $owner)
    {
        $data = [
            'name' => $owner->name,
            'email' => $owner->email
        ];

        $slug = $owner->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = Owner::generateSlug();
            }
            while ($this->ownerExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbOwner = $this->getOwner($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update owner with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Owner and deletes the Owner's icon.
     *
     * @param String $slug Owner's slug.
     * @return void
     */
    public function deleteOwner($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
