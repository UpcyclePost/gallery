<?php

class Feed extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $ik;

	/**
	 *
	 * @var datetime
	 */
	public $created;

	/**
	 *
	 * @var string
	 */
	public $sender;

	/**
	 *
	 * @var int
	 */
	public $sender_ik;

	/**
	 *
	 * @var string
	 */
	public $recipient;

	/**
	 *
	 * @var int
	 */
	public $recipient_ik;

	/**
	 *
	 * @var int
	 */
	public $item_ik;

	/**
	 *
	 * @var string
	 */
	public $event_name;

	/**
	 *
	 * @var string
	 */
	public $event_type;

	public function initialize()
	{
		$this->setReadConnectionService('readDb');
		$this->setWriteConnectionService('writeDb');

		$this->hasOne('item_ik', 'Post', 'ik');
		$this->hasOne('sender_ik', 'User', 'ik', ['alias' => 'Sender']);
		$this->hasOne('recipient_ik', 'User', 'ik', ['alias' => 'Recipient']);
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'           => 'ik',
			'created'      => 'created',
			'sender'       => 'sender',
			'sender_ik'    => 'sender_ik',
			'recipient'    => 'recipient',
			'recipient_ik' => 'recipient_ik',
			'item_ik'      => 'item_ik',
			'event_type'   => 'event_type',
			'event_name'   => 'event_name'
		];
	}
}