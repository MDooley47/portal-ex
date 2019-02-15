<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class APIControllerFactory implements FactoryInterface
{
    use HasTablesFactory;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->addTables();

        return new APIController($this->tables);
    }
}
