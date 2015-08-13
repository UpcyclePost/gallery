<?php

class Shipping extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $user_ik;

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

	public function initialize()
	{
		$this->setReadConnectionService('readDb');
		$this->setWriteConnectionService('writeDb');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'user_ik' => 'user_ik',
			'address' => 'address',
			'city'    => 'city',
			'st'      => 'st',
			'zip'     => 'zip'
		];
	}
}