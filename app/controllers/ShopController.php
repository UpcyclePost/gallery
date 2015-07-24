<?php

use Up\Services\PrestashopIntegrationService;

class ShopController extends ControllerBase
{
	public function redirectAction($userIk)
	{
		$user = \User::findFirst((int)$userIk);

		if ($user)
		{
			return $this->response->redirect($user->shopUrl());
		}
		else
		{
			return $this->response->redirect('error/404');
		}
	}

	public function customizeAction()
	{
		if (($profile = $this->__getProfile()) !== \false)
		{
			$this->view->flow = 'edit';
			if ($this->request->has('flow'))
			{
				$this->view->flow = $this->request->get('flow');
			}
			else if ($this->request->hasPost('flow'))
			{
				$this->view->flow = $this->request->getPost('flow');
			}

			// If the variables were changed either through direct action or accident
			// reset the flow to member.
			if ($this->view->flow != 'edit' && $this->view->flow != 'create')
			{
				$this->view->flow = 'edit';
			}

			if (!$profile->Shop)
			{
				$shop = new \Up\Models\Shop();
				$shop->user_ik = $profile->ik;
				$shop->save();

				// Reload the profile
				$profile = $this->__getProfile();
			}

			$this->view->profile = $profile;

			if (!$this->request->isPost())
			{
				$this->assets->addJs('js/shop/customize.js')
				             ->addJs('js/libraries/dropzone/dropzone.js')
							 ->addJs('js/libraries/cropper/cropper.min.js')
							 ->addCss('js/libraries/cropper/cropper.min.css');
			}
			else
			{
				$this->view->disable();

				$shop = $profile->Shop;

				if ($this->request->hasPost('cropped-logo'))
				{
					$fileName = sprintf('%s-%s-%s.png', $profile->ik, Helpers::createShortCode($profile->ik), time());
					$permanentFile = $this->config->application->shopLogoDir . $fileName;
					file_put_contents($permanentFile, base64_decode(str_replace('data:image/png;base64,', '', $this->request->getPost('cropped-logo'))));
					// Resize the cropped logo
					$imageProcessingService = new ImageProcessingService($permanentFile);

					$imageProcessingService->createThumbnail($permanentFile, 400, 100, \true);

					$shop->logo = $fileName;
				}

				if ($this->request->hasPost('background'))
				{
					$shop->background = $this->request->getPost('background');
				}

				$shop->description = $this->request->getPost('description');

				$profile->save();
				$shop->save();

				$this->flash->success('Your shop has been saved.');

				if ($this->request->hasPost('flow'))
				{
					if ($this->request->getPost('flow') == 'create')
					{
						return $this->response->redirect('shop/module/marketplace/sellerrequest');
					}
				}

				return $this->response->redirect($profile->shopUrl());
			}
		}
	}

	public function uploadLogoAction()
	{
		if (!$this->request->isPost() || !$this->request->isAjax())
		{
			// This function only accepts POSTed requests
			return $this->response->redirect('shop/my/customize');
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
				if (($profile = $this->__getProfile()) !== \false)
				{
					foreach ($this->request->getUploadedFiles() AS $file)
					{
						if ($file->getType() == 'image/gif' || $file->getType() == 'image/png' || $file->getType() == 'image/jpeg')
						{
							$fileName = sprintf('%s-%s-%s%s', $profile->ik, Helpers::createShortCode($profile->ik), time(), strrchr($file->getName(), '.'));

							//profileImageDir
							$permanentFile = $this->config->application->shopLogoDir . $fileName;

							if ($file->moveTo($permanentFile))
							{
								list($width, $height, $type, $attr) = @getimagesize($permanentFile);

								// Create a thumbnail of the profile background
								$imageProcessingService = new ImageProcessingService($permanentFile);

								$imageProcessingService->createThumbnail($permanentFile, ($width > 800) ? 800 : $width);

								echo json_encode(['success' => true, 'data' => ['file' => $fileName, 'preview' => $this->imageUrl->get(sprintf('shop/logo/%s', $fileName))]]);

								return;
							}
						}

						break;
					}
				}
			}
		}

		echo json_encode(['success' => false]);
	}

	public function uploadBackgroundAction()
	{
		if (!$this->request->isPost() || !$this->request->isAjax())
		{
			// This function only accepts POSTed requests
			return $this->response->redirect('shop/my/customize');
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
				if (($profile = $this->__getProfile()) !== \false)
				{
					foreach ($this->request->getUploadedFiles() AS $file)
					{
						if ($file->getType() == 'image/gif' || $file->getType() == 'image/png' || $file->getType() == 'image/jpeg')
						{
							$fileName = sprintf('%s-%s-%s%s', $profile->ik, Helpers::createShortCode($profile->ik), time(), strrchr($file->getName(), '.'));
							$thumbnailFileName = sprintf('thumb-%s', $fileName);

							//profileImageDir
							$permanentFile = $this->config->application->shopBackgroundDir . $fileName;
							$thumbnailFile = sprintf('%s%s', $this->config->application->shopBackgroundDir, $thumbnailFileName);

							if ($file->moveTo($permanentFile))
							{
								// Create a thumbnail of the profile background
								$imageProcessingService = new ImageProcessingService($permanentFile);
								list($width, $height, $type, $attr) = @getimagesize($permanentFile);

								$imageProcessingService->createThumbnail($thumbnailFile, ($width >= 244) ? 244 : $width);

								echo json_encode(['success' => true, 'data' => ['file' => $fileName, 'preview' => $this->imageUrl->get(sprintf('shop/background/%s', $thumbnailFileName))]]);

								return;
							}

							// Let's plan to have more than one background image in the future - perhaps rotating?
							break;
						}
					}
				}
			}
		}

		echo json_encode(['success' => false]);
	}

	public function viewAction($userName)
	{
		$user = \User::findFirst(['slugify(user_name) = ?0', 'bind' => [$userName]]);

		if (!$user)
		{
			return $this->response->redirect('error/404');
		}
		else
		{
			if (!$user->Shop)
			{
				$shop = new \Up\Models\Shop();
				$shop->user_ik = $user->ik;
				$shop->views = 1;
				$shop->save();
			}
			else
			{
				$user->Shop->views = ($user->Shop->views) ? $user->Shop->views + 1 : 1;
				$user->save();
				$user->Shop->save();
			}

			$prestashopService = new PrestashopIntegrationService();

			if (($shop_info = $prestashopService->getShopByEmail($user->email)) !== \false)
			{
				$isOwnShop = ($this->view->isLoggedIn && $this->auth[ 'ik' ] == $user->ik);
				$this->view->displayShopNotVisibleMessage = ($isOwnShop && $user->Shop->totalProducts() < 3);

				$this->view->results = $user->Shop->activeProducts();
				$this->view->shopName = $shop_info['shop_name'];
				$this->view->title = sprintf('%s | Upcycling Ideas, Articles and Products | UpcyclePost', $user->user_name);
				$this->view->profile = $user;
				$this->view->shopAbout = $shop_info['shop_about'];

				if ($user->custom_background)
				{
					$this->view->custom_background = $user->custom_background;
				}
			}
			else
			{
				return $this->response->redirect('error/404');
			}
		}

		$this->assets->addJs('js/gallery/layout.js?v=0.26.3');
	}

	public function shopsAction()
	{
		$this->view->title = 'Shop Gallery | Upcycling Ideas, Articles and Products | UpcyclePost';
		$prestashopService = new PrestashopIntegrationService();

		$this->assets->addJs('js/gallery/layout.js?v=0.26.3');
		$this->view->results = $prestashopService->findShops();
	}
}
