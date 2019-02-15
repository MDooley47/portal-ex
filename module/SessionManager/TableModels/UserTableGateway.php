<?php

namespace SessionManager\TableModels;

use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use User\Model\User;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class UserTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'User';

    public function __construct()
    {
        $this->table = 'users';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated  Please use the add method.
     *
     * Adds Users from the database.
     *
     * @return User
     */
    public function addUser($data) {
        return $this->add($data);
    }

    public function add($data) {
        $model = new User($data);

        return $this->save($model);
    }

    /**
     * @deprecated  Please use the all method.
     *
     * Selects all Users from the database.
     *
     * @return User[]
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all Users from the database.
     *
     * @return User[]
     */
    public function all() {
        return $this->select();
    }

    /**
     * @deprecated Please use the get method instead
     *
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
        return $this->get($id, $options);
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
    public function get($id, $options = ['type' => 'slug'])
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

        return new User($row->getArrayCopy());
    }

    /**
     * @deprecated Please use the exists method instead.
     *
     * Checks if an user exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param array      $options Contains 'field' which defines what type of
     *                            identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function userExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an user exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param array      $options Contains 'field' which defines what type of
     *                            identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? User::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     *
     * @deprecated Please use the save method instead.
     *
     * Saves an User to the database.
     *
     * If $user->slug is not null then attempts to update an user with that slug
     *
     * @param User $user
     *
     * @throws RuntimeException User does not exist
     *
     * @return User
     */
    public function saveUser(User $user)
    {
        return $this->save($user);
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
     * @return User
     */
    public function save($user)
    {
        $data = [
            'name'  => $user->name,
            'email' => $user->email,
        ];

        $slug = $user->slug;

        if ($slug == null) {
            do {
                $data['slug'] = User::generateSlug();
            } while ($this->exists($data['slug'], ['field' => 'slug']));
            $this->insert($data);
        } else if ($dbUser = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update user with identifier %s; does not exist',
                $slug
            ));
        }

        $user->slug = $data['slug'] ?? $slug;

        return $user;
    }

    /**
     * @deprecated Please use the delete method
     *
     * Deletes User and deletes the User's icon.
     *
     * @param string $slug User's slug.
     *
     * @return void
     */
    public function deleteUser($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes User and deletes the User's icon.
     *
     * @param string $slug User's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete([User::$primaryKey => $slug]);
    }
}
