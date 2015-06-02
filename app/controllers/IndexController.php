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
		/*$results = Post::searchIndex(0, 49, false, false, false, false, ['ik' => 'desc', 'influence' => 'desc']);
		$_results = [];

		$i = 0;
		foreach ($results AS $result)
		{
			//if ($i == 6) {
				//$_results[] = ['promotion' => true, 'url' => 'http://www.upcyclepost.com/blog/make-upcycled-holiday-contest/', 'thumbnail' => 'holidays.jpg', 'title' => 'Make It An Upcycled Holiday Contest'];
			//}
			$_results[ ] = $result;
			$i++;
		}
		unset($results);*/
		$this->view->results = (new \Up\Services\PrestashopIntegrationService())->findRecentProducts();

		$this->view->title = 'UpcyclePost: Discover Upcycled Products & Post Ideas';

		$showedSubscribe = $this->session->get('showedSubscribe');
		$this->view->showSubscribe = false;
		if (is_null($showedSubscribe) || $showedSubscribe === false)
		{
			$this->session->set('showedSubscribe', time());
			$this->view->showSubscribe = true;

			$this->assets->addJs('js/libraries/ajaxchimp/jquery.ajaxchimp.min.js');
		}

		$prestashopService = new \Up\Services\PrestashopIntegrationService();
		$prestashopService->findRecentProducts();
	}
}