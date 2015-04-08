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
		$this->assets->addJs('js/gallery/layout.js');

		$term = \false;

		if ($this->request->isPost())
		{
			$term = $this->__searchService->sanitize($this->request->getPost('term'));
		}

		$users = $this->__searchService->findUsers($term);
		$results = [];

		if ($users)
		{
			foreach ($users AS $user)
			{
				$results[ ] = [
					'url'       => $user->url(),
					'thumbnail' => $user->backgroundThumbUrl(),
					'title'     => $user->user_name,
					'user'      => $user->ik,
					'userName'  => $user->user_name
				];
			}
		}

		$this->view->searchTerm = $term;
		$this->view->results = $results;
	}
}