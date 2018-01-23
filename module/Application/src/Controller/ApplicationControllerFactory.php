<?php

namespace Application\Controller;

use Application\Controller\ApplicationController;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;

class ApplicationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $appTable = $container->get('App\Model\AppTable');

        return new ApplicationController($appTable);
    }
}
