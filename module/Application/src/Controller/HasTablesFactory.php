<?php

namespace Application\Controller;

use Application\Controller\ApplicationController;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class HasTablesFactory implements FactoryInterface
{
    private $tables = [];

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->add('App')
            ->add('Attribute')
            ->add('Group')
            ->add('GroupType')
            ->add('IpAddress')
            ->add('Privilege')
            ->add('Setting')
            ->add('Tab')
            ->add('User');

        return new ApplicationController($this->tables);
    }

    private function add($name)
    {
        $this->tables[$name] = $this->container->get("{$name}\Model\\{$name}Table");;

        return $this;
    }
}
