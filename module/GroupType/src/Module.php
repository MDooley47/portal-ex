<?php

namespace GroupType;

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
                Model\GroupTypeTable::class => function ($container)
                {
                    $tableGateway = $container->get(Model\GroupTypeTableGateway::class);
                    return new Model\GroupTypeTable($tableGateway);
                },
                Model\GroupTypeTableGateway::class => function ($container)
                {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\GroupType());
                    return new TableGateway('groupTypes', $dbAdapter, null, $resultSetPrototype);
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
                Controller\GroupTypeController::class => function($container)
                {
                    return new Controller\GroupTypeController(
                        $container->get(Model\GroupTypeTable::class)
                    );
                },
            ],
        ];
    }
}
