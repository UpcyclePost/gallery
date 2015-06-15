<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

$di->set('config', function () use ($config)
{
    return $config;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config)
{
    return new DbAdapter([
                             'host'     => $config->database->host,
                             'username' => $config->database->username,
                             'password' => $config->database->password,
                             'dbname'   => $config->database->dbname
                         ]);
});

$di->set('prestashopDb', function () use ($config)
{
	return new DbAdapter([
		                     'host'     => $config->prestashop->database->host,
		                     'username' => $config->prestashop->database->username,
		                     'password' => $config->prestashop->database->password,
		                     'dbname'   => $config->prestashop->database->dbname
	                     ]);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function ()
{
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () use ($config)
{
    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql([
                                                        'host'     => $config->session->database->host,
                                                        'username' => $config->session->database->username,
                                                        'password' => $config->session->database->password,
                                                        'dbname'   => $config->session->database->dbname
                                                    ]);

    $session = new Phalcon\Session\Adapter\Database([
                                                        'db'    => $connection,
                                                        'table' => $config->session->database->table
                                                    ]);

    $session->start();

    return $session;
});

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function ()
{
    return new Phalcon\Flash\Session([
                                         'error'   => 'alert alert-danger',
                                         'warning' => 'alert alert-warning',
                                         'success' => 'alert alert-success',
                                         'notice'  => 'alert alert-info',
                                     ]);
});

$di->set('cookies', function ()
{
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(false);

    return $cookies;
});

$di->set('modelsManager', function ()
{
    return new Phalcon\Mvc\Model\Manager();
});

$di->set('dispatcher', function () use ($di)
{
    $eventsManager = $di->getShared('eventsManager');

    $security = new Security($di);

    $eventsManager->attach('dispatch', $security);
    $eventsManager->attach('dispatch:beforeException', function ($event, $dispatcher, $exception)
    {
        switch ($exception->getCode())
        {
            case Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Phalcon\Mvc\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward(['controller' => 'error', 'action' => 'show404']);

                return false;
        }
    });

    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

// Phalcon-specific plugins
include $config->application->get('servicePluginsDir') . 'view.php';
include $config->application->get('servicePluginsDir') . 'router.php';
include $config->application->get('servicePluginsDir') . 'url.php';

// UP-specific plugins
include $config->application->get('servicePluginsDir') . 'eventManager.php';
include $config->application->get('servicePluginsDir') . 'solr.php';
include $config->application->get('servicePluginsDir') . 'stripe.php';

// Prestashop specific plugins
include $config->application->get('servicePluginsDir') . 'prestashop.php';