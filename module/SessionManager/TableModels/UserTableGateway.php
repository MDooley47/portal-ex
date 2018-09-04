<?php

namespace SessionManager\TableModels;

use RuntimeException;
use User\Model\User;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class UserTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'users';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Users from the database.
     *
     * @return User[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an User from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return User
     */
    public function getUser($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug') {
            $rowset = $this->select(['slug' => $id]);
        } elseif ($options['type'] == 'email') {
            $rowset = $this->select(['email' =>  strtolower($id)]);
        }

        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %d of type %s',
                $id, $options['type']
            ));
        }

        return (new User())->exchangeArray($row->getArrayCopy());
    }

    /**
     * Checks if an user exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function userExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an User to the database.
     *
     * If $user->slug is not null then attempts to update an user with that slug
     *
     * @param User $user
     *
     * @throws RuntimeException User does not exist
     *
     * @return void
     */
    public function saveUser(User $user)
    {
        $data = [
            'name'  => $user->name,
            'email' => $user->email,
        ];

        $slug = $user->slug;

        if ($slug == null) {
            do {
                $data['slug'] = User::generateSlug();
            } while ($this->userExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return;
        }

        if ($dbUser = $this->getUser($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes User and deletes the User's icon.
     *
     * @param string $slug User's slug.
     *
     * @return void
     */
    public function deleteUser($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
