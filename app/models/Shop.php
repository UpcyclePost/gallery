<?php namespace Up\Models;

use Up\Services\PrestashopIntegrationService;

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
	 * @var string
	 */
	public $logo;

	/**
	 *
	 * @var string
	 */
	public $description;

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
			'ik'          => 'ik',
			'user_ik'     => 'user_ik',
			'background'  => 'background',
			'logo'        => 'logo',
			'description' => 'description',
			'views'       => 'views'
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

	public function totalProducts()
	{
		$prestashopService = new PrestashopIntegrationService();
		return $prestashopService->countProducts($this->User);
	}

	public function activeProducts()
	{
		$prestashopService = new PrestashopIntegrationService();
		return $prestashopService->findRecentProducts(\false, $this->User);
	}
}