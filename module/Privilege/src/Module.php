<?php

namespace Privilege;

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
                Model\PrivilegeTable::class => function ($container)
                {
                    $tableGateway = $container->get(Model\PrivilegeTableGateway::class);
                    return new Model\PrivilegeTable($tableGateway);
                },
                Model\PrivilegeTableGateway::class => function ($container)
                {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Privilege());
                    return new TableGateway('privileges', $dbAdapter, null, $resultSetPrototype);
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
                Controller\PrivilegeController::class => function($container)
                {
                    return new Controller\PrivilegeController(
                        $container->get(Model\PrivilegeTable::class)
                    );
                },
            ],
        ];
    }
}
