<?php

use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Relation;

class Referral extends Model
{
	public $user_ik;
	public $source;

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		$this->setReadConnectionService('readDb');
		$this->setWriteConnectionService('writeDb');

		$this->setSource('Referrals');
		$this->hasOne('user_ik', 'User', 'ik');
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'user_ik' => 'user_ik',
			'source'  => 'source'
		];
	}
}