<?php

namespace Privilege\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class PrivilegeTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs PrivilegeTable
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
     * Selects all Privileges from the database.
     *
     * @return Privilege[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an Privilege from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return Privilege
     */
    public function getPrivilege($id, $options = ['type' => 'slug'])
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
     * Checks if an privilege exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function privilegeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Privilege to the database.
     *
     * If $privilege->slug is not null then attempts to update an privilege with that slug
     *
     * @param Privilege $privilege
     * @return void
     * @throws RuntimeException Privilege does not exist
     */
    public function savePrivilege(Privilege $privilege)
    {
        $data = [
            'name' => $privilege->name,
            'description' => $privilege->description
        ];

        $slug = $privilege->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = Privilege::generateSlug();
            }
            while ($this->privilegeExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbPrivilege = $this->getPrivilege($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update privilege with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Privilege and deletes the Privilege's icon.
     *
     * @param String $slug Privilege's slug.
     * @return void
     */
    public function deletePrivilege($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
