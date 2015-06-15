<?php

use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\View;

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config)
{
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir($config->application->viewsDir);

	$view->registerEngines([
		                       ".volt" => 'volt'
	                       ]);

	return $view;
});

$di->set('volt', function ($view, $di) use ($config)
{
	$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

	$volt->setOptions([
		                  "compiledPath"      => $config->application->cacheDir,
		                  'compiledExtension' => '.compiled',
		                  'compiledSeparator' => '_',
		                  'stat'              => true
	                  ]);

	$compiler = $volt->getCompiler();

	// format number
	$compiler->addFilter('pretty', function ($resolvedArgs, $exprArgs)
	{
		return 'Helpers::pretty(' . $resolvedArgs . ')';
	});

	$compiler->addFilter('url', function ($resolvedArgs, $exprArgs)
	{
		return 'Helpers::url(' . $resolvedArgs . ')';
	});

	$compiler->addFilter('formatDate', function ($resolvedArgs, $exprArgs)
	{
		return 'Helpers::dateFormat(' . $resolvedArgs . ')';
	});

	$compiler->addFilter('recency', function($resolvedArgs, $exprArgs)
	{
		return sprintf('Helpers::recency(%s)', $resolvedArgs);
	});

	$compiler->addFilter('truncate', function($resolvedArgs, $exprArgs)
	{
		return sprintf('Helpers::truncate(%s)', $resolvedArgs);
	});

	return $volt;
}, true);