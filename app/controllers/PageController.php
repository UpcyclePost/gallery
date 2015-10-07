<?php

class PageController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();

		//$this->view->_mixpanel_page = 'Tweenie';
	}

	public function shopAction()
	{
		$this->view->page_title_text = 'Find items that are one-of-a-kind.';
		$this->view->_mixpanel_page = 'Shop Tweenie';
	}

	public function shareAction()
	{
		$this->view->page_title_text = 'Spread the joy and share inspirations.';
		$this->view->_mixpanel_page = 'Share Tweenie';
	}

	public function sellAction()
	{
		$this->view->page_title_text = "There's a market for your creations.";
		$this->view->_mixpanel_page = 'Sell Tweenie';
	}
}
