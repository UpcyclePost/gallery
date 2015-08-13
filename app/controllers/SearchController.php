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
		$this->assets->addJs('js/gallery/layout.js?v=0.26.3');

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
					'views'     => $user->views ?: 0,
					'followers' => $user->followers ?: 0,
					'shopName'  => $prestashopService->getShopNameByEmail($user->email),
					'shopUrl'   => $user->shopUrl()
				];
			}
		}

		$this->view->searchTerm = $term;
		$this->view->results = $results;
	}

	public function countUsersAction()
	{
		$this->view->disable();

		$result = [];

		$users = User::find([
			'conditions' => 'user_name LIKE ?0',
			'bind'       => [sprintf('%s%s', $this->request->get('term'), '%')],
		    'limit'      => 1
		]);

		$result[] = ['id' => md5('4-0'), 'query' => sprintf('user:%s',$this->request->get('term')), 'text' => sprintf('%s in Users', $this->request->get('term')), 'value' => sprintf('Search for %s', $this->request->get('term'))];

		if ($users && count($users) > 0)
		{
			foreach ($users AS $k => $user)
			{
				$result[] = ['id' => md5($user->ik), 'query' => sprintf('user:%s', $user->ik), 'text' => sprintf('%s in Users', $user->user_name), 'value' => $user->user_name];
			}
		}

		echo json_encode($result);
	}

	public function countProductsAction()
	{
		$this->view->disable();

		$result = [];

		$searchService = new SearchService();
		$products = $searchService->findProducts($this->request->get('term'), \false, 0, 5);

		if ($products && count($products) > 0)
		{
			$images = [];
			$categories = [];

			$i = 0;
			foreach ($products AS $product)
			{
				if ($i < 3)
				{
					$images[] = sprintf('<img style="margin-right: 5px; max-height: 30px;" src="%s">', $product['image']);
					$categories[$product['categoryIk']] = $product['categoryTitle'];
				}

				$i++;
			}

			$result[] = ['id' => md5('1-1'), 'query' => $this->request->get('term'), 'text' => sprintf('%s and More', implode('', $images)), 'value' => $this->request->get('term')];
			foreach ($categories AS $categoryIk => $categoryTitle)
			{
				$result[] = ['id' => md5($categoryIk), 'query' => $this->request->get('term'), 'text' => sprintf('Browse %s', $categoryTitle), 'value' => $categoryTitle];
			}
		}
		else
		{
			$result[] = ['id' => md5('1-0'), 'query' => $this->request->get('term'), 'text' => $this->request->get('term'), 'value' => $this->request->get('term')];
		}

		echo json_encode($result);
	}

	public function countShopsAction()
	{
		$this->view->disable();

		$result = [];
		$result[] = ['id' => md5('2-0'), 'query' => sprintf('shop:%s',$this->request->get('term')), 'text' => sprintf('%s in Shops', $this->request->get('term')), 'value' => sprintf('Search for %s', $this->request->get('term'))];

		$prestashopService = new \Up\Services\PrestashopIntegrationService();
		$shops = $prestashopService->findShops($this->request->get('term'));

		if ($shops && count($shops) > 0)
		{
			foreach ($shops AS $k => $shop)
			{
				$result[] = ['id' => md5($shop['title']), 'query' => sprintf('shop:%s', $shop['title']), 'text' => sprintf('%s in Shops', $shop['title']), 'value' => $shop['title']];
			}
		}

		echo json_encode($result);
	}

	public function countIdeasAction()
	{
		$this->view->disable();

		$result = [];

		$searchService = new SearchService();
		$posts = $searchService->findIdeas($this->request->get('term'), \false, 0, 3);

		if ($posts && count($posts) > 0)
		{
			$images = [];

			foreach ($posts AS $post)
			{
				$images[] = sprintf('<img style="margin-right: 5px; max-height: 20px;" src="%s">', Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $post['id'], $post['ik'])));
			}

			$result[] = ['id' => md5('3-1'), 'query' => sprintf('idea:%s', $this->request->get('term')), 'text' => sprintf('%s and More', implode('', $images)), 'value' => $this->request->get('term')];
		}
		else
		{
			$result[] = ['id' => md5('3-0'), 'query' => sprintf('idea:%s', $this->request->get('term')), 'text' => $this->request->get('term'), 'value' => $this->request->get('term')];
		}

		echo json_encode($result);
	}
}
