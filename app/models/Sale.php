<?php

class Sale extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $ik;

	/**
	 *
	 * @var int
	 */
	public $post_ik;

	/**
	 *
	 * @var integer
	 */
	public $sold_by_shop_ik;

	/**
	 *
	 * @var integer
	 */
	public $sold_to_user_ik;

	/**
	 *
	 * @var decimal
	 */
	public $amount;

	/**
	 *
	 * @var decimal
	 */
	public $ship_amount;

	/**
	 *
	 * @var decimal
	 */
	public $total_amount;

	/**
	 *
	 * @var decimal
	 */
	public $transaction_fee;

	/**
	 *
	 * @var decimal
	 */
	public $listing_fee;

	/**
	 *
	 * @var string
	 */
	public $ship_name;

	/**
	 *
	 * @var string
	 */
	public $ship_address;

	/**
	 *
	 * @var string
	 */
	public $ship_city;

	/**
	 *
	 * @var string
	 */
	public $ship_st;

	/**
	 *
	 * @var string
	 */
	public $ship_zip;

	/**
	 *
	 * @var datetime
	 */
	public $sold_at;

	/**
	 *
	 * @var int
	 */
	public $shipped;

	/**
	 *
	 * @var datetime
	 */
	public $shipped_at;

	public function initialize()
	{
		$this->belongsTo('post_ik', 'Post', 'ik');
		$this->belongsTo('sold_by_shop_ik', 'Shop', 'ik');
		$this->belongsTo('sold_to_user_ik', 'User', 'ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'              => 'ik',
			'post_ik'         => 'post_ik',
			'sold_by_shop_ik' => 'sold_by_shop_ik',
			'sold_to_user_ik' => 'sold_to_user_ik',
			'amount'          => 'amount',
			'ship_amount'     => 'ship_amount',
			'total_amount'    => 'total_amount',
			'transaction_fee' => 'transaction_fee',
			'listing_fee'     => 'listing_fee',
			'ship_name'       => 'ship_name',
			'ship_address'    => 'ship_address',
			'ship_city'       => 'ship_city',
			'ship_st'         => 'ship_st',
			'ship_zip'        => 'ship_zip',
			'sold_at'         => 'sold_at',
			'shipped'         => 'shipped',
			'shipped_at'      => 'shipped_at'
		];
	}

	public function calculateFee()
	{
		return round(($this->total_amount * .05) + 0.35, 2);
	}

	public function getBalanceOwed()
	{
		return $this->total_amount - $this->transaction_fee - $this->listing_fee;
	}
}