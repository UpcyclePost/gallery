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

	public function viewAction($userName)
	{
		$user = \User::findFirst(['user_name = ?0', 'bind' => [$userName]]);

		if (!$user)
		{
			return $this->response->redirect('error/404');
		}
		else
		{
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