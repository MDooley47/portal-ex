<?php

namespace Group;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    /**
     * Gets the configuration.
     *
     * @return dictionary
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Gets the service configuration
     *
     * @return dictionary
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\GroupTable::class => function ($container)
                {
                    $tableGateway = $container->get(Model\GroupTableGateway::class);
                    return new Model\GroupTable($tableGateway);
                },
                Model\GroupTableGateway::class => function ($container)
                {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Group());
                    return new TableGateway('groups', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    /**
     * Gets the Controller configuration.
     *
     * @return dictionary
     */
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\GroupController::class => function($container)
                {
                    return new Controller\GroupController(
                        $container->get(Model\GroupTable::class)
                    );
                },
            ],
        ];
    }
}
