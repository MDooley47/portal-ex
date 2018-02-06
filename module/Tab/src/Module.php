<?php

namespace Tab;

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
                Model\TabTable::class => function ($container)
                {
                    $tableGateway = $container->get(Model\TabTableGateway::class);
                    return new Model\TabTable($tableGateway);
                },
                Model\TabTableGateway::class => function ($container)
                {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Tab());
                    return new TableGateway('tabs', $dbAdapter, null, $resultSetPrototype);
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
                Controller\TabController::class => function($container)
                {
                    return new Controller\TabController(
                        $container->get(Model\TabTable::class)
                    );
                },
            ],
        ];
    }
}
