<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use RuntimeException;

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
        if (! Session::isActive())
        {
            return $this->redirect()->toRoute('login');
        }
        else {
            // TODO: SHOW THEIR DASHBOARD
            Session::hasPrivilege('auth');
        }

        return new ViewModel([
            'apps' => $this->getTable('app')->fetchAll(),
        ]);
    }

    public function loginAction()
    {
        if ($this->getRequest()->isPost())
        {
            return $this->loginPostAction();
        }

        if (Session::isActive())
        {
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel();
    }

    public function loginPostAction()
    {
        $email = $this->params()->fromPost('email');
        $password = $this->params()->fromPost('password');

        if (! Session::isActive())
        {
            try
            {
                $user = $this->getTable('user')->getUser($email, ['type' => 'email']);

                note("Login: Email: " . $email, "INFO");
                Session::start();
                Session::setUser($user);
            }
            catch (RuntimeException $e)
            {
                note("Login Attempt: Incorrect Email: " . $email, "INFO");
                return $this->redirect()->toRoute('login');
            }
        }

        return $this->redirect()->toRoute('home');
    }

    public function logoutAction()
    {
        if ($this->getRequest()->isPost())
        {
            if (Session::isActive())
            {
                Session::end();
            }
        }
        else
        {
            return $this->redirect()->toRoute('home');
        }
    }
}
