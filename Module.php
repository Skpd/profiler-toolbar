<?php

namespace Skpd\ProfilerToolbar;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Config\Config;
use ZendDeveloperTools\ProfilerEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, BootstrapListenerInterface
{
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\EventManager\EventManager $eventManager */
        $eventManager  = $e->getTarget()->getEventManager();

        $eventManager->attach(ProfilerEvent::EVENT_PROFILER_INIT, function () {
            xhprof_enable(XHPROF_FLAGS_MEMORY);
        });
    }

    public function getConfig()
    {
        $config = new Config(array());

        if (is_dir(__DIR__ . '/config')) {
            $iterator  = new \RegexIterator(new \DirectoryIterator(__DIR__ . '/config'), '#\.config\.php$#i');
            foreach ($iterator as $file) {
                /** @var $file \DirectoryIterator */
                if ($file->isReadable()) {
                    $subConf = new Config(include $file->getRealPath());
                    $config->merge($subConf);
                }
            }
        }

        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }
}