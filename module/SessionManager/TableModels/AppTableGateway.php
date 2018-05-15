<?php

namespace SessionManager\TableModels;

use RuntimeException;

use App\Model\App;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;
use Zend\Validator\Db\RecordExists;


class AppTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'apps';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * Selects all Apps from the database.
     *
     * @return App[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    public function getGroups($user)
    {

        $rowset = $this->select(function (Select $select)
                    use ($user, $privilege, $group)
                {
                    $select->where([
                        'userSlug' => $user,
                    ]);
                });

        return $rowset;
    }

    /**
     * Selects an App from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'slug'.
     * @return App
     */
    public function getApp($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug')
        {
            $rowset = $this->select(['slug' => $id]);
        }
        else if ($options['type' == 'id'])
        {
            $rowset = $this->select(['id' => $id]);
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
     * Checks if an app exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'slug'.
     * @return boolean If value exists
     */
    public function appExists($id, $options = ['type' => 'slug'])
    {
        return (new RecordExists([
            'table' => $this->getTable(),
            'field' => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an App to the database.
     *
     * If $app->slug is not null then attempts to update an app with that slug
     *
     * @param App $app
     * @return void
     * @throws RuntimeException App does not exist
     */
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
            $this->insert($data);
            return;
        }

        if ($dbApp = $this->getApp($slug))
        {
            if ($data['iconPath'] != $dbApp->iconPath)
            {
                if (file_exists(addBasePath($file = $dbApp->iconPath))) unlink($file);
            }
            $data['version'] = 1 + (int) $dbApp->version;
            $this->update($data,['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update app with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes App and deletes the App's icon.
     *
     * @param String $slug App's slug.
     * @return void
     */
    public function deleteApp($slug)
    {
        if (file_exists($file = $this->getApp($slug)->iconPath)) unlink($file);
        $this->delete(['slug' => $slug]);
    }
}
