<?php

namespace User\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class UserTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs UserTable
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
     * Selects all Users from the database.
     *
     * @return User[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an User from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return User
     */
    public function getUser($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug')
        {
            $rowset = $this->tableGateway->select(['slug' => $id]);
        }
        else if ($options['type'] == 'email')
        {
            $rowset = $this->tableGateway->select(['email' =>  strtolower($id)]);
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
     * Checks if an user exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function userExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an User to the database.
     *
     * If $user->slug is not null then attempts to update an user with that slug
     *
     * @param User $user
     * @return void
     * @throws RuntimeException User does not exist
     */
    public function saveUser(User $user)
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email
        ];

        $slug = $user->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = User::generateSlug();
            }
            while ($this->userExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbUser = $this->getUser($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes User and deletes the User's icon.
     *
     * @param String $slug User's slug.
     * @return void
     */
    public function deleteUser($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
