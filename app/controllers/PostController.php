<?php

class PostController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();

		$this->view->_mixpanel_page = 'Post Idea';
	}

	public function indexAction()
	{

	}

	public function ideaAction()
	{
		if ($this->session->has('editing'))
		{
			$this->session->remove('editing');
		}

		$this->view->title = 'Post Idea | UpcyclePost';

		$this->assets->addJs('js/libraries/dropzone/dropzone.js')
		             ->addJs('js/post/idea.js');
	}

	/**
	 * Saves the uploaded thumbnails and creates a new post.
	 *
	 * @return mixed
	 */
	public function uploadAction()
	{
		if (!$this->request->isPost() || !$this->request->isAjax())
		{
			// This function only accepts POSTed requests
			return $this->response->redirect('post/idea');
		}
		else
		{
			// Nothing to see here
			$this->view->disable();

			if (!$this->request->hasFiles())
			{
				echo json_encode(['success' => false]);
			}
			else
			{
				foreach ($this->request->getUploadedFiles() AS $file)
				{
					// Save
					$postModel = new Post();
					$postModel->created = date('Y-m-d H:i:s');
					$postModel->visible = 0;
					$postModel->indexed = 0;
					$postModel->type = 'idea';
					$postModel->status = 'created';
					$postModel->views = 0;
					$postModel->likes = 0;
					$postModel->comments = 0;
					$postModel->shares = 0;
					$postModel->reports = 0;

					if (isset($this->auth[ 'ik' ]))
					{
						$postModel->user_ik = $this->auth[ 'ik' ];
					}

					if ($postModel->save() != false)
					{
						$postModel->id = Helpers::createShortCode($postModel->ik);
						$postModel->save();

						$postService = new PostService();
						$postService->createThumbnails($file, $postModel->ik, $postModel->id);

						$this->session->set('editing', ['ik' => $postModel->ik, 'id' => $postModel->id]);

						// Return result
						echo json_encode(['success' => true, 'data' => ['preview' => $postModel->thumbnail('big')]]);
					}
					else
					{
						echo json_encode(['success' => false, 'data' => ['messages' => $postModel->getMessages()]]);
					}
					break;
				}
			}
		}
	}

	public function detailsAction()
	{
		if ($this->request->isPost())
		{
			$this->response->redirect('post/details');
		}

		$this->assets->addJs('js/libraries/tagmanager/tagmanager.js')
		             ->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/post/details.js');

		$categories = [];
		foreach (Helpers::getCategoryList() AS $category)
		{
			$categories[ $category[ 'ik' ] ] = ['title' => $category[ 'title' ], 'children' => []];
		}

		$this->view->categories = $categories;

		if (($post = $this->session->get('editing')) != null)
		{
			$postModel = Post::findFirst($post[ 'ik' ]);
			if ($postModel !== false)
			{
				$postModel->status = 'uploaded';
				$postModel->update();
			}
			$this->view->thumbnail = $postModel->thumbnail('small');
		}
		else
		{
			$this->flash->error('Uh oh, your post couldn\'t be saved, please try your upload again.');
			$this->response->redirect('post/idea');
		}
	}

	public function submitAction()
	{
		if (($post = $this->session->get('editing')) != null)
		{
			if ($this->request->isPost())
			{
				$postModel = Post::findFirst($post[ 'ik' ]);
				if ($postModel !== false)
				{
					if ($postModel->status == 'uploaded')
					{
						$postModel->description = strip_tags($this->request->getPost('description'));
						$postModel->title = strip_tags($this->request->getPost('title'));
						$postModel->tags = strip_tags($this->request->getPost('hidden-tags'));
						$postModel->status = 'submitted';

						$category = $this->request->getPost('category');
						if (is_numeric($category))
						{
							$postModel->category_ik = $category;
						}

						$postModel->update();

						return $this->response->redirect('post/post');
					}
					else
					{
						// The user may have skipped a step
						$this->flash->error('Uh oh, your post couldn\'t be saved, please try again.');

						return $this->response->redirect('post/details');
					}
				}
				else
				{
					// Something somewhere went wrong, this post doesn't exist!  Redirect to upload?
					$this->flash->error('Uh oh, your post couldn\'t be saved, please try again.');

					return $this->response->redirect('post/details');
				}
			}
		}

		return $this->response->redirect('post/idea');
	}

	public function postAction()
	{
		$this->view->disable();
		if (($post = $this->session->get('editing')) != null)
		{
			$postModel = Post::findFirst($post[ 'ik' ]);
			if ($postModel !== false)
			{
				if ($postModel->status == 'submitted')
				{
					$postModel->user_ik = $this->session->get('auth')[ 'ik' ];
					$postModel->visible = 1;
					$postModel->indexed = 1;
					$postModel->status = 'posted';

					$postModel->index();

					if ($postModel->update())
					{
						$this->session->remove('editing');

						// Notify any event listeners
						$this->eventManager->fire('post:afterPostHasBeenCreated', $this, ['user' => $this->auth[ 'ik' ], 'model' => $postModel]);

						return $this->response->redirect($postModel->url());
					}
				}
			}
		}

		$this->flash->error('Uh oh, your post couldn\'t be saved, please try again.');

		return $this->response->redirect('post/details');
	}

	public function likeAction($post_ik)
	{
		$this->view->disable();

		$success = false;

		$auth = $this->session->get('auth');

		if ($auth && $auth !== false && $this->request->isAjax())
		{
			if (is_numeric($post_ik))
			{
				$ik = intval($post_ik);

				//Lookup item
				$post = Post::findFirst([
					                        'ik = ?0 AND visible = ?1',
					                        'bind' => [$ik, 1]
				                        ]);

				if ($post)
				{
					$likesModel = new Likes();
					$likesModel->post_ik = $ik;
					$likesModel->user_ik = $auth[ 'ik' ];
					$likesModel->ts = date('Y-m-d H:i:s');
					if ($likesModel->save())
					{
						$success = true;

						$post->likes = $post->likes + 1;
						$post->indexed = 0;
						$post->update();

						// Notify any event listeners.
						$this->eventManager->fire('post:afterPostHasBeenLiked', $this, ['user' => $auth[ 'ik' ], 'model' => $post]);
					}
				}
			}
		}

		echo json_encode(['success' => $success, 'data' => []]);
	}

	public function reportAction($post_ik)
	{
		$this->view->disable();

		if (is_numeric($post_ik))
		{
			$ik = intval($post_ik);

			$post = Post::findFirst([
				                        'ik = ?0 AND visible = ?1',
				                        'bind' => [$ik, 1]
			                        ]);

			if ($post)
			{
				$post->reports = $post->reports + 1;
				$post->indexed = 0;
				$post->update();
				//Report
				Helpers::sendEmail('info@upcyclepost.com', 'Post ' . $post_ik . ' has been flagged by the community.', 'Please check the post at ' . $post->url() . ' and moderate as necessary.');
			}
		}
	}

	public function shareAction($post_ik)
	{
		$this->view->disable();

		$success = false;

		if ($this->request->isPost() && $this->request->isAjax())
		{
			if (is_numeric($post_ik))
			{
				$ik = intval($post_ik);

				$post = Post::findFirst([
					                        'ik = ?0 AND visible = ?1',
					                        'bind' => [$ik, 1]
				                        ]);

				if ($post)
				{
					$post->shares = $post->shares + 1;
					$post->indexed = 0;
					$post->update();
					$who = 'Someone';

					$auth = $this->session->get('auth');
					if ($auth && $auth !== false)
					{
						$who = $auth[ 'user_name' ];
					}

					ob_start();
					$this->view->partial('emails/share', ['message' => $this->request->getPost('message'), 'who' => $who, 'post_thumbnail' => $post->thumbnail('small'), 'post_url' => $post->url()]);
					$body = ob_get_contents();
					ob_end_clean();

					//Send
					$success = Helpers::sendEmail($this->request->getPost('email'), $who . ' Sent you a post', $body);

					// Notify any event listeners.
					$this->eventManager->fire('post:afterPostHasBeenShared', $this, [
						'user'  => $auth[ 'ik' ],
						'model' => $post
					]);
				}
			}
		}

		echo json_encode(['success' => $success, 'data' => []]);
	}

	public function removeAction($post_ik)
	{
		$this->view->disable();

		$success = false;

		$auth = $this->session->get('auth');

		if (is_numeric($post_ik))
		{
			$ik = intval($post_ik);

			//Lookup item
			$post = Post::findFirst([
				                        'ik = ?0 AND visible = ?1',
				                        'bind' => [$ik, 1]
			                        ]);
		}

		if ($post)
		{
			if ($auth && $auth !== false && ($auth[ 'role' ] == 'Admins' || $post->user_ik == $auth[ 'ik' ]) && $this->request->isAjax())
			{
				$post->visible = 0;
				$post->status = 'deleted';
				$post->update();
				$post->index();
				$success = true;
			}
		}

		echo json_encode(['success' => $success, 'data' => []]);
	}

	public function editAction($post_ik)
	{
		if (is_numeric($post_ik))
		{
			$ik = intval($post_ik);

			$post = Post::findFirst([
				                        'ik = ?0 AND visible = 1 AND user_ik = ?1 AND status = ?2',
				                        'bind' => [$ik, $this->auth[ 'ik' ], 'posted']
			                        ]);

			if ($post)
			{
				if (!$this->request->isPost())
				{
					$this->assets->addJs('js/libraries/tagmanager/tagmanager.js')
					             ->addJs('js/libraries/validate/jquery.validate.min.js')
					             ->addJs('js/post/details.js');

					$categories = [];
					foreach (Helpers::getCategoryList() AS $category)
					{
						$categories[ $category[ 'ik' ] ] = ['title' => $category[ 'title' ], 'children' => []];
					}

					$this->view->categories = $categories;
					$this->view->thumbnail = $post->thumbnail('small');
					$this->view->post = $post;
				}
				else
				{
					if ($ik == $this->request->getPost('_ref') && $post->id == $this->request->getPost('_val'))
					{
						$post->description = strip_tags($this->request->getPost('description'));
						$post->title = strip_tags($this->request->getPost('title'));
						$post->tags = strip_tags($this->request->getPost('hidden-tags'));
						$post->indexed = 0;

						$category = $this->request->getPost('category');
						if (is_numeric($category))
						{
							$post->category_ik = $category;
						}

						if ($post->update())
						{
							$this->flash->success('Your post has been updated.');
							return $this->response->redirect($post->url());
						}
					}
					else
					{
						$this->flash->error('The idea selected could not be edited.');
						return $this->response->redirect('post/idea');
					}
				}
			}
			else
			{
				$this->flash->error('The idea selected could not be edited.');
				return $this->response->redirect('post/idea');
			}
		}
	}
}