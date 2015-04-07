<?php

class ListingController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();

		$this->view->title = 'List Item | UpcyclePost Market';
	}

	public function uploadAction()
	{
		$this->assets->addJs('js/libraries/dropzone/dropzone.js')
		             ->addJs('js/post/market.js');

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfListingDisabled($profile);

			if ($this->session->has('editing'))
			{
				$this->session->remove('editing');
			}
		}
		else
		{
			// This user has no profile, or is not a seller
			return $this->response->redirect('post/idea');
		}
	}

	public function thumbnailAction()
	{
		$this->view->disable();

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfListingDisabled($profile);

			if (!$this->request->isPost() || !$this->request->isAjax())
			{
				return $this->response->redirect('shop/list');
			}
			else
			{
				if ($this->request->hasFiles())
				{
					foreach ($this->request->getUploadedFiles() AS $file)
					{
						// Save
						$postModel = new Post();
						$postModel->created = date('Y-m-d H:i:s');
						$postModel->visible = 0;
						$postModel->indexed = 0;
						$postModel->type = 'market';
						$postModel->status = 'created';
						$postModel->views = 0;
						$postModel->likes = 0;
						$postModel->comments = 0;
						$postModel->shares = 0;
						$postModel->reports = 0;
						$postModel->user_ik = $this->auth['ik'];

						if ($postModel->save() != false)
						{
							$postModel->id = Helpers::createShortCode($postModel->ik);
							$postModel->save();

							$postService = new PostService();
							$result = $postService->createThumbnails($file, $postModel->ik, $postModel->id);

							if ($result !== \true)
							{
								$postModel->delete();
								echo json_encode(['success' => false, 'data' => ['messages' => 'Thumbnail could not be created.']]);
							}
							else
							{
								$this->session->set('editing', ['ik' => $postModel->ik, 'id' => $postModel->id, 'market' => \true]);

								// Return result
								echo json_encode(['success' => $result, 'data' => ['preview' => $postModel->thumbnail('big')]]);
							}
						}
						else
						{
							echo json_encode(['success' => false, 'data' => ['messages' => $postModel->getMessages()]]);
						}
						break;
					}
				}
				else
				{
					echo json_encode(['success' => false, 'data' => ['messages' => 'No files were uploaded.']]);
				}
			}
		}
		else
		{
			return $this->response->redirect('post/idea');
		}
	}

	public function detailsAction()
	{
		$this->assets->addJs('js/libraries/tagmanager/tagmanager.js')
		             ->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/post/marketDetails.js');

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			if ($this->request->isPost())
			{
				$this->response->redirect('shop/list/details');
			}

			$this->__redirectIfListingDisabled($profile);

			if (($post = $this->session->get('editing')) != null && isset($post['market']))
			{
				$postModel = Post::findFirst($post['ik']);

				if (!$postModel)
				{
					// Not creating a post, probably should not be here
					return $this->response->redirect('shop/list');
				}
				else
				{
					$postModel->status = 'uploaded';
					$postModel->update();

					$postService = new PostService();
					$this->view->categories = $postService->buildCategoryArray();
					$this->view->postTitle = $postModel->title;
					$this->view->tags = $postModel->tags;
					$this->view->description = $postModel->description;
					$this->view->price = $postModel->Market->price;
					$this->view->shipping = $postModel->Market->shipping_price;
					$this->view->actual_category = $postModel->category_ik;
					$this->view->post = $postModel->ik;
					$this->view->thumbnail = $postModel->thumbnail('small');
				}
			}
			else
			{
				$this->flash->error('Uh oh, your item couldn\'t be saved, please try your upload again.');
				$this->response->redirect('shop/list');
			}
		}
		else
		{
			return $this->response->redirect('post/idea');
		}
	}

	public function submitAction()
	{
		$this->view->disable();

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfListingDisabled($profile);

			if (($post = $this->session->get('editing')) != null && isset($post['market']))
			{
				$postModel = Post::findFirst($post['ik']);

				if (!$this->__saveAndContinue($postModel, \false))
				{
					$this->flash->error('Your listing could not be edited.');
					return $this->response->redirect('shop/dashboard');
				}
			}
			else
			{
				var_dump($post);
			}
		}
		else
		{
			return $this->response->redirect('post/idea');
		}
	}

	public function publishAction($postIk = \false)
	{
		$this->view->disable();

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfListingDisabled($profile);

			if (($post = $this->session->get('editing')) != null)
			{
				$postModel = Post::findFirst($post['ik']);
				$isEditing = ($postIk && $postIk == $post['ik'] && $postModel->user_ik == $this->auth['ik'] && $postModel->type == 'market');

				if ($postModel !== false)
				{
					if ($isEditing || $postModel->status == 'submitted')
					{
						$postModel->user_ik = $this->session->get('auth')['ik'];
						$postModel->visible = 1;
						$postModel->indexed = 1;
						$postModel->status = 'posted';

						// Update Market
						$marketModel = Market::findFirst([
							                                 'conditions' => 'post_ik = ?0',
							                                 'bind'       => [$postModel->ik]
						                                 ]);

						$marketModel->status = 'available';
						$marketModel->listed_at = date('Y-m-d H:i:s');
						$marketModel->updated_at = date('Y-m-d H:i:s');
						$marketModel->deleted = 0;

						$marketModel->save();

						$postModel->index();

						if ($postModel->save())
						{
							$this->session->remove('editing');

							if ($isEditing)
							{
								$this->flash->success('Your listing has been updated.');
								return $this->response->redirect('shop/dashboard');
							}
							else
							{
								// Notify any event listeners before the redirect
								$this->eventManager->fire('post:afterListingHasBeenCreated', $this, ['user' => $this->auth['ik'], 'model' => $postModel]);

								return $this->response->redirect($postModel->url());
							}
						}
					}
				}
			}

			$this->flash->error('Uh oh, your listing couldn\'t be saved, please try again');
			return $this->response->redirect('shop/list/details');
		}
		else
		{
			return $this->response->redirect('post/idea');
		}
	}

	public function editAction($postIk = \false)
	{
		$this->assets->addJs('js/libraries/tagmanager/tagmanager.js')
		             ->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/post/marketDetails.js');

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfListingDisabled($profile);

			if ($postIk)
			{
				$postModel = Post::findFirst($postIk);

				if (!$postModel->Market)
				{
					$this->flash->error('This listing cannot be edited.');
					return $this->response->redirect('shop/dashboard');
				}
				else if (($result = $postModel->Market->isEditable($this->auth['ik'])) !== \true)
				{
					$this->flash->error($result);
					return $this->response->redirect('shop/dashboard');
				}
				else
				{
					$this->session->set('editing', ['ik' => $postModel->ik, 'id' => $postModel->id, 'market' => \true]);
				}

				if ($this->request->isPost())
				{
					if (!$this->__saveAndContinue($postModel, $postIk))
					{
						$this->flash->error('Your listing could not be edited.');
						return $this->response->redirect('shop/dashboard');
					}
				}

				// Get category list
				$postService = new PostService();
				$this->view->categories = $postService->buildCategoryArray();
				$this->view->postTitle = $postModel->title;
				$this->view->tags = $postModel->tags;
				$this->view->description = $postModel->description;
				$this->view->price = $postModel->Market->price;
				$this->view->shipping = $postModel->Market->shipping_price;
				$this->view->actual_category = $postModel->category_ik;
				$this->view->post = $postModel->ik;
				$this->view->thumbnail = $postModel->thumbnail('small');
			}
			else
			{
				// Not editing a post, probably should not be here
				return $this->response->redirect('shop/list');
			}
		}
		else
		{
			return $this->response->redirect('post/idea');
		}
	}

	public function deleteAction($postIk)
	{
		$this->view->disable();

		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfListingDisabled($profile);

			$postModel = Post::findFirst($postIk);

			if ($postModel->visible == 1 && $postModel->status != 'deleted')
			{
				if ($postModel->Market)
				{
					if (($result = $postModel->Market->isEditable($this->auth['ik'])) !== \true)
					{
						$this->flash->error($result);
					}
					else
					{
						$postModel->visible = 0;
						$postModel->status = 'deleted';
						$postModel->save();
						if ($postModel->save() === true)
						{
							$marketModel = Market::findFirst([
								                                 'conditions'        => 'post_ik = ?0',
								                                 'bind'              => [$postIk]
							                                 ]);

							if ($marketModel)
							{
								$marketModel->status = 'deleted';
								$marketModel->deleted = 1;
								$marketModel->deleted_at = date('Y-m-d H:i:s');

								if ($marketModel->save() === true)
								{
									$this->flash->success(sprintf('%s has been deleted.', $postModel->title));
								}
							}
						}
					}
				}
			}
		}

		// Regardless of success, redirect to dashboard.
		return $this->response->redirect('shop/dashboard');
	}

	/**
	 * Redirects the user to the appropriate location if listing is not enabled
	 * for their account.
	 *
	 * @param User $profile
	 *
	 * @return mixed
	 */
	protected function __redirectIfListingDisabled(&$profile)
	{
		if ($profile instanceof User)
		{
			if (!$profile->Shop || !$profile->Shop->ik)
			{
				// This profile has no shop.
				$this->flash->error('You must open a shop to list items.');
				return $this->response->redirect('shop/open');
			}
			else if (!$profile->Shop->canListItem())
			{
				// This shop cannot list items.
				$this->flash->error('Listing items in the Marketplace is disabled for this shop.');
				return $this->response->redirect('shop/dashboard');
			}
		}
		else
		{
			return $this->response->redirect('post/idea');
		}
	}

	protected function __saveAndContinue(&$postModel, $postIk = \false)
	{
		if ($postModel && $this->request->isPost())
		{
			$isEditing = ($postIk && $postModel->ik == $postIk && $postModel->user_ik == $this->auth['ik'] && $postModel->type == 'market');

			if (!$isEditing && $postModel->status != 'uploaded')
			{
				// Redirect to shop dashboard if they are neither posting nor editing.
				return $this->response->redirect('shop/dashboard');
			}

			$price = round(preg_replace('/[^0-9\.]/', '', $this->request->getPost('price')), 2);
			$shipping = round(preg_replace('/[^0-9\.]/', '', $this->request->getPost('shipping')), 2);

			// Verify the price meets the requirements.
			if ($price < $this->config->application->prices->get('minimumListingPrice'))
			{
				$this->flash->error(sprintf('Your listing price of %s does not meet the minimum listing price of %s.', $price, number_format($this->config->application->prices->get('minimumListingPrice'), 2)));

				if ($isEditing)
				{
					return $this->response->redirect('shop/listing/edit/' . $postIk);
				}
				else
				{
					return $this->response->redirect('shop/list/details');
				}
			}

			// Save the post details
			$postModel->description = strip_tags($this->request->getPost('description'));
			$postModel->title = strip_tags($this->request->getPost('title'));
			$postModel->tags = strip_tags($this->request->getPost('hidden-tags'));
			if (($category = $this->request->getPost('category')) && is_numeric($category))
			{
				$postModel->category_ik = $category;
			}

			// If we are not editing a post, save the status.
			if (!$isEditing)
			{
				$postModel->status = 'submitted';
			}

			$postModel->save();

			// Attach the Market model
			$marketModel = Market::findFirst([
				                                 'conditions' => 'post_ik = ?0',
				                                 'bind'       => [$postModel->ik]
			                                 ]);
			if (!$marketModel)
			{
				if (!$isEditing)
				{
					$marketModel = new Market();
				}
				else
				{
					$this->flash->error('Your listing could not be edited.');
					return $this->response->redirect('shop/dashboard');
				}
			}

			// Save the market details
			$marketModel->post_ik = $postModel->ik;
			$marketModel->shop_ik = $postModel->User->Shop->ik;
			$marketModel->price = $price;
			$marketModel->shipping_price = $shipping;
			$marketModel->ships_to = $postModel->User->Shop->ships_to;

			// If we are not editing a post, save the status.
			if (!$isEditing)
			{
				$marketModel->status = 'created';
			}

			$marketModel->save();

			if ($isEditing)
			{
				// Successful edit
				return $this->response->redirect('shop/listing/edit/publish/' . $postIk);
			}
			else
			{
				// Successful post
				return $this->response->redirect('shop/list/publish');
			}

			return \true;
		}

		return \false;
	}
}