<?php

$loader = new \Phalcon\Loader();

require($config->application->autoloadPath);

$loader->registerNamespaces(
    Array(
        'Phalcon\Session\Adapter' => $config->application->pluginsDir,
    )
);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->servicesDir,
        $config->application->eventingDir
    )
)->register();