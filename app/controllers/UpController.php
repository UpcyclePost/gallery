<?php

class UpController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->view->disable();
	}

	public function configAction()
	{
		$configuration = [
			'url'       => $this->config->application->get('baseUri')
		];

		echo sprintf('const UPMOD_CONFIG = %s;', json_encode($configuration));
	}
}