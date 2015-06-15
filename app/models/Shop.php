<?php namespace Up\Models;

class Shop extends \Phalcon\Mvc\Model
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
	public $user_ik;

	/**
	 *
	 * @var string
	 */
	public $name;

	/**
	 *
	 * @var string
	 */
	public $recipient_legal_name;

	/**
	 *
	 * @var string
	 */
	public $url;

	/**
	 *
	 * @var string
	 */
	public $recipient_token;

	/**
	 *
	 * @var string
	 */
	public $customer_token;

	/**
	 *
	 * @var string
	 */
	public $card_token;

	/**
	 *
	 * @var string
	 */
	public $bank_token;

	/**
	 *
	 * @var string
	 */
	public $address;

	/**
	 *
	 * @var string
	 */
	public $city;

	/**
	 *
	 * @var string
	 */
	public $st;

	/**
	 *
	 * @var string
	 */
	public $zip;

	/**
	 *
	 * @var string
	 */
	public $country;

	/**
	 *
	 * @var string
	 */
	public $ships_to;

	/**
	 *
	 * @var string
	 */
	public $preferred_language;

	/**
	 *
	 * @var string
	 */
	public $preferred_currency;

	/**
	 *
	 * @var string
	 */
	public $first_name;

	/**
	 *
	 * @var string
	 */
	public $last_name;

	/**
	 *
	 * @var string
	 */
	public $phone_number;

	/**
	 *
	 * @var string
	 */
	public $last4;

	/**
	 *
	 * @var decimal
	 */
	public $balance;

	/**
	 *
	 * @var decimal
	 */
	public $total_revenue;

	/**
	 *
	 * @var datetime
	 */
	public $created_at;

	/**
	 *
	 * @var datetime
	 */
	public $updated_at;

	/**
	 *
	 * @var datetime
	 */
	public $terms_agreed_at;

	/**
	 *
	 * @var string
	 */
	public $terms_agreed_to;

	/**
	 *
	 * @var datetime
	 */
	public $last_transferred_at;

	/**
	 *
	 * @var int
	 */
	public $is_active;

	/**
	 *
	 * @var datetime
	 */
	public $activated_at;

	public function initialize()
	{
		$this->hasMany('ik', 'Sales', 'sold_by_shop_ik');
		$this->hasOne('ik', 'Survey', 'shop_ik');
		$this->hasMany('ik', 'Market', 'shop_ik');
		$this->belongsTo('user_ik', 'User', 'ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'                   => 'ik',
			'user_ik'              => 'user_ik',
			'name'                 => 'name',
			'recipient_legal_name' => 'recipient_legal_name',
			'url'                  => 'url',
			'recipient_token'      => 'recipient_token',
			'customer_token'       => 'customer_token',
			'card_token'           => 'card_token',
			'bank_token'           => 'bank_token',
			'address'              => 'address',
			'city'                 => 'city',
			'st'                   => 'st',
			'zip'                  => 'zip',
			'country'              => 'country',
			'ships_to'             => 'ships_to',
			'preferred_currency'   => 'preferred_currency',
			'preferred_language'   => 'preferred_language',
			'first_name'           => 'first_name',
			'last_name'            => 'last_name',
			'phone_number'         => 'phone_number',
			'last4'                => 'last4',
			'balance'              => 'balance',
			'total_revenue'        => 'total_revenue',
			'created_at'           => 'created_at',
			'updated_at'           => 'updated_at',
			'terms_agreed_at'      => 'terms_agreed_at',
			'terms_agreed_to'      => 'terms_agreed_to',
			'last_transferred_at'  => 'last_transferred_at',
			'is_active'            => 'is_active',
			'activated_at'         => 'activated_at'
		];
	}

	public function canListItem()
	{
		if ($this->is_active != 1)
		{
			// This shop is not active, they cannot list an item for sale.
			return \false;
		}
		else if (!$this->bank_token && !$this->card_token)
		{
			// This shop has not configured their payment information, they cannot list an item for sale.
			return \false;
		}
		else if ($this->balance < 0)
		{
			// This shop has a negative balance, they cannot list an item for sale.
			return \false;
		}

		return \true;
	}
}