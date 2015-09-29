<?php

class Market extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $post_ik;

	/**
	 *
	 * @var integer
	 */
	public $shop_ik;

	/**
	 *
	 * @var decimal
	 */
	public $price;

	/**
	 * @var decimal
	 */
	public $shipping_price;

	/**
	 * @var string
	 */
	public $ships_to;

	/**
	 *
	 * @var string
	 */
	public $status;

	/**
	 *
	 * @var integer
	 */
	public $sold_to_user_ik;

	/**
	 *
	 * @var datetime
	 */
	public $listed_at;

	/**
	 *
	 * @var datetime
	 */
	public $sold_at;

	/**
	 *
	 * @var datetime
	 */
	public $updated_at;

	/**
	 *
	 * @var int
	 */
	public $deleted;

	/**
	 *
	 * @var datetime
	 */
	public $deleted_at;

	public function initialize()
	{
		$this->setReadConnectionService('readDb');
		$this->setWriteConnectionService('writeDb');

		$this->useDynamicUpdate(true);

		$this->belongsTo('post_ik', 'Post', 'ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'post_ik'         => 'post_ik',
			'shop_ik'         => 'shop_ik',
			'price'           => 'price',
			'shipping_price'  => 'shipping_price',
			'ships_to'        => 'ships_to',
			'status'          => 'status',
			'sold_to_user_ik' => 'sold_to_user_ik',
			'listed_at'       => 'listed_at',
			'sold_at'         => 'sold_at',
			'updated_at'      => 'updated_at',
			'deleted'         => 'deleted',
			'deleted_at'      => 'deleted_at'
		];
	}

	public function isEditable($authenticatedUserIk)
	{
		if (!$this->Post)
		{
			return 'The listing could not be found.';
		}
		else if ($this->Post->visible != 1)
		{
			return 'The listing is not available.';
		}
		else if ($this->deleted != 0)
		{
			return 'The listing has been deleted.';
		}
		else if ($this->Post->user_ik != $authenticatedUserIk)
		{
			return 'The listing could not be found.';
		}
		else if ($this->Post->type != 'market')
		{
			return 'This item is not for sale.';
		}
		else
		{
			if ($this->status == 'available')
			{
				return \true;
			}
			else
			{
				switch ($this->status)
				{
					case 'deleted':
						return 'You cannot edit deleted listings.';
					case 'sold':
						return 'This item has been sold and can no longer be edited.';
					default:
						return 'This item is not available and cannot be edited.';
				}
			}
		}

		return 'This listing cannot be edited.';
	}
}