<?php

class Survey extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $shop_ik;

	/**
	 *
	 * @var string
	 */
	public $answer;

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'shop_ik' => 'shop_ik',
			'answer'  => 'answer'
		];
	}
}