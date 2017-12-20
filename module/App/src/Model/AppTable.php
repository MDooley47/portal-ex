<?php

namespace App\Model;

use RuntimeException;
use Zend\Validator\Db\RecordExists;

class AppTable
{
    private $tableGateway;

    public function __construct(\Zend\Db\TableGateway\TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getApp($id, $options = ['type' => 'slug'])
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

    public function appExists($id, $options = ['type' => 'slug'])
    {
        return (new RecordExists([
            'table' => $this->tableGateway->getTable(),
            'field' => $options['type'],
            'adapter' => $this->tableGateway->getAdapter(),
        ]))->isValid($id);
    }

    public function saveApp(App $app)
    {
        $data = [
            'name' => $app->name,
            'url' => $app->url,
            'iconPath' => $app->iconPath,
        ];

        $id = (int) $app->id;

        if ($id === 0)
        {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getApp($id))
        {
            throw new RuntimeException(springf(
                'Cannot update app with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data,['id' => (int) $id]);
    }

    public function deleteApp($id)
    {
        unlink($this->getApp($id)->iconPath);
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
