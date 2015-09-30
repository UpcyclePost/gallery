<?php

class BrowseController extends ControllerBase
{
	const ITEMS_PER_PAGE = 50;

	/**
	 * @var SearchService
	 */
	protected $__searchService;

	public
	function initialize()
	{
		parent::initialize();

		$this->session->set('redirectTo', $this->router->getRewriteUri());
		$this->__searchService = new SearchService();
	}

	public
	function productsAction($category = \false)
	{
		$this->view->page_header_text = $this->__getCategoryName($category) ?: 'Products';
		$this->view->results = $this->__searchService->findProducts($this->__getSearchTerm(), $this->__getCategoryId($category), $this->__getOffset(), BrowseController::ITEMS_PER_PAGE);
	}

	public
	function shopsAction()
	{
		$this->view->page_header_text = 'Shops';
		$prestashopService = new \Up\Services\PrestashopIntegrationService();

		$this->view->results = $prestashopService->findShops();
	}

	public
	function ideasAction($category = \false)
	{
		$this->view->page_header_text = $this->__getCategoryName($category) ?: 'Ideas';
		$this->view->results = $this->__searchService->findIdeas($this->__getSearchTerm(), $this->__getCategoryId($category), $this->__getOffset(), BrowseController::ITEMS_PER_PAGE);
	}

	public
	function membersAction()
	{
		$this->view->page_header_text = 'Members';
		$users = $this->__searchService->findUsers($this->__getSearchTerm());

		$results = [];

		$prestashopService = new \Up\Services\PrestashopIntegrationService();

		if ($users)
		{
			foreach ($users AS $user)
			{
				$results[] = [
					'_user'     => \true,
					'url'       => $user->url(),
					'thumbnail' => $user->backgroundThumbUrl(),
					'title'     => $user->user_name,
					'user'      => $user->ik,
					'userName'  => $user->user_name,
					'views'     => $user->views ?: 0,
					'followers' => $user->followers ?: 0,
					'shopName'  => $prestashopService->getShopNameByEmail($user->email),
					'shopUrl'   => $user->shopUrl()
				];
			}
		}

		$this->view->results = $results;
	}

	protected
	function __getSearchTerm()
	{
		if ($this->request->isPost())
		{
			return SearchService::sanitize($this->request->getPost('term') ?: \false);
		}

		return \false;
	}

	protected
	function __getOffset()
	{
		$offset = 0;

		if ($this->request->isPost())
		{
			$offset = $this->request->getPost('start') ?: $offset;
		}

		return is_numeric($offset) ? $offset : 0;
	}

	protected
	function __getCategoryId($categorySlug)
	{
		if ($categorySlug !== false)
		{
			$categories = Helpers::getCategoryList();
			if (isset($categories[ $categorySlug ]))
			{
				return $categories[ $categorySlug ][ 'ik' ];
			}
		}

		return \false;
	}

	protected
	function __getCategoryName($categorySlug)
	{
		if ($categorySlug !== false)
		{
			$categories = Helpers::getCategoryList();
			if (isset($categories[ $categorySlug ]))
			{
				return $categories[ $categorySlug ][ 'title' ];
			}
		}

		return '';
	}
}