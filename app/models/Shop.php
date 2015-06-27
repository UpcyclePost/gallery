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
	public $background;

	/**
	 *
	 * @var int
	 */
	public $views;

	/**
	 *
	 * @var datetime
	 */
	public $logo;

	public function initialize()
	{
		$this->setSource('shops');
		$this->belongsTo('user_ik', 'User', 'ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'         => 'ik',
			'user_ik'    => 'user_ik',
			'background' => 'background',
			'logo'       => 'logo',
			'views'      => 'views'
		];
	}

	public function backgroundUrl()
	{
		$url = \Phalcon\DI::getDefault()->get('imageUrl');

		return $url->get(sprintf('shop/background/%s', $this->background));
	}

	public function backgroundThumbUrl()
	{
		$url = \Phalcon\DI::getDefault()->get('imageUrl');

		if ($this->background)
		{
			return $url->get(sprintf('shop/background/thumb-%s', $this->background));
		}

		return \false;
	}

	public function logoUrl()
	{
		$url = \Phalcon\DI::getDefault()->get('imageUrl');

		if ($this->logo)
		{
			return $url->get(sprintf('shop/logo/%s', $this->logo));
		}

		return $url->get(sprintf('profile/avatar/person.png'));
	}
}