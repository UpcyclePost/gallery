<?php

class Subscription extends \Phalcon\Mvc\Model
{
	/**
	 *
	 * @var integer
	 */
	public $ik;

	/**
	 *
	 * @var integer
	 */
	public $subscriber_user_ik;

	/**
	 *
	 * @var integer
	 */
	public $subscribed_user_ik;

	/**
	 *
	 * @var string
	 */
	public $subscription_type;

	/**
	 *
	 * @var integer
	 */
	public $subscribed;

	/**
	 *
	 * @var datetime
	 */
	public $subscribed_at;

	public function initialize()
	{
		$this->useDynamicUpdate(true);

		$this->hasOne('subscribed_user_ik', 'User', 'ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'                 => 'ik',
			'subscriber_user_ik' => 'subscriber_user_ik',
			'subscribed_user_ik' => 'subscribed_user_ik',
			'subscription_type'  => 'subscription_type',
			'subscribed'         => 'subscribed',
			'subscribed_at'      => 'subscribed_at'
		];
	}
}