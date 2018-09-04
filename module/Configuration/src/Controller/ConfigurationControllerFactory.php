<?php

namespace Configuration\Controller;

use Application\Controller\HasTablesFactory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConfigurationControllerFactory implements FactoryInterface
{
    use HasTablesFactory;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->addTables();

        return new ConfigurationController($this->tables);
    }
}
