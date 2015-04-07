<?php

class UserEventListener extends EventListener
{
	public function whenUserHasBeenFollowed($event, $component, $data)
	{
		// Notify user that they have been followed
		$this->__postEvent('user', 'user', 'user', 'followed', $data['subscriber'], $data['subscribed_to']);

		// Probably send an email here.
	}

	public function whenPrivateMessageHasBeenSent($event, $component, $data)
	{
		// Notify user that they have received a new private message.
		$this->__postEvent('user', 'user', 'user', 'messaged', $data['sender'], $data['recipient'], $data['messageIk']);

		// Probably send an email here.
	}
}