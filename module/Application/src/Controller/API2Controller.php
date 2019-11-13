<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use SessionManager\Session;
use Traits\HasTables;
use Zend\Http\Headers;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class API2Controller extends AbstractActionController
{
    use HasTables;

    public $parameters = [];
    public $urlParameters = [];
    public $postParameters = [];

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }

    public function init()
    {
        $this->makeParams();

        $this->verb = $this->getRequest()->getMethod();

        Session::start();

        $this->response = new Response();
        $this->headers = new Headers();
        $this->headers->addHeaderLine('Content-Type', 'text/json');
        $this->response->setHeaders($this->headers);
    }

    public function makeParams()
    {
        $this->urlParameters = $this->params()->fromQuery();
        $this->postParameters = $this->params()->fromPost();

        $i = 0;
        $last = null;
        foreach ($this->params()->fromRoute() as $key => $segment) {
            if (strpos($key, 'segment') !== false) {
                if ($i++ % 2 == 0) {
                    $this->parameters[$segment] = null;
                } else {
                    $this->parameters[$last] = $segment;
                }
                $last = $segment;
            }
        }
    }

    public function apiAction()
    {
        $this->init();

        $content = $this->handleVerb();

        $response = $this->response->setContent($content);

        return $response;
    }

    public function handleVerb()
    {
        $content = null;

        switch ($this->verb) {
            case 'DELETE':
                $content = $this->deleteVerb();
                break;
            case 'GET':
                $content = $this->getVerb();
                break;
            case 'PATCH':
                $content = $this->patchVerb();
                break;
            case 'POST':
                $content = $this->postVerb();
                break;
            case 'PUT':
                $content = $this->putVerb();
                break;
            default:
                die('ERROR UNKNOWN HTTP REQUEST METHOD');
        }

        return json_encode($content);
    }

    public function resolveModelSlug($model, $slug)
    {
        $model = resolveModel(singularize($model));
        return is_array($output = $model::find($slug)) ? $output : $output->getArrayCopy();
    }

    public function deleteVerb()
    {
        $model = array_key_first($this->parameters);
        $slug = $this->parameters[$model];

        if (!Session::hasPrivilege('sudo')
            && isset($model)
            && isset($slug)
        ) return False;

        return castModel($model, $this->resolveModelSlug($model, $slug))->delete();
    }

    public function getVerb()
    {
        $outputs = [];
        foreach ($this->parameters as $key => $param) {
            if (   (($key == 'users') && isset($param) &&
                        (!(Session::hasPrivilege('sudo') || $param === Session::get('userSlug')))
                   )
                || (($key == 'groups')     && isset($param) && !Session::hasPrivilege('auth', $param))
            ) continue;
            else if (empty($param)) {
                $resolvedModel = resolveModel(singularize($key));
                $outputs[$key] = [];
                $limit = $this->urlParameters['limit'] ?? 50;
                $offset = $this->urlParameters['offset'] ?? 0;
                foreach ($resolvedModel::all($limit, $offset) as $model) {
                    if ($model->privilegeCheck()) {
                        array_push($outputs[$key], $model->getArrayCopy());
                    }
                }
            } else {
                $requestedModel = $this->resolveModelSlug($key, $param);
                $outputs[$key] = $requestedModel;
            }
        }
        return $outputs;
    }

    public function patchVerb()
    {
    }

    public function postVerb()
    {
    }

    public function putVerb()
    {
    }
}
