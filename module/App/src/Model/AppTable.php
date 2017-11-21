<?php

namespace App\Model;

use RuntimeException;
use Zend\Db\TableGatewayInterface;

class AppTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getApp($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row)
        {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveApp(App $app)
    {
        $data = [
            'name' => $app->name;
            'url' => $app->url;
            'iconPath' => $app->iconPath;
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

        $this->tableGateway->update($data,['id' => $id]);
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
