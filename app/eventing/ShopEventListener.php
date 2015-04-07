<?php

class ShopEventListener extends EventListener
{
	public function whenShopHasBeenFollowed($event, $component, $data)
	{
		// Notify shop owner that their shop has been followed
		$this->__postEvent('user', 'shop', 'shop', 'followed', $data['subscriber'], $data['subscribed_to']);
	}
}