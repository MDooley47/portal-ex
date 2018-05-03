<?php

namespace Application\Controller;

use Application\Controller\AttributeController;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AttributeControllerFactory implements FactoryInterface
{
    use HasTablesFactory;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->addTables();
        return new AttributeController($this->tables);
    }
}
