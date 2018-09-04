<?php

namespace SessionManager\TableModels;

use Privilege\Model\Privilege;
use RuntimeException;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class PrivilegeTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'privileges';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Privileges from the database.
     *
     * @return Privilege[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an Privilege from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return Privilege
     */
    public function getPrivilege($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug') {
            $rowset = $this->select(['slug' => $id]);
        } elseif ($options['type' == 'id']) {
            $rowset = $this->select(['id' => $id]);
        }
        $row = $rowset->current();
        if (!$row) {
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
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function privilegeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
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
     * @return void
     */
    public function savePrivilege(Privilege $privilege)
    {
        $data = [
            'name'        => $privilege->name,
            'description' => $privilege->description,
        ];

        $slug = $privilege->slug;

        if ($slug == null) {
            do {
                $data['slug'] = Privilege::generateSlug();
            } while ($this->privilegeExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return;
        }

        if ($dbPrivilege = $this->getPrivilege($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update privilege with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes Privilege and deletes the Privilege's icon.
     *
     * @param string $slug Privilege's slug.
     *
     * @return void
     */
    public function deletePrivilege($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
