<?php

class GalleryController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->view->title = 'Gallery | UpcyclePost';
	}

	private function Posts($slug = false, $start = 0)
	{
		$category = false;
		$this->view->category = 'Idea Gallery';
		if ($slug !== false)
		{
			$categories = Helpers::getCategoryList();
			if (isset($categories[ $slug ]))
			{
				$category = $categories[ $slug ][ 'ik' ];
				$this->view->category = $categories[ $slug ][ 'title' ];

				$this->view->title = $categories[ $slug ][ 'title' ] . ' | Upcycling Ideas, Articles and Products | UpcyclePost';
				$this->view->metaDescription = sprintf("Your place to discover the world's greatest upcycled products and post what inspires you. %s", $categories[ $slug ][ 'title' ]);
				$this->view->og_description = $this->view->metaDescription;
			}
		}

		$searchTerm = \false;
		$searchService = new SearchService();
		$users = \false;

		if ($this->request->isPost())
		{
			$start = $this->request->getPost('start') ?: $start;
			$searchTerm = $searchService->sanitize($this->request->getPost('term') ?: \false);

			if ($searchTerm && $searchTerm !== '')
			{
				$users = $searchService->findUsers($searchTerm);

				$this->view->title = sprintf('Gallery %s | UpcyclePost', str_replace('"', "'", $searchTerm));
				$this->view->metaDescription = sprintf("Your place to discover the world's greatest upcycled products and post what inspires you. %s", str_replace('"', "'", $searchTerm));
				$this->view->og_description = $this->view->metaDescription;
			}
		}

		$this->view->searchTerm = $searchTerm ?: '';

		if ($users)
		{
			if (count($users) == 1)
			{
				return $this->response->redirect(sprintf('profile/view/%s', $users[ 0 ]->ik));
			}
			else
			{
				$this->view->users = $users;
			}
		}

		return $searchService->findPosts($searchTerm, $category, $start);
	}

	public function indexAction($slug = false)
	{
		$this->session->set('redirectTo', $this->router->getRewriteUri());

		$this->assets->addJs('js/gallery/layout.js')
		             ->addJs('js/gallery/index.js');

		$this->view->start = 50;

		if (!$this->request->isPost())
		{
			$this->view->canonical_url = $this->url->get(substr($this->router->getRewriteUri(), 1));
			$startAt = 0;
			if ($slug === \false)
			{
				$this->view->start = 50;
				$startAt = 0;
			}
			$this->view->results = $this->Posts($slug, $startAt);
		}
		else
		{
			$this->view->results = $this->Posts($slug);
		}
	}

	public function moreAction($slug = false)
	{
		$this->view->disable();

		if ($this->request->isPost() && $this->request->isAjax())
		{
			$results = $this->Posts($slug);

			if (count($results) > 0)
			{
				$this->view->partial('partial/gallery/list', ['results' => $results, 'isodiv' => 'iso-' . $this->request->getPost('start')]);
			}
		}
		else
		{
			return $this->response->redirect('gallery');
		}
	}

	public function viewAction($slug = false)
	{
		$this->session->set('redirectTo', $this->router->getRewriteUri());

		$this->view->setLayout('main');

		$this->assets->addJs('js/gallery/view.js')
		             ->addJs('js/gallery/layout.js')
		             ->addJs('js/social/follow.js');

		if (!is_numeric($slug))
		{
			return $this->response->redirect('gallery');
		}

		$ik = intval($slug);

		//Lookup item
		$post = Post::findFirst([
			                        'ik = ?0 AND visible = ?1',
			                        'bind' => [$ik, 1]
		                        ]);

		//$post = Post::findFirst();
		if ($post === false)
		{
			return $this->response->redirect('gallery');
		}

		$enableLike = false;
		$liked = false;
		$following = false;

		if ($this->auth && $this->auth !== false)
		{
			$this->view->isLoggedIn = true;
			$enableLike = true;

			$likes = Likes::findFirst([
				                          'post_ik = ?0 AND user_ik = ?1',
				                          'bind' => [$ik, $this->auth[ 'ik' ]]
			                          ]);

			if ($likes && $likes !== false)
			{
				$enableLike = false;
				$liked = true;
			}

			if ($this->auth[ 'role' ] == 'Admins')
			{
				$this->assets->addJs('js/admin/gallery/view.js');
			}

			// Check if the authenticated user is following the post's user
			$subscriptionService = new SubscriptionService();
			$following = $subscriptionService->userIsSubscribed(
				($post->type == 'market') ? 'shop' : 'user',
				$this->auth[ 'ik' ],
				($post->type == 'market') ? $post->Shop->ik : $post->User->ik
			);
		}

		$this->view->following = $following;
		$this->view->enableLike = $enableLike;
		$this->view->liked = $liked;

		$post->views = $post->views + 1;
		$post->indexed = 0;
		$post->Update();

		$this->view->title = sprintf('%s %s | UpcyclePost', str_replace('"', "'", substr($post->title, 0, 50)), $post->Category->title);
		$this->view->metaDescription = sprintf('%s %s', 'Discover the greatest upcycled products and post what inspires you.', str_replace('"', "'", Helpers::tokenTruncate($post->description, 150)));

		$this->view->og_img = $post->thumbnail('big');
		list($width, $height, $type, $attr) = @getimagesize($this->config->application->thumbnailDir . $post->getImageName('big'));
		$this->view->og_img_size = [$width, $height];
		$this->view->og_description = $this->view->metaDescription;

		$this->view->category = $post->Category->title;
		$searchService = new SearchService();
		$this->view->results = $searchService->findPosts(\false, \false, 0, 150, $post->user_ik);

		$this->view->post = $post;
		$this->view->itemPrevLink = $post->previous();
		$this->view->itemNextLink = $post->next();

		//Disqus
		$timestamp = time();
		$message = base64_encode(json_encode([]));
		if ($this->cookies->has('disqus'))
		{
			$message = $this->cookies->get('disqus');
		}

		$this->view->disqus_hmac = Helpers::dsq_hmacsha1($message . ' ' . $timestamp, $this->config->disqus->private_key);
		$this->view->disqus_message = $message;
		$this->view->disqus_timestamp = $timestamp;

		$this->view->canonical_url = $post->url();
	}
}