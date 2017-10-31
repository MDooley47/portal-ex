<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function sessionActive(Container $sessionContainer)
    {
    	// tests whether a session is valid/active or not
    	// 10/20/2017 SI
    	
    	if (empty($sessionContainer))
    	{
    		return (false);
    	}
    	
    	if (($sessionContainer->activeTime > (time() - 3600)) && 
    			$sessionContainer->sessionLevel > 0 &&
    			$sessionContainer->userId > 0)
    	{
    		// valid
    		return (true);
    	}
    	
    	return (false);
    }
    
	public function indexAction()
    {
    	$newSession = false;
    	try 
    	{
    		$sessionContainer = new Container('pexSession');
    	}
    	catch (\Exception $ex)
    	{
    		echo "<br>Session Exception: " . $ex->getMessage();
    		echo "<br>_SESSION: ";
    		var_dump($_SESSION);
    		exit();
    	}
    	
    	// test for active session
    	if (!$this->sessionActive($sessionContainer))
    	{
    		$sessionContainer->userId = 1;
    		$sessionContainer->sessionLevel = 10;
    		$sessionContainer->activeTime = time();
    		$newSession = true;
    	}
    	
    	return new ViewModel([
    			'userId' => $sessionContainer->userId,
    			'sessionLevel' => $sessionContainer->sessionLevel,
    			'activeTime' => $sessionContainer->activeTime,
    			'newSession' => $newSession,
    	]);
    }
}
