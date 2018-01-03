<?php

namespace Setting;

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
                Model\SettingTable::class => function ($container)
                {
                    $tableGateway = $container->get(Model\SettingTableGateway::class);
                    return new Model\SettingTable($tableGateway);
                },
                Model\SettingTableGateway::class => function ($container)
                {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Setting());
                    return new TableGateway('settings', $dbAdapter, null, $resultSetPrototype);
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
                Controller\SettingController::class => function($container)
                {
                    return new Controller\SettingController(
                        $container->get(Model\SettingTable::class)
                    );
                },
            ],
        ];
    }
}
