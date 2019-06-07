<?php

namespace SessionManager\TableModels;

use App\Model\App;
use RuntimeException;
use SessionManager\Tables;
use Traits\Tables\HasColumns;
use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Validator\Db\RecordExists;

class AppTableGateway extends AbstractTableGateway implements UniversalTableGatewayInterface
{
    use HasColumns;

    public $model_name = 'App';

    public function __construct()
    {
        $this->table = 'apps';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    /**
     * @deprecated Please use the add method.
     *
     * Adds App to database from array
     *
     * @param array $data
     *
     * @return App
     */
    public function addApp($data)
    {
        return $this->add($data);
    }

    /**
     * Adds App to database from array.
     *
     * @param array $data
     *
     * @return App
     */
    public function add($data)
    {
        $app = new App($data);

        return $this->save($app);
    }

    /**
     * @deprecated Please use the all method.
     *
     * Selects all Apps from the database.
     */
    public function fetchAll()
    {
        return $this->all();
    }

    /**
     * Selects all Apps from the database.
     */
    public function all()
    {
        $select = $this->getSql()->select();
        $select->order('name ASC');

        return $this->selectWith($select);
    }

    public function getApps($appSlugs)
    {
        $apps = [];

        foreach ($appSlugs as $app) {
            array_push($apps, $this->get($app));
        }

        return $apps;
    }

    /**
     * @deprecated Please use the get method instead.
     *
     * Selects an App from the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options
     *
     * @return App
     */
    public function getApp($id, $options = null)
    {
        return $this->get($id);
    }

    /**
     * Selects an App from the database.
     *
     * @param mixed $id The identifier.
     *
     * @return App
     */
    public function get($id)
    {
        $rowset = $this->select([App::$primaryKey => $id]);

        $row = $rowset->current();
        // if (!$row) {
        // throw new RuntimeException(sprintf(
        //     'Could not Find Row with identifier %d of type %s',
        //     $id, App::$primaryKey
        // ));
        // }

        return $row;
    }

    /**
     * @deprecated Please use the exists method.
     *
     * Checks if an app exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'field' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function appExists($id, $options = null)
    {
        return $this->exists($id, $options);
    }

    /**
     * Checks if an app exists in the database.
     *
     * @param mixed $id      The identifier.
     * @param array $options Contains 'type' which defines what type of
     *                       identifier $id is. Default value is 'field' => 'slug'.
     *
     * @return bool If value exists
     */
    public function exists($id, $options = ['field' => 'slug'])
    {
        return (new RecordExists([
            'table'   => $this->getTable(),
            'field'   => $options['field'] ?? App::$primaryKey,
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * @deprecated Please use the save method instead.
     *
     * Saves an App to the database.
     *
     * If $app->slug is not null then attempts to update an app with that slug
     *
     * @param App $app
     *
     * @throws RuntimeException App does not exist
     *
     * @return App
     */
    public function saveApp(App $app)
    {
        return $this->save($app);
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
     * @return App
     */
    public function save($app)
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
            } while ($this->exists($data['slug']));
            $this->insert($data);

            if (isset($app->tab)) {
                (new Tables())
                    ->getTable('tabApps')
                    ->addCorrelation($app->tab, $data['slug']);
            }
        } elseif ($dbApp = $this->get($slug)) {
            // if ($data['iconPath'] != $dbApp->iconPath) {
            //     if (file_exists(addBasePath($file = $dbApp->iconPath))) {
            //         unlink($file);
            //     }
            // }
            $data['version'] = 1 + (int) $dbApp->version;
            $this->update($data, ['slug' => $slug]);

            if (isset($app->tab)) {
                (new Tables())
                    ->getTable('tabApps')
                    ->addCorrelation($app->tab, $slug);
            }
        } else {
            throw new RuntimeException(sprintf(
                'Cannot update app with identifier %s; does not exist',
                $slug
            ));
        }

        $app->slug = $data['slug'] ?? $slug;

        return $app;
    }

    /**
     * @deprecated Please use the delete method instead.
     *
     * Deletes App and deletes the App's icon.
     *
     * @param string $slug App's slug.
     *
     * @return void
     */
    public function deleteApp($slug)
    {
        $this->delete($slug);
    }

    /**
     * Deletes App and deletes the App's icon.
     *
     * @param string $slug App's slug.
     *
     * @return void
     */
    public function delete($slug)
    {
        if (file_exists($file = $this->get($slug)->iconPath)) {
            unlink($file);
        }

        parent::delete([App::$primaryKey => $slug]);
    }
}
