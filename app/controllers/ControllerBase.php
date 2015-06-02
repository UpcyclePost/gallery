<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    protected $auth = false;

    protected function initialize()
    {
        $unread = 0;
        $this->view->title = 'UpcyclePost';
        $auth = $this->session->get('auth');
        $this->view->isLoggedIn = ($auth && $auth !== false);
        $this->view->metaDescription = "Your place to discover the world's greatest upcycled products and to post what inspires you.";
        $this->view->auth = [];

        $prestashopIntegrationService = new \Up\Services\PrestashopIntegrationService();

        if ($auth !== false)
        {
            $this->auth = $auth;
            $this->view->auth = $auth;
            $unread = Message::count(['conditions' => 'to_user_ik=?0 AND read is null', 'bind' => [$auth[ 'ik' ]]]);

            if (($profile = $this->__getProfile()) !== \false)
            {
                $prestashopIntegrationService->loginToPrestashop($profile);

	            $this->view->avatar = $profile->avatarUrl();

                if (isset($auth['shopId']))
                {
                    $this->view->myShopId = $auth['shopId'];
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
        $this->view->unread = $unread;

        $this->flash->output();
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