<?php

namespace SessionManager\TableModels;

use Privilege\Model\Privilege;
use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class PrivilegeTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'Privilege';

    public function __construct()
    {
        $this->table = 'privileges';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds Privilege to database from array
     *
     * @param array $data
     *
     * @return Privilege
     */
    public function addPrivilege($data)
    {
        return $this->add($data);
    }

    /**
     * Adds Privilege to database from array.
     *
     * @param array $data
     *
     * @return Privilege
     */
    public function add($data)
    {
        $privilege = new Privilege($data);

        return $this->save($privilege);
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all Privileges from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all Privileges from the database.
     */
    public function all()
    {
        return $this->select();
    }

    /**
     * @deprecated Please use the get method instead.
     *
     * Selects an Privilege from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return Privilege
     */
    public function getPrivilege($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an Privilege from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return Privilege
     */
    public function get($id)
    {
        $rowset = $this->select([Privilege::$primaryKey => $id]);

        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row in %s with identifier %s of type %s',
                $this->table, $id, Privilege::$primaryKey
            ));
        }

        return $row;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an privilege exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function privilegeExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an privilege exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? Privilege::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method.
     *
     * Saves an Privilege to the database.
     *
     * If $privilege->slug is not null then attempts to update an privilege with that slug
     *
     * @param Privilege $privilege
     *
     * @throws RuntimeException Privilege does not exist
     *
     * @return Privilege
     */
    public function savePrivilege($privilege)
    {
        return $this->save($privilege);
    }

    /**
     * Saves an Privilege to the database.
     *
     * If $privilege->slug is not null then attempts to update an privilege with that slug
     *
     * @param Privilege $privilege
     *
     * @throws RuntimeException Privilege does not exist
     *
     * @return Privilege
     */
    public function save($privilege)
    {
        $data = [
            'name'         => $privilege->name,
            'description'  => $privilege->description,
            'level'        => $privilege->level,
        ];

        $slug = $privilege->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Privilege::generateSlug();
            } while ($this->exists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
        } elseif ($dbPrivilege = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update privilege with identifier %s does not exist',
                $slug
            ));
        }

        $privilege->slug = $data['slug'] ?? $slug;

        return $privilege;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes Privilege and deletes the Privilege's icon.
     *
     * @param string $slug Privilege's slug.
     *
     * @return void
     */
    public function deletePrivilege($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes Privilege and deletes the Privilege's icon.
     *
     * @param string $slug Privilege's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete(['slug' => $slug]);
    }
}
