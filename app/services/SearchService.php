<?php

class SearchService
{
	protected $config;

	public function __construct()
	{
		$this->config = Phalcon\DI::getDefault()->get('config');
	}

	/**
	 * @param       $searchTerm
	 * @param mixed $category
	 * @param mixed $start
	 * @param mixed $limit
	 * @param mixed $userIk
	 * @param mixed $not
	 *
	 * @return mixed
	 */
	public function findPosts($searchTerm, $category = \false, $start = \false, $limit = 50, $userIk = \false, $not = \false)
	{
		$term = $this->sanitize($searchTerm);
		$term = ($term && strlen($term) > 0) ? $term : \false;

		$prestashopService = new \Up\Services\PrestashopIntegrationService();
		$isPrestashopAvailable = $prestashopService->isPrestashopAvailable();

		// When there is no search term, we want to only sort on "Influence"
		$sort = ($term) ? ['type' => 'desc', 'score' => 'desc', 'influence' => 'desc'] : ['type' => 'desc', 'ik' => 'desc', 'influence' => 'desc'];
		$type = ($isPrestashopAvailable) ? \false : 'idea';
		$result = Post::searchIndex($start, $limit, $category, $userIk, $term, $not, $sort, $type);

		$posts = [];
		$products = [];

		$marketIk = [];
		foreach ($result AS $post)
		{
			if ($post['type'] == 'market')
			{
				$marketIk[] = $post['id'];
			}
			else
			{
				$posts[] = $post;
			}
		}

		if (count($marketIk) > 0)
		{
			if ($isPrestashopAvailable)
			{
				$products = $prestashopService->findProductsByIds($marketIk);
			}
		}

		return array_merge($products, $posts);
	}

	/**
	 * @param $searchTerm
	 *
	 * @return mixed
	 */
	public function findUsers($searchTerm)
	{
		if ($searchTerm)
		{
			$term = $this->sanitize($searchTerm);

			if ($term && strlen($term) > 0)
			{
				return User::find([
					                  'conditions' => 'user_name LIKE ?0',
					                  'bind'       => [sprintf('%s%s', $term, '%')]
				                  ]);
			}
		}

		return User::find([
			                  'conditions' => 'custom_background IS NOT NULL'
		                  ]);
	}

	/**
	 * @param $term
	 *
	 * @return string|bool
	 */
	public function sanitize($term)
	{
		return $term ? preg_replace('/[^\w\s\'_]+/', '', $term) : \false;
	}
}