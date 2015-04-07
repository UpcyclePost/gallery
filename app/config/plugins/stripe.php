<?php

$di->set('stripe', function () use ($config)
{
	return $config->stripe;
});