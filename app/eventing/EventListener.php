<?php

class EventListener
{
	/**
	 * @param       $eventType
	 * @param       $eventName
	 * @param       $senderIk
	 * @param       $recipientIk
	 * @param mixed $itemIk
	 */
	protected function __postEvent($senderType, $recipientType, $eventType, $eventName, $senderIk, $recipientIk = \false, $itemIk = \false)
	{
		try
		{
			$feed = new Feed();
			$feed->created = date('Y-m-d H:i:s');
			$feed->sender_ik = $senderIk;
			$feed->event_type = $eventType;
			$feed->event_name = $eventName;
			$feed->sender = $senderType;
			$feed->recipient = $recipientType;

			if ($recipientIk)
			{
				$feed->recipient_ik = $recipientIk;
			}

			if ($itemIk)
			{
				$feed->item_ik = $itemIk;
			}

			$feed->save();
		}
		catch (Exception $e)
		{
		}
	}
}