<?php

namespace SessionManager\TableModels;

use IpAddress\Model\IpAddress;
use RuntimeException;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class IpAddressTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'ipAddresses';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all IpAddresss from the database.
     *
     * @return IpAddress[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an IpAddress from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return IpAddress
     */
    public function getIpAddress($id, $options = ['type' => 'slug'])
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
     * Checks if an ipAddress exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'id'.
     *
     * @return bool If value exists
     */
    public function ipAddressExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an IpAddress to the database.
     *
     * If $ipAddress->slug is not null then attempts to update an ipAddress with that slug
     *
     * @param IpAddress $ipAddress
     *
     * @throws RuntimeException IpAddress does not exist
     *
     * @return void
     */
    public function saveIpAddress(IpAddress $ipAddress)
    {
        $data = [
            'name'        => $ipAddress->name,
            'description' => $ipAddress->description,
            'ip'          => $ipAddress->ip,
        ];

        $slug = $ipAddress->slug;

        if ($slug == null) {
            do {
                $data['slug'] = IpAddress::generateSlug();
            } while ($this->ipAddressExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);

            return;
        }

        if ($dbIpAddress = $this->getIpAddress($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(springf(
                'Cannot update ipAddress with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes IpAddress and deletes the IpAddress's icon.
     *
     * @param string $slug IpAddress's slug.
     *
     * @return void
     */
    public function deleteIpAddress($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
