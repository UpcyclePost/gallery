<?php

class MarketController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->view->title = 'Market | UpcyclePost';
	}
}