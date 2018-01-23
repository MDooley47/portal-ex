<?php

namespace IpAddress\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class IpAddressTable
{
    /**
     * TableGateway.
     */
    private $tableGateway;

    /**
     * Constructs IpAddressTable
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
     * Selects all IpAddresss from the database.
     *
     * @return IpAddress[]
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Selects an IpAddress from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return IpAddress
     */
    public function getIpAddress($id, $options = ['type' => 'slug'])
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
     * Checks if an ipAddress exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function ipAddressExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an IpAddress to the database.
     *
     * If $ipAddress->slug is not null then attempts to update an ipAddress with that slug
     *
     * @param IpAddress $ipAddress
     * @return void
     * @throws RuntimeException IpAddress does not exist
     */
    public function saveIpAddress(IpAddress $ipAddress)
    {
        $data = [
            'name' => $ipAddress->name,
            'email' => $ipAddress->email
        ];

        $slug = $ipAddress->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = IpAddress::generateSlug();
            }
            while ($this->ipAddressExists($data['slug'], ['type' => 'slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbIpAddress = $this->getIpAddress($slug))
        {
            $this->tableGateway->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update ipAddress with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes IpAddress and deletes the IpAddress's icon.
     *
     * @param String $slug IpAddress's slug.
     * @return void
     */
    public function deleteIpAddress($slug)
    {
        $this->tableGateway->delete(['slug' => $slug]);
    }
}
