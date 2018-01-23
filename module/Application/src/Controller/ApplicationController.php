<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use App\Model\AppTable;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ApplicationController extends AbstractActionController
{

    private $appTable;

    public function __construct(AppTable $appTable)
    {
        $this->appTable = $appTable;
    }

    public function sessionActive()
    {
        // tests whether a session is valid/active or not
        // 10/20/2017 SI

        session_start();
        if (isset($_SESSION['activeTime']) && isset($_SESSION['sessionLevel']) && isset($_SESSION['userId']))
        {
            // variables are present in the session, so test them
            if (($_SESSION['activeTime'] > (time() - 3600)) &&
                $_SESSION['sessionLevel'] > 0 &&
                $_SESSION['userId'] > 0)
            {
                // valid
                return (true);
            }
        }

        return (false);
    }

    public function indexAction()
    {
        $newSession = false;

//         try
//         {
//             $sessionContainer = new Container('pexSession');
//         }
//         catch (\Exception $ex)
//         {
//             echo "<br>Session Exception: " . $ex->getMessage();
//             $tmpdir = scandir("/tmp");
//             echo "<br>tmp dir: ";
//             foreach ($tmpdir as $l)
//             {
//                 echo "<br>" . $l;
//             }
//             echo "<br>_SESSION: ";
//             var_dump($_SESSION);
//             exit();
//         }

        // test for active session
        if (!$this->sessionActive())
        {
            // new session
            $newSession = true;
            $_SESSION['activeTime'] = time();
            $_SESSION['sessionLevel'] = 10;
            $_SESSION['userId'] = 1;
        }

//         if (!$this->sessionActive($sessionContainer))
//         {
//             $sessionContainer->userId = 1;
//             $sessionContainer->sessionLevel = 10;
//             $sessionContainer->activeTime = time();
//             $newSession = true;
//         }

        var_dump($this->appTable-fetchAll());
        die();

        return new ViewModel([
            'userId' => $_SESSION['userId'],
            'sessionLevel' => $_SESSION['sessionLevel'],
            'activeTime' => $_SESSION['activeTime'],
            'newSession' => $newSession,
        ]);
    }
}
