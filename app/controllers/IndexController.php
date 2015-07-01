<?php

class IndexController extends ControllerBase
{
	public function indexAction()
	{
		$this->view->setMainView('layouts/index');
		$this->assets->addJs('js/gallery/layout.js')
		             ->addJs('js/libraries/backstretch/backstretch.min.js')
		             ->addJs('js/index/index.js');

		$this->view->category = 'New Inspirations';
		$this->view->searchTerm = '';
		$this->view->canonical_url = $this->url->get('');

		$prestashopIntegrationService = new \Up\Services\PrestashopIntegrationService();

		/**
		 * If Prestashop is not in catalogue mode, display products, otherwise display ideas on the front page.
		 */
		if ($prestashopIntegrationService->isPrestashopAvailable())
		{
			$this->view->results = $prestashopIntegrationService->findFrontPageProducts();
		}
		else
		{
			$this->view->results = Post::searchIndex(0, 49, false, false, false, false, ['ik' => 'desc'], 'idea');
		}

		$this->view->title = 'UpcyclePost: Discover Upcycled Products & Post Ideas';

		$showedSubscribe = $this->session->get('showedSubscribe');
		$this->view->showSubscribe = false;
		if (is_null($showedSubscribe) || $showedSubscribe === false)
		{
			$this->session->set('showedSubscribe', time());
			$this->view->showSubscribe = true;

			$this->assets->addJs('js/libraries/ajaxchimp/jquery.ajaxchimp.min.js');
		}
	}
}