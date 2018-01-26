<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use App\Model\AppTable;
use SessionManager\Session;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ApplicationController extends AbstractActionController
{

    {
    }

    public function indexAction()
    {
        // activate session if not active
        if (!Session::active())
        {
            $newSession = true;
            Session::add('activeTime', time());
            Session::add('userId', 1);
        }

        return new ViewModel([
        ]);
    }
}
