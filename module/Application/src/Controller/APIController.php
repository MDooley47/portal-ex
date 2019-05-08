<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use App\Model\App;
use SessionManager\Session;
use Traits\HasTables;
use Zend\Http\Headers;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class APIController extends AbstractActionController
{
    use HasTables;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }

    public function indexAction()
    {
        $response = new Response();
        $headers = new Headers();
        $headers->addHeaderLine('Content-Type', 'text/json');
        $response->setHeaders($headers);
        if (!Session::isActive())
        {
          // must be logged in
          $response->setStatusCode(401);
          return ($response);
        }
        if (!Session::hasPrivilege('sudo'))
        {
          // must have sudo privilege to use the API
          $response->setStatusCode(401);
          return ($response);
        }

        if ($this->getRequest()->isGet()) {
            return $this->handlePost($response);

            return $this->handleGet($response);
        } elseif ($this->getRequest()->isPost()) {
            return $this->handlePost($response);
        }
    }

    public function handleGet($response)
    {
        $response->setContent('{"r_type": "get"}');

        return $response;
    }

    public function handlePost($response)
    {
        $model = strtolower($this->getRequest()->getQuery('m'));
        $action = strtolower($this->getRequest()->getQuery('a'));
        $id = $this->getRequest()->getQuery('id');
        $data = $this->getRequest()->getPost()->toArray();

        $content = [];

        if (empty($model)) {
            return 400;
        }


        if (empty($action) && empty($id)) {
            $this->listModels($content, $model);
        } elseif (empty($action) && isset($id)) {
            $this->viewModel($content, $model, $id);
          } elseif (isset($action) && $action == 'listrelated') {
              $this->listRelated($content, $model, $id);
          } elseif (isset($action) && $action == 'add') {
            $this->addModel($content, $model, $data);
        } elseif (isset($action) && $action == 'edit') {
            $this->editModel($content, $model, $id, $data);
        } elseif (isset($action) && $action == 'form') {
            $this->formModel($content, $model);
        } elseif (isset($action) && $action == 'delete') {
            $this->deleteModel($content, $model, $id);
        } elseif (isset($action) && $action == 'updaterelated') {
            $data = json_decode(file_get_contents("php://input"));
            $this->updateRelated($content, $model, $id, $data);
        }

        if (empty($content) && $action != 'listrelated') {
            return 404;
        } else {
            $response->setContent(json_encode($content));
        }

        return $response;
    }

    public function listModels(&$content, $m)
    {
        $content[$this->plural($m)] = [];

        $models = $this->getTable($m)->fetchAll();

        foreach ($models as $model) {
            array_push($content[$this->plural($m)], $model->getArrayCopy());
        }
    }

    public function listRelated(&$content, $m, $id)
    {
      $table = $this->getTable($m);
      $models = $table->fetchRelated($id);
      foreach ($models as $model) {
        $content[$m][] = $model;
      }
    }

    public function updateRelated(&$content, $m, $id, $data)
    {
        $table = $this->getTable($m);
        if ($table->deleteRelated($id) < 0)
        {
          $content[$m] = null;
          return(false);
        }

        if ($table->addRelated($data) > -1)
        {
          $content[$m] = $data;
          return(true);
        }

        return(false);
    }

    public function viewModel(&$content, $model, $id)
    {
        $table = $this->getTable($model);

        if ($table->exists($id)) {
            $content[$model] = $table->get($id)->getArrayCopy();
        }
    }

    public function addModel(&$content, $model, $data)
    {
        $content['success'] = true;

        if ($model == 'app') {
            $data['iconPath'] = App::saveIconFromBase64($data['icon']);
            $data['version'] = $data['version'] ?? 0;
            unset($data['icon']);
        }

        try {
            $table = guaranteeUniversalTableGateway($this->getTable($model));
            $added = $table->add($data);
            $content[$model] = $added->getArrayCopy();
        } catch (\Exception $e) {
            $content['success'] = false;
            if (env('debug')) {
                $content['exception'] = [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ];
            }
        }
    }

    public function editModel(&$content, $m, $id, $data)
    {
      if ($m == 'app' && !empty($data['icon'])) {
          $data['iconPath'] = App::saveIconFromBase64($data['icon']);
          $data['version'] = $data['version'] ?? 0;
          unset($data['icon']);
      }

      $table = $this->getTable($m);
      $model = $table->get($id);

      $model->exchangeArray($data);
      $model->slug = $id;
      $table->save($model);

      $content[$m] = $model->getArrayCopy();
    }

    public function formModel(&$content, $m)
    {
        $content['success'] = true;

        try {
            $model = resolveModel($m);
            $content[$m] = $model::$form;
        } catch (\Exception $e) {
            $content['success'] = false;

            if (env('debug')) {
                $content['exception'] = [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ];
            }
        }
    }

    public function deleteModel(&$content, $model, $id)
    {
        $content['success'] = true;

        try {
            $this->getTable($model)->delete($id);
        } catch (\Exception $e) {
            $content['success'] = false;
            if (env('debug')) {
                $content['exception'] = [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ];
            }
        }
    }

    public function plural($model)
    {
        switch (strtolower($model)) {
            case 'user':
                return 'users';
            case 'group':
                return 'groups';
            case 'tab':
                return 'tabs';
            case 'app':
                return 'apps';
            case 'attribute':
                return 'attributes';
            case 'grouptype':
                return 'grouptypes';
            case 'ipaddress':
                return 'ipaddresses';
            case 'ownertype':
                return 'ownertypes';
            case 'privilege':
                return 'privileges';
            default:
                return $model;
        }
    }
}
