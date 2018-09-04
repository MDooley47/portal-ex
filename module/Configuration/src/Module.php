<?php

namespace Configuration;

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
        return include __DIR__.'/../config/module.config.php';
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
                Controller\ConfigurationController::class => function ($container) {
                    return new Controller\ConfigurationController(
                        $container->get(Model\ConfigurationTable::class)
                    );
                },
            ],
        ];
    }
}
