<?php
use Phalcon\Events\Manager as EventsManager;

$eventManager = new EventsManager();

//Set up the events manager to collect responses
$eventManager->collectResponses(true);

// Attach events to the eventManager
$eventManager->attach('post', new PostEventListener());
$eventManager->attach('shop', new ShopEventListener());
$eventManager->attach('user', new UserEventListener());

$di->set('eventManager', function() use ($eventManager)
{
	return $eventManager;
});