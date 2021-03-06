<?php

namespace Eye4web\Zf2Abac\Factory\Assertion;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Eye4web\Zf2Abac\Assertion\AssertionPluginManager;

class AssertionPluginManagerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return AssertionPluginManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $config */
        $config = $serviceLocator->get('Config')['eye4web_abac']['assertion_manager'];

        $pluginManager = new AssertionPluginManager(new Config($config));
        $pluginManager->setServiceLocator($serviceLocator);

        return $pluginManager;
    }
}
