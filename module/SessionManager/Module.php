<?php

namespace SessionManager;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Empty module for easy managment of traits.
 */
class Module implements ConfigProviderInterface
{
    /**
     * Gets the configuration.
     *
     * @return dictionary
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}

?>
