<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use SessionManager\Session;
use Traits\HasTables;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ApplicationController extends AbstractActionController
{
    use HasTables;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
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
            'apps' => $this->tables['app']->fetchAll(),
        ]);
    }
}
