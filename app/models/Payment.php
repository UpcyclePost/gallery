<?php

class Payment extends \Phalcon\Mvc\Model
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
	public $post_ik;

	/**
	 *
	 * @var integer
	 */
	public $from_user_ik;

	/**
	 *
	 * @var integer
	 */
	public $to_user_ik;

	/**
	 *
	 * @var integer
	 */
	public $shop_ik;

	/**
	 *
	 * @var string
	 */
	public $stripe_id;

	/**
	 * @var decimal
	 */
	public $amount;

	/**
	 * @var decimal
	 */
	public $authorized_amount;

	/**
	 *
	 * @var datetime
	 */
	public $applied_at;

	public function initialize()
	{
		$this->belongsTo('post_ik', 'Market', 'post_ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'                => 'ik',
			'post_ik'           => 'post_ik',
			'shop_ik'           => 'shop_ik',
			'from_user_ik'      => 'from_user_ik',
			'to_user_ik'        => 'to_user_ik',
			'stripe_id'         => 'stripe_id',
			'amount'            => 'amount',
			'authorized_amount' => 'authorized_amount',
			'applied_at'        => 'applied_at'
		];
	}
}