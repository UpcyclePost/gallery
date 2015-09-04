<?php

class CreditCard extends \Phalcon\Mvc\Model
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
	public $user_ik;

	/**
	 *
	 * @var integer
	 */
	public $success;

	/**
	 *
	 * @var string
	 */
	public $stripe_id;

	/**
	 *
	 * @var string
	 */
	public $card_token;

	/**
	 * @var datetime
	 */
	public $processed_at;

	/**
	 * @var string
	 */
	public $cvc_result;

	/**
	 *
	 * @var string
	 */
	public $zip_result;

	/**
	 *
	 * @var string
	 */
	public $address_result;

	/**
	 *
	 * @var double
	 */
	public $amount;

	/**
	 *
	 * @var string
	 */
	public $response;

	public function initialize()
	{
		$this->hasOne('post_ik', 'Payment', 'post_ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'             => 'ik',
			'post_ik'        => 'post_ik',
			'user_ik'        => 'user_ik',
			'success'        => 'success',
			'stripe_id'      => 'stripe_id',
			'card_token'     => 'card_token',
			'processed_at'   => 'processed_at',
			'cvc_result'     => 'cvc_result',
			'zip_result'     => 'zip_result',
			'address_result' => 'address_result',
			'amount'         => 'amount',
			'response'       => 'response'
		];
	}
}