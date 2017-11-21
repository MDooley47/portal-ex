<?php

namespace App;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGatewa\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AppTable::class => function ($container)
                {
                    $tableGateway = $container->get(Model\AppTableGateway::class);
                    return new Model\AppTable($tableGateway);
                },
                Model\AppTableGateway::class => function ($container)
                {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\App());
                    return new TableGateway('app', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AppController::class => function($container)
                {
                    return new Controller\AppController(
                        $container->get(Model\AppTable::class)
                    );
                },
            ],
        ];
    }
}
