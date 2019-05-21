<?php

namespace SessionManager\TableModels;

use IpAddress\Model\IpAddress;
use RuntimeException;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class IpAddressTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'IpAddress';

    public function __construct()
    {
        $this->table = 'ipAddresses';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds IpAddress to database from array
     *
     * @param array $data
     *
     * @return IpAddress
     */
    public function addIpAddress($data)
    {
        return $this->add($data);
    }

    /**
     * Adds IpAddress to database from array.
     *
     * @param $data
     *
     * @return IpAddress
     */
    public function add($data)
    {
        $ipAddress = new IpAddress($data);

        return $this->save($ipAddress);
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all IpAddresss from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all IpAddresss from the database.
     */
    public function all()
    {
        return $this->select();
    }

    /**
     * @deprecated Please use the get method.
     *
     * Selects an IpAddress from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return IpAddress
     */
    public function getIpAddress($id, $options = null)
    {
        $this->get($id);
    }

    /**
     * Selects an IpAddress from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return IpAddress
     */
    public function get($id)
    {
        $rowset = $this->select([IpAddress::$primaryKey => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %d of type %s',
                $id, IpAddress::$primaryKey
            ));
        }

        return $row;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an ipAddress exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function ipAddressExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an ipAddress exists in the database.
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
            'field'   => $options['type'] ?? IpAddress::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method.
     *
     * Saves an IpAddress to the database.
     *
     * If $ipAddress->slug is not null then attempts to update an ipAddress with that slug
     *
     * @param IpAddress $ipAddress
     *
     * @throws RuntimeException IpAddress does not exist
     *
     * @return IpAddress
     */
    public function saveIpAddress($ipAddress)
    {
        return $this->save($ipAddress);
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
     * @return IpAddress
     */
    public function save($ipAddress)
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
            } while ($this->exists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
        } elseif ($dbIpAddress = $this->get($slug)) {
            $this->update($data, ['slug' => $slug]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update ipAddress with identifier %d; does not exist',
                $slug
            ));
        }

        $ipAddress->slug = $data['slug'] ?? $slug;

        return $ipAddress;
    }

    /**
     * @deprecated Please use the delete method.
     *
     * Deletes IpAddress and deletes the IpAddress's icon.
     *
     * @param string $slug IpAddress's slug.
     *
     * @return void
     */
    public function deleteIpAddress($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes IpAddress and deletes the IpAddress's icon.
     *
     * @param string $slug IpAddress's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        parent::delete([IpAddress::$primaryKey => $slug]);
    }
}
