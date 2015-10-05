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
		$sort = ($term) ? ['score' => 'desc', 'posted' => 'desc', 'influence' => 'desc'] : ['posted' => 'desc', 'influence' => 'desc'];
		$type = ($isPrestashopAvailable) ? \false : 'idea';
		$result = Post::searchIndex($start, $limit, $category, $userIk, $term, $not, $sort, $type);

		$posts = [];
		$marketIk = [];

		foreach ($result AS $k => $post)
		{
			if ($post['type'] == 'market')
			{
				$marketIk[] = $post['id'];
				// We use id instead of ik because ik is made up in Solr for products
				$posts[$post['id']] = \false;
			}
			else
			{
				$posts[$post['ik']] = $post;
			}
		}

		if (count($marketIk) > 0)
		{
			if ($isPrestashopAvailable)
			{
				$psResult = $prestashopService->findProductsByIds($marketIk);
				foreach ($psResult AS $product)
				{
					// We use ik instead of id because this is the actual ik of the product
					$posts[$product['ik']] = $product;
				}
			}
		}

		$result = [];
		foreach ($posts AS $k => $post)
		{
			if ($post !== \false)
			{
				$result[] = $post;
			}
		}

		return $result;
	}

	public function findProducts($searchTerm, $category = \false, $start = \false, $limit = 50, $userIk = \false, $not = \false)
	{
		$term = $this->sanitize($searchTerm);
		$term = ($term && strlen($term) > 0) ? $term : \false;

		$prestashopService = new \Up\Services\PrestashopIntegrationService();
		$isPrestashopAvailable = $prestashopService->isPrestashopAvailable();

		if (!$isPrestashopAvailable)
		{
			return [];
		}

		// When there is no search term, we want to only sort on "Influence"
		$sort = ($term) ? ['score' => 'desc', 'posted' => 'desc', 'influence' => 'desc'] : ['posted' => 'desc', 'influence' => 'desc'];
		$result = Post::searchIndex($start, $limit, $category, $userIk, $term, $not, $sort, 'market');

		$posts = [];
		$marketIk = [];

		foreach ($result AS $k => $post)
		{
			if ($post['type'] == 'market')
			{
				$marketIk[] = $post['id'];
				// We use id instead of ik because ik is made up in Solr for products
				$posts[$post['id']] = \false;
			}
			else
			{
				$posts[$post['ik']] = $post;
			}
		}

		if (count($marketIk) > 0)
		{
			if ($isPrestashopAvailable)
			{
				$psResult = $prestashopService->findProductsByIds($marketIk);
				foreach ($psResult AS $product)
				{
					// We use ik instead of id because this is the actual ik of the product
					$posts[$product['ik']] = $product;
				}
			}
		}

		$result = [];
		foreach ($posts AS $k => $post)
		{
			if ($post !== \false)
			{
				$result[] = $post;
			}
		}

		return $result;
	}

	public function findIdeas($searchTerm, $category = \false, $start = \false, $limit = 50, $userIk = \false, $not = \false)
	{
		$term = $this->sanitize($searchTerm);
		$term = ($term && strlen($term) > 0) ? $term : \false;

		// When there is no search term, we want to only sort on "Influence"
		$sort = ($term) ? ['score' => 'desc', 'posted' => 'desc', 'influence' => 'desc'] : ['posted' => 'desc', 'influence' => 'desc'];

		return Post::searchIndex($start, $limit, $category, $userIk, $term, $not, $sort, 'idea');
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