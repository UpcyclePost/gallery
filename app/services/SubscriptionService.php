<?php

class SubscriptionService
{
	protected $config;
	protected $modelsManager;
	protected $eventManager;

	public function __construct()
	{
		$this->config = Phalcon\DI::getDefault()->get('config');
		$this->modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$this->eventManager = Phalcon\DI::getDefault()->get('eventManager');
	}

	/**
	 * @param $subscriptionType
	 * @param $subscriberIk
	 * @param $itemIk
	 *
	 * @return bool
	 */
	public function userIsSubscribed($subscriptionType, $subscriberIk, $itemIk)
	{
		$model = Subscription::findFirst([
			                                 'conditions' => 'subscription_type = ?0 AND subscriber_user_ik = ?1 AND subscribed_user_ik = ?2 AND subscribed = ?3',
			                                 'bind'       => [$subscriptionType, $subscriberIk, $itemIk, 1]
		                                 ]);

		return ($model && $model->subscribed == 1);
	}

	/**
	 * Subscribe to feed.  Returns true on subscribe, false on unsubscribe.
	 *
	 * @param $subscriptionType
	 * @param $subscriberIk
	 * @param $subscribeToIk
	 *
	 * @return bool
	 */
	public function subscribe($subscriptionType, $subscriberIk, $subscribeToIk)
	{
		// We can't subscribe to ourselves
		if ($subscriberIk == $subscribeToIk)
		{
			return \false;
		}

		// Check if this user is already following this user.
		$subscriptionModel = Subscription::findFirst([
			                                                 'conditions' => 'subscription_type = ?0 AND subscriber_user_ik = ?1 AND subscribed_user_ik = ?2',
			                                                 'bind'       => [$subscriptionType, $subscriberIk, $subscribeToIk]
		                                                 ]);

		if (!$subscriptionModel)
		{
			// Subscribe to this user's updates
			$subscription = new Subscription();
			$subscription->subscriber_user_ik = $subscriberIk;
			$subscription->subscribed_user_ik = $subscribeToIk;
			$subscription->subscription_type = $subscriptionType;
			$subscription->subscribed = 1;
			$subscription->subscribed_at = date('Y-m-d H:i:s');
			$subscription->save();

			return \true;
		}
		else
		{
			$subscribed = false;
			// Unsubscribe or resubscribe (Do we need to tell the users when this happens?)
			if ($subscriptionModel->subscribed == 1)
			{
				$subscriptionModel->subscribed = 0;
			}
			else
			{
				$subscriptionModel->subscribed = 1;
				$subscriptionModel->subscribed_at = date('Y-m-d H:i:s');

				$subscribed = true;
			}

			$subscriptionModel->save();

			return $subscribed;
		}

		return \false;
	}

	public function getSubscriptions($subscriberIk)
	{
		return Subscription::find([
			                          'conditions' => 'subscriber_user_ik = ?0 AND subscribed = ?1',
			                          'bind'       => [$subscriberIk, 1]
		                          ]);
	}

	public function getSubscribedEvents($recipientType, $subscriberIk, $type = \false)
	{
		$subscriptionCollection = Subscription::find([
			                                             'conditions' => 'subscriber_user_ik = ?0 AND subscribed = ?1',
			                                             'bind'       => [$subscriberIk, 1]
		                                             ]);

		$subscriptions = [];
		foreach ($subscriptionCollection AS $subscription)
		{
			$subscriptions[] = $subscription->subscribed_user_ik;
		}

		if (count($subscriptions) > 0)
		{
			$conditions = sprintf('recipient = ?0 AND (recipient_ik = ?1 OR (sender_ik IN(%s) AND recipient_ik IS NULL AND item_ik IS NOT NULL))', implode(',', $subscriptions));
			if ($type !== \false)
			{
				$conditions = sprintf("recipient = ?0 AND event_name = '%s' AND (recipient_ik = ?1 OR (sender_ik IN(%s) AND recipient_ik IS NULL AND item_ik IS NOT NULL))", $type, implode(',', $subscriptions));
			}

			return Feed::find([
				                  'conditions' => $conditions,
				                  'bind'       => [$recipientType, $subscriberIk],
				                  'order'      => 'created DESC',
				                  'limit'      => 100
			                  ]);
		}
		else
		{
			$conditions = 'recipient = ?0 AND recipient_ik = ?1';
			if ($type !== \false)
			{
				$conditions = sprintf("recipient = ?0 AND recipient_ik = ?1 AND event_name = '%s'", $type);
			}

			return Feed::find([
				                  'conditions' => $conditions,
				                  'bind'       => [$recipientType, $subscriberIk],
				                  'order'      => 'created DESC',
				                  'limit'      => 100
			                  ]);
		}

		return $feedCollection;
	}
}