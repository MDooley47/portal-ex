<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class API2Controller extends AbstractActionController
{
    use HasTables;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }

    public function init()
    {
        $this->makeParams();
        $this->verb = $this->getRequest()->getMethod();
    }

    public function makeParams()
    {
        $this->parameters = [];
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

        $this->handleVerb();

        die();
    }

    public function handleVerb()
    {
        switch ($this->verb) {
            case 'DELETE':
                $this->deleteVerb();
                break;
            case 'GET':
                $this->getVerb();
                break;
            case 'PATCH':
                $this->patchVerb();
                break;
            case 'POST':
                $this->postVerb();
                break;
            case 'PUT':
                $this->putVerb();
                break;
            default:
                die('ERROR UNKNOWN HTTP REQUEST METHOD');
        }
    }

    public function deleteVerb()
    {
    }

    public function getVerb()
    {
        foreach ($this->parameters as $key => $param) {
            $this->getHelper($key, $param);
        }
    }

    public function getHelper($model, $slug)
    {
        $model = resolveModel(singularize($model));
        dd($model::all());
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
