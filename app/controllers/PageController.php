<?php

class PageController extends ControllerBase
{
	public function shopAction()
	{
		$this->view->page_title_text = 'Find items that are one-of-a-kind.';
	}

	public function shareAction()
	{
		$this->view->page_title_text = 'Spread the joy and share inspirations.';
	}

	public function sellAction()
	{
		$this->view->page_title_text = "There's a market for your creations.";
	}
}