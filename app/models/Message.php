<?php

class Message extends \Phalcon\Mvc\Model
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
	public $reply_to_ik;

	/**
	 *
	 * @var string
	 */
	public $sent;

	/**
	 *
	 * @var string
	 */
	public $read;

	/**
	 *
	 * @var integer
	 */
	public $from_user_ik;

	/**
	 *
	 * @var integer
	 */
	public $to_user_ik;

	/**
	 *
	 * @var string
	 */
	public $subject;

	/**
	 *
	 * @var string
	 */
	public $message;

	public function initialize()
	{
		$this->belongsTo('from_user_ik', 'User', 'ik');
	}

	public function slug()
	{
		return sprintf('%s-%s', $this->User->user_name, $this->ik);
	}

	/**
	 * Independent Column Mapping.
	 */
	public function columnMap()
	{
		return [
			'ik'           => 'ik',
			'reply_to_ik'  => 'reply_to_ik',
			'sent'         => 'sent',
			'read'         => 'read',
			'from_user_ik' => 'from_user_ik',
			'to_user_ik'   => 'to_user_ik',
			'subject'      => 'subject',
			'message'      => 'message'
		];
	}
}