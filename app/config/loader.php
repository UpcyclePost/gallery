<?php

$loader = new \Phalcon\Loader();

require($config->application->autoloadPath);
require($config->application->psWebServicePath);

$loader->registerNamespaces(
	Array(
		'Phalcon\Session\Adapter' => $config->application->pluginsDir,
		'Up\Services'             => $config->application->servicesDir
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