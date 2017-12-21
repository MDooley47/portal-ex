<?php

namespace App\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGateway;
use Zend\Validator\Db\RecordExists;

class AppTable
{
    private $tableGateway;

    public function __construct(TableGateway $tableGateway)
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

        $slug = $app->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = App::generateSlug();
            }
            while ($this->appExists($data['slug']));
            $this->tableGateway->insert($data);
            return;
        }

        if ($dbApp = $this->getApp($slug))
        {
            if ($data['iconPath'] != $dbApp->iconPath)
            {
                if (file_exists($file = $dbApp->iconPath)) unlink($file);
            }
            $data['version'] = 1 + (int) $dbApp->version;
            $this->tableGateway->update($data,['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update app with identifier %d; does not exist',
                $id
            ));
        }
    }

    public function deleteApp($id)
    {
        if (file_exists($file = $this->getApp($id)->iconPath)) unlink($file);
        $this->tableGateway->delete(['slug' => $id]);
    }
}
