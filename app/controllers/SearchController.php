<?php

class SearchController extends ControllerBase
{
	/**
	 * @var SearchService
	 */
	protected $__searchService;

	public function initialize()
	{
		parent::initialize();
		$this->view->title = 'Search | UpcyclePost';
		$this->__searchService = new SearchService();
	}

	public function usersAction()
	{
		$this->view->title = 'Profile Gallery | Upcycling Ideas, Articles and Products | UpcyclePost';
		$this->assets->addJs('js/gallery/layout.js');

		$term = \false;

		if ($this->request->isPost())
		{
			$term = $this->__searchService->sanitize($this->request->getPost('term'));
		}

		$users = $this->__searchService->findUsers($term);
		$results = [];

		$prestashopService = new \Up\Services\PrestashopIntegrationService();

		if ($users)
		{
			foreach ($users AS $user)
			{
				$results[] = [
					'url'       => $user->url(),
					'thumbnail' => $user->backgroundThumbUrl(),
					'title'     => $user->user_name,
					'user'      => $user->ik,
					'userName'  => $user->user_name,
					'views'     => $user->views,
					'followers' => $user->followers,
					'shopName'  => $prestashopService->getShopNameByEmail($user->email),
					'shopUrl'   => $user->shopUrl()
				];
			}
		}

		$this->view->searchTerm = $term;
		$this->view->results = $results;
	}
}