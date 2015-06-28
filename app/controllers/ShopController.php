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
				             ->addJs('js/libraries/dropzone/dropzone.js');
			}
			else
			{
				$this->view->disable();

				if ($this->request->hasPost('logo'))
				{
					$profile->Shop->logo = $this->request->getPost('logo');
				}

				if ($this->request->hasPost('background'))
				{
					$profile->Shop->background = $this->request->getPost('background');
				}

				$profile->save();
				$profile->Shop->save();

				$this->flash->success('Your shop has been saved.');

				return $this->response->redirect('shops/my/customize');
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
								// Create a thumbnail of the profile background
								$imageProcessingService = new ImageProcessingService($permanentFile);

								$imageProcessingService->createThumbnail($permanentFile, 400, 100, \true);

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
				$user->Shop->views = ($user->Shop->views) ? $user->Shop->views++ : 1;
				$user->save();
				$user->Shop->save();
			}

			$prestashopService = new PrestashopIntegrationService();

			if (($shop_info = $prestashopService->getShopByEmail($user->email)) !== \false)
			{
				$this->view->results = $prestashopService->findRecentProducts(\false, $user);
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

		$this->assets->addJs('js/gallery/layout.js');
	}

	public function shopsAction()
	{
		$this->view->title = 'Shop Gallery | Upcycling Ideas, Articles and Products | UpcyclePost';
		$prestashopService = new PrestashopIntegrationService();

		$this->assets->addJs('js/gallery/layout.js');
		$this->view->results = $prestashopService->findShops();
	}
}