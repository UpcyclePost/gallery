<?php

class IndexController extends ControllerBase
{
	public function indexAction()
	{
		$this->view->setMainView('layouts/index');
		$this->assets->addJs('js/gallery/layout.js?v=0.26.3')
		             ->addJs('js/libraries/backstretch/backstretch.min.js')
		             ->addJs('js/index/index.js?v=0.26.3')
		;

		$this->view->category = 'New Inspirations';
		$this->view->searchTerm = '';
		$this->view->canonical_url = $this->url->get('');

		$prestashopIntegrationService = new \Up\Services\PrestashopIntegrationService();

		/**
		 * If Prestashop is not in catalogue mode, display products, otherwise display ideas on the front page.
		 */
		if ($prestashopIntegrationService->isPrestashopAvailable())
		{
			$items = $prestashopIntegrationService->findFrontPageProducts();
		}
		else
		{
			$items = Post::searchIndex(0, 49, false, false, false, false, ['ik' => 'desc'], 'idea');
		}

		$featuredProductBlock = $prestashopIntegrationService->getCMSBlock(18);
		$featuredShopBlock = $prestashopIntegrationService->getCMSBlock(19);
		$featuredProfileBlock = $prestashopIntegrationService->getCMSBlock(20);

		$results = [];

		if ($featuredProductBlock)
		{
			$results[] = ['cms' => \true, 'content' => $featuredProductBlock['content']];
		}

		$i = 0;
		foreach ($items AS $item)
		{
			if ($i == 6 && $featuredShopBlock)
			{
				$results[] = ['cms' => \true, 'content' => $featuredShopBlock['content']];
			}
			elseif ($i == 15 && $featuredProfileBlock)
			{
				$results[] = ['cms' => \true, 'content' => $featuredProfileBlock['content']];
			}

			$results[] = $item;

			$i++;
		}

		$this->view->results = $results;

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
