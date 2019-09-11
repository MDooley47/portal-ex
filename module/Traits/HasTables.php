<?php

namespace Traits;

trait HasTables
{
    private $tables = [];

    private function init($additionalTables = null)
    {
        $this
            ->add('App')
            ->add('Attribute')
            ->add('Group')
            ->add('GroupGroups')
            ->add('GroupType')
            ->add('IpAddress')
            ->add('OwnerTabs')
            ->add('OwnerType')
            ->add('Privilege')
            ->add('Setting')
            ->add('Tab')
            ->add('TabApps')
            ->add('User')
            ->add('UserPrivileges');

        if (isset($additionalTables) && is_array($additionalTables)) {
            foreach ($additionalTables as $table) {
                $this->add($table);
            }
        }
    }

    public function add($name)
    {
        $type = '\\SessionManager\\TableModels\\'.$name.'TableGateway';
        $this->tables[strtolower($name)] = new $type();

        return $this;
    }

    private function addByContainer($name)
    {
        $this->tables[$name] = $this->container->get("{$name}\Model\\{$name}Table");

        return $this;
    }

    public function addTableArray($tables)
    {
        foreach ($tables as $name => $table) {
            $this->addTable($name, $table);
        }
    }

    public function addTable($name, $table)
    {
        $this->tables[strtolower($name)] = $table;

        return $this;
    }

    public function getTable($table)
    {
        return (isset($this->tables[strtolower($table)]))
            ? $this->tables[strtolower($table)]
            : $this->tables;
    }

    private function addTableGateway($name, $ref)
    {
        $this->tables[$name] = $ref;

        return $this;
    }
}
