<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use App\Form\AppForm;
use Attribute\Form\AttributeForm;
use Group\Form\GroupForm;
use GroupType\Form\GroupTypeForm;
use IpAddress\Form\IpAddressForm;
use OwnerType\Form\OwnerTypeForm;
use Privilege\Form\PrivilegeForm;
use RuntimeException;
use SessionManager\Session;
use Tab\Form\TabForm;
use SessionManager\Tables;
use Traits\HasTables;
use User\Form\UserForm;
use User\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
        if (!Session::isActive()) {
            return $this->redirect()->toRoute('login');
        } else {
            // TODO: SHOW THEIR HOME TAB
            Session::hasPrivilege('auth');

            $user = Session::getUser();
            if ($user)
            {
              $tab = $user->defaultTab();
              if ($tab)
              {
                $apps = $tab->getApps();
              }
              else
              {
                $portalError = true;
                $portalErrorMessage = 'No applications could be located for you. Please contact your technology support staff.';
              }
            }
            else
            {
              $portalError = true;
              $portalErrorMessage = 'We cannot find your user profile. Please contact your technology support staff.';
            }
            if ($portalError)
            {
              return (new ViewModel([
                'portalError' => $portalError,
                'portalErrorMessage' => $portalErrorMessage,
              ]));
            }
            return (new ViewModel([
                'apps' => $tab->getApps(),
            ]))
            ->setTemplate('application/tab/index');
        }
    }

    public function dashboardAction()
    {
        return (new ViewModel([
            'forms' => [
                'user'      => new UserForm(),
                'group'     => new GroupForm(),
                'tab'       => new TabForm(),
                'app'       => new AppForm(),
                'attribute' => new AttributeForm(),
                'grouptype' => new GroupTypeForm(),
                'ipaddress' => new IpAddressForm(),
                'ownertype' => new OwnerTypeForm(),
                'privilege' => new PrivilegeForm(),
            ],
        ]))
        ->setTemplate('application/dashboard/index');
    }

    public function loginoldAction()
    {
        if ($this->getRequest()->isPost()) {
            return $this->loginPostAction();
        }

        if (Session::isActive()) {
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel();
    }

    public function loginAction()
    {
        // initiates SAML SSO login using SimpleSAMLphp SP
        //   7/16/2018 SI
        $as = new \SimpleSAML\Auth\Simple('default-sp');
        $as->requireAuth();
        $attributes = $as->getAttributes();

        $tables = new Tables();

        $user = $tables->getTable('users')->getUser($attributes['mail'][0], ['type' => 'email']);
        if (!$user)
        {
          // add user, privilege, and group
          $user = new User();
          $user->email = $attributes['mail'][0];
          $user->name = $attributes['givenName'][0] . " " . $attributes['sn'][0];
          $user->codist = $attributes['esucc-cdn'][0];
          $usersTable = $tables->getTable('users');
          $userSlug = $usersTable->saveUser($user);
          $tables->getTable('userPrivileges')->addCorrelation($userSlug,'auth');
          $tables->getTable('userGroups')
            ->addCorrelation($userSlug, substr($user->codist,0,7));

        }
        // make session active
        note('Login: Email: '.$email, 'INFO');
        Session::start();
        Session::setUser($user);
        Session::setActiveTime();

        return $this->redirect()->toRoute('home');
        
    }

    public function loginPostAction()
    {
        $email = $this->params()->fromPost('email');
        $password = $this->params()->fromPost('password');

        if (!Session::isActive()) {
            try {
                $tables = new Tables();
                $user = $tables->getTable('users')->getUser($email, ['type' => 'email']);

                note('Login: Email: '.$email, 'INFO');
                Session::start();
                Session::setUser($user);
            } catch (RuntimeException $e) {
                note('Login Attempt: Incorrect Email: '.$email, 'INFO');

                return $this->redirect()->toRoute('login');
            }
        }

        return $this->redirect()->toRoute('home');
    }

    public function logoutAction()
    {
        if ($this->getRequest()->isPost()) {
            if (Session::isActive()) {
                Session::end();
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
}
