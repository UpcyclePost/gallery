<?php

class FollowController extends ControllerBase
{
	public function shopAction($shopIk)
	{
		$result = ['hasChanged' => false, 'subscribed' => false];

		$this->view->disable();
		if ($this->auth && isset($this->auth['ik']))
		{
			// Check if this shop exists.
			$shopModel = Shop::findFirst($shopIk);
			if ($shopModel)
			{
				if ($shopModel->user_ik != $this->auth['ik'])
				{
					$result['hasChanged'] = true;

					$subscriptionService = new SubscriptionService();
					$subscribed = $subscriptionService->subscribe('shop', $this->auth['ik'], $shopModel->User->ik);

					$result['subscribed'] = $subscribed;

					if ($subscribed)
					{
						$result['text'] = 'Following ' . $shopModel->name;
						// Notify any listeners that this user has been followed.
						$this->eventManager->fire('shop:whenShopHasBeenFollowed', $this, ['subscriber' => $this->auth['ik'], 'subscribed_to' => $shopModel->User->ik]);
					}
					else
					{
						$result['text'] = 'Follow ' . $shopModel->name;
					}
				}
			}
		}

		echo json_encode($result);
	}

	public function userAction($userIk)
	{
		$result = ['hasChanged' => false, 'subscribed' => false];

		$this->view->disable();
		if ($this->auth && isset($this->auth['ik']))
		{
			if ($this->auth['ik'] != $userIk)
			{
				$userModel = User::findFirst($userIk);
				if ($userModel)
				{
					$result['hasChanged'] = true;

					$subscriptionService = new SubscriptionService();
					$subscribed = $subscriptionService->subscribe('user', $this->auth['ik'], $userModel->ik);

					$result['subscribed'] = $subscribed;

					if ($subscribed)
					{
						$result['text'] = 'Following ' . $userModel->name;
						// Notify any listeners that this user has been followed.
						$this->eventManager->fire('user:whenUserHasBeenFollowed', $this, ['subscriber' => $this->auth['ik'], 'subscribed_to' => $userModel->ik]);
					}
					else
					{
						$result['text'] = 'Follow ' . $userModel->name;
					}
				}
			}
		}

		echo json_encode($result);
	}
}