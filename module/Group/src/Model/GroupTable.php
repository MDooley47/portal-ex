<?php

namespace Group\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class GroupTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs GroupTable
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
     * Selects all Groups from the database.
     *
     * @return Group[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an Group from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return Group
     */
    public function getGroup($id, $options = ['type' => 'slug'])
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
     * Checks if an group exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function groupExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an Group to the database.
     *
     * If $group->slug is not null then attempts to update an group with that slug
     *
     * @param Group $group
     * @return void
     * @throws RuntimeException Group does not exist
     */
    public function saveGroup(Group $group)
    {
        $data = [
            'name' => $group->name,
            'email' => $group->email
        ];

        $slug = $group->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = Group::generateSlug();
            }
            while ($this->groupExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbGroup = $this->getGroup($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update group with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Group and deletes the Group's icon.
     *
     * @param String $slug Group's slug.
     * @return void
     */
    public function deleteGroup($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
