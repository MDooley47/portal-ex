<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TabControllerFactory implements FactoryInterface
{
    use HasTablesFactory;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->addTables();

        return new TabController($this->tables);
    }
}
