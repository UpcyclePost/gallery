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
	 *
	 * @return mixed
	 */
	public function findPosts($searchTerm, $category = \false, $start = \false)
	{
		$term = $this->sanitize($searchTerm);
		$term = ($term && strlen($term) > 0) ? $term : \false;

		// When there is no search term, we want to only sort on "Influence"
		$sort = ($term) ? ['score' => 'desc', 'influence' => 'desc'] : ['ik' => 'desc', 'influence' => 'desc'];
		return Post::searchIndex($start, 50, $category, \false, $term, \false, $sort);
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