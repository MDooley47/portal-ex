<?php

namespace GroupType\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class GroupTypeTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs GroupTypeTable
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
     * Selects all GroupTypes from the database.
     *
     * @return GroupType[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an GroupType from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return GroupType
     */
    public function getGroupType($id, $options = ['type' => 'slug'])
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
                'Could not Find Row with identifier %s of type %s',
                $id, $options['type']
            ));
        }

        return $row;
    }

    /**
     * Checks if an groupType exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function groupTypeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an GroupType to the database.
     *
     * If $groupType->slug is not null then attempts to update an groupType with that slug
     *
     * @param GroupType $groupType
     * @return void
     * @throws RuntimeException GroupType does not exist
     */
    public function saveGroupType(GroupType $groupType)
    {
        $data = [
            'name' => $groupType->name,
            'description' => $groupType->description
        ];

        $slug = $groupType->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = GroupType::generateSlug();
            }
            while ($this->groupTypeExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbGroupType = $this->getGroupType($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update groupType with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes GroupType and deletes the GroupType's icon.
     *
     * @param String $slug GroupType's slug.
     * @return void
     */
    public function deleteGroupType($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
