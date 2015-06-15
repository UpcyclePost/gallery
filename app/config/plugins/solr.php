<?php

$di->set('solr', function () use ($config)
{
	return new Solarium\Client([
		                           'endpoint' => [
			                           $config->solr->host => [
				                           'host' => $config->solr->host,
				                           'port' => $config->solr->port,
				                           'path' => $config->solr->path
			                           ]
		                           ]
	                           ]);
});