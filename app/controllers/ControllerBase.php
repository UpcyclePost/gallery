<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	protected $auth = false;

	protected function initialize()
	{
		$unread = 0;
		$this->view->_version = $this->config->application->assetVersion;
		$this->view->_mixpanel_key = $this->config->application->mixpanelKey;
		$this->view->_mixpanel_page = 'Home';
		$this->view->title = 'Upmod';
		$auth = $this->session->get('auth');
		$this->view->isLoggedIn = ($auth && $auth !== false);
		$this->view->metaDescription = "Your place to discover the world's greatest upcycled products and to post what inspires you.";
		$this->view->auth = [];

		$prestashopIntegrationService = new \Up\Services\PrestashopIntegrationService();

		if ($auth !== false)
		{
			$this->view->actualRole = (isset($auth[ 'originalRole' ])) ? $auth[ 'originalRole' ] : $auth[ 'role' ];
			$this->view->impersonating = (isset($auth[ 'impersonating' ])) ? $auth[ 'impersonating' ] : \false;

			$this->auth = $auth;
			$this->view->auth = $auth;
			$unread = Message::count(['conditions' => 'to_user_ik=?0 AND read is null', 'bind' => [$auth[ 'ik' ]]]);

			if (($profile = $this->__getProfile()) !== \false)
			{
				$prestashopIntegrationService->loginToPrestashop($profile);

				$this->view->avatar = $profile->avatarUrl();

				if (isset($auth[ 'shopId' ]))
				{
					$this->view->myShopId = $auth[ 'shopId' ];
					$this->view->totalUnshippedItems = $prestashopIntegrationService->getTotalOrdersWaitingToShip($profile) ?: 0;
				}
				else
				{
					if (($shopId = $prestashopIntegrationService->getShopId($profile)))
					{
						$this->view->myShopId = $shopId;
						$this->view->totalUnshippedItems = $prestashopIntegrationService->getTotalOrdersWaitingToShip($profile) ?: 0;
					}
				}
			}
		}

		$this->view->totalCartItems = $prestashopIntegrationService->getTotalItemsInCart();
		$this->view->ps_Available = $prestashopIntegrationService->isPrestashopAvailable();
		$this->view->unread = $unread;

		$sidebarBlock = $prestashopIntegrationService->getCMSBlock(21);
		if ($sidebarBlock)
		{
			$this->view->sidebarCMSBlock = $sidebarBlock;
		}

		$this->flash->output();
	}

	protected function __getCMSBlock($id)
	{
		$prestashopIntegrationService = new \Up\Services\PrestashopIntegrationService();
		$result = $prestashopIntegrationService->getCMSBlock($id);

		$search = [                 // www.fileformat.info/info/unicode/<NUM>/ <NUM> = 2018
		                            "\xC2\xAB",     // « (U+00AB) in UTF-8
		                            "\xC2\xBB",     // » (U+00BB) in UTF-8
		                            "\xE2\x80\x98", // ‘ (U+2018) in UTF-8
		                            "\xE2\x80\x99", // ’ (U+2019) in UTF-8
		                            "\xE2\x80\x9A", // ‚ (U+201A) in UTF-8
		                            "\xE2\x80\x9B", // ? (U+201B) in UTF-8
		                            "\xE2\x80\x9C", // “ (U+201C) in UTF-8
		                            "\xE2\x80\x9D", // ” (U+201D) in UTF-8
		                            "\xE2\x80\x9E", // „ (U+201E) in UTF-8
		                            "\xE2\x80\x9F", // ? (U+201F) in UTF-8
		                            "\xE2\x80\xB9", // ‹ (U+2039) in UTF-8
		                            "\xE2\x80\xBA", // › (U+203A) in UTF-8
		                            "\xE2\x80\x93", // – (U+2013) in UTF-8
		                            "\xE2\x80\x94", // — (U+2014) in UTF-8
		                            "\xE2\x80\xA6",  // … (U+2026) in UTF-8
		];

		$replacements = [
			"<<",
			">>",
			"'",
			"'",
			"'",
			"'",
			'"',
			'"',
			'"',
			'"',
			"<",
			">",
			"-",
			"-",
			"...",
		];

		$result[ 'content' ] = str_replace($search, $replacements, utf8_encode($result[ 'content' ]));

		return $result;
	}

	/**
	 * @return User|bool|null
	 */
	protected function __getProfile()
	{
		if (isset($this->auth[ 'ik' ]))
		{
			return User::findFirst($this->auth[ 'ik' ]);
		}

		return \false;
	}

	protected function __getShopProfile()
	{
		$user = $this->__getProfile();

		if (!$user || $user->type != 'seller' || $user->feature_enabled != 1)
		{
			return \false;
		}

		return $user;
	}

	protected function __load()
	{

	}
}
