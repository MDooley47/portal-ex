<?php

namespace Application\Controller;

trait HasTablesFactory
{
    private $tables = [];

    private function addTables($additionalTables = [])
    {
        $this->add('App')
            ->add('Attribute')
            ->add('Group')
            ->add('GroupType')
            ->add('IpAddress')
            ->add('Privilege')
            ->add('Setting')
            ->add('Tab')
            ->add('User');

        foreach ($additionalTables as $table)
        {
            $this->add($table);
        }
    }

    private function add($name)
    {
        $this->tables[$name] = $this->container->get("{$name}\Model\\{$name}Table");;

        return $this;
    }
}
