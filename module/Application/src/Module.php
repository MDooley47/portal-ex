<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class Module
{
    const VERSION = '3.0.2';

    public function getConfig()
    {
        return include __DIR__.'/../config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        GlobalAdapterFeature::setStaticAdapter($adapter);
    }
}
