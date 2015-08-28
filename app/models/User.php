<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class User extends Model
{

	/**
	 *
	 * @var integer
	 */
	public $ik;

	/**
	 *
	 * @var string
	 */
	public $email;

	/**
	 *
	 * @var string
	 */
	public $user_name;

	/**
	 *
	 */
	public $shop_name;

	/**
	 *
	 * @var string
	 */
	public $password;

	/**
	 *
	 * @var string
	 */
	public $salt;

	/**
	 *
	 * @var string
	 */
	public $url;

	/**
	 *
	 * @var string
	 */
	public $twitter;

	/**
	 *
	 * @var string
	 */
	public $etsy;

	/**
	 *
	 * @var string
	 */
	public $facebook;

	/**
	 *
	 * @var string
	 */
	public $websites;

	/**
	 *
	 * @var string
	 */
	public $name;

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
	public $role;

	/**
	 *
	 * @var string
	 */
	public $type;

	/**
	 *
	 * @var string
	 */
	public $gender;

	/**
	 *
	 * @var string
	 */
	public $location;

	/**
	 *
	 * @var string
	 */
	public $about;

	/**
	 *
	 * @var string
	 */
	public $registered;

	/**
	 *
	 * @var string
	 */
	public $login;

	/**
	 *
	 * @var string
	 */
	public $token;

	/**
	 *
	 * @var string
	 */
	public $token_requested;

	/**
	 * @var int
	 */
	public $feature_enabled;

	/**
	 * @var string
	 */
	public $custom_background;

	/**
	 * @var string
	 */
	public $avatar;

	/**
	 * @var int
	 */
	public $contact_for_marketplace;

	/**
	 * @var int
	 */
	public $views;

	/**
	 * @var int
	 */
	public $followers;

	public function url($secureIfHttps = \true)
	{
		$url = Phalcon\DI::getDefault()->get('url');

		$thisUrl = $url->get('profile/view/') . $this->ik;

		if (!$secureIfHttps)
		{
			$thisUrl = str_ireplace('https://', 'http://', $thisUrl);
		}

		return $thisUrl;
	}

	public function shopUrl()
	{
		$url = Phalcon\DI::getDefault()->get('url');

		return $url->get('shops/') . Helpers::url($this->user_name);
	}

	public function backgroundUrl()
	{
		$url = Phalcon\DI::getDefault()->get('imageUrl');

		return $url->get(sprintf('profile/%s', $this->custom_background));
	}

	public function backgroundThumbUrl()
	{
		$url = Phalcon\DI::getDefault()->get('imageUrl');

		if ($this->custom_background)
		{
			return $url->get(sprintf('profile/thumb-%s', $this->custom_background));
		}

		return \false;
	}

	public function avatarUrl()
	{
		$url = Phalcon\DI::getDefault()->get('imageUrl');

		if ($this->avatar)
		{
			return $url->get(sprintf('profile/avatar/%s', $this->avatar));
		}

		return $url->get(sprintf('profile/avatar/person.png'));
	}

	/**
	 * Validations and business logic
	 */
	public function validation()
	{
		$this->validate(
			new Email(
				[
					'field'    => 'email',
					'required' => true,
				]
			)
		);

		if ($this->validationHasFailed() == true)
		{
			return false;
		}
	}

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		$this->setReadConnectionService('readDb');
		$this->setWriteConnectionService('writeDb');

		$this->setSource('User');
		$this->hasOne('ik', 'Shipping', 'user_ik');
		$this->hasMany('ik', 'Sales', 'sold_to_user_ik');
		$this->hasMany('ik', 'Post', 'user_ik');
		$this->hasOne('ik', 'Referral', 'user_ik');
		$this->hasOne('ik', 'Up\Models\Shop', 'user_ik', [
			'alias' => 'Shop',
			'foreignKey' => [
				'action' => Relation::ACTION_CASCADE
			]
		]);
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'                      => 'ik',
			'email'                   => 'email',
			'password'                => 'password',
			'salt'                    => 'salt',
			'url'                     => 'url',
			'twitter'                 => 'twitter',
			'etsy'                    => 'etsy',
			'facebook'                => 'facebook',
			'websites'                => 'websites',
			'name'                    => 'name',
			'role'                    => 'role',
			'type'                    => 'type',
			'gender'                  => 'gender',
			'location'                => 'location',
			'about'                   => 'about',
			'registered'              => 'registered',
			'login'                   => 'login',
			'token'                   => 'token',
			'token_requested'         => 'token_requested',
			'feature_enabled'         => 'feature_enabled',
			'custom_background'       => 'custom_background',
			'contact_for_marketplace' => 'contact_for_marketplace',
			'first_name'              => 'first_name',
			'last_name'               => 'last_name',
			'user_name'               => 'user_name',
			'shop_name'               => 'shop_name',
			'avatar'                  => 'avatar',
			'views'                   => 'views',
			'followers'               => 'followers'
		];
	}
}