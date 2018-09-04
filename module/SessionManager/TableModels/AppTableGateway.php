<?php

namespace SessionManager\TableModels;

use App\Model\App;
use RuntimeException;
use SessionManager\Tables;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class AppTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'apps';
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

    public function getApps($appSlugs)
    {
        $apps = [];

        foreach ($appSlugs as $app) {
            array_push($apps, $this->getApp($app));
        }

        return $apps;
    }

    /**
     * Selects an App from the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'slug'.
     *
     * @return App
     */
    public function getApp($id, $options = [])
    {
        $rowset = $this->select(['slug' => $id]);

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
     * Checks if an app exists in the database.
     *
     * @param mixed      $id      The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     *                            identifier $id is. Default value is 'type' => 'slug'.
     *
     * @return bool If value exists
     */
    public function appExists($id, $options = ['type' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an App to the database.
     *
     * If $app->slug is not null then attempts to update an app with that slug
     *
     * @param App $app
     *
     * @throws RuntimeException App does not exist
     *
     * @return void
     */
    public function saveApp(App $app)
    {
        $data = [
            'name'     => $app->name,
            'url'      => $app->url,
            'iconPath' => $app->iconPath,
        ];

        $slug = $app->slug;

        if ($slug == null) {
            do {
                $data['slug'] = App::generateSlug();
            } while ($this->appExists($data['slug']));
            $this->insert($data);

            if (isset($app->tab)) {
                (new Tables())
                    ->getTable('tabApps')
                    ->addCorrelation($app->tab, $data['slug']);
            }

            return;
        }

        if ($dbApp = $this->getApp($slug)) {
            if ($data['iconPath'] != $dbApp->iconPath) {
                if (file_exists(addBasePath($file = $dbApp->iconPath))) {
                    unlink($file);
                }
            }
            $data['version'] = 1 + (int) $dbApp->version;
            $this->update($data,['slug' => $slug]);

            if (isset($app->tab)) {
                (new Tables())
                    ->getTable('tabApps')
                    ->addCorrelation($app->tab, $slug);
            }
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update app with identifier %s; does not exist',
                $slug
            ));
        }
    }

    /**
     * Deletes App and deletes the App's icon.
     *
     * @param string $slug App's slug.
     *
     * @return void
     */
    public function deleteApp($slug)
    {
        if (file_exists($file = $this->getApp($slug)->iconPath)) {
            unlink($file);
        }
        $this->delete(['slug' => $slug]);
    }
}
