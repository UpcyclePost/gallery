<?php

class PostEventListener extends EventListener
{
	public function afterPostHasBeenCreated($event, $component, $data)
	{
		// Notify subscribers (followers) that the new post has been created.
		$this->__postEvent('user', 'user', 'post', 'created', $data['model']->user_ik, false, $data['model']->ik);
	}

	public function afterListingHasBeenCreated($event, $component, $data)
	{
		// Notify subscribers (followers) that the new listing has been created.
	}

	public function afterPostHasBeenAddedToFavorites($event, $component, $data)
	{
		$this->__postEvent('user', 'user', 'post', 'addedToFavorites', $data['user'], $data['model']->user_ik, $data['model']->ik);
	}

	public function afterPostHasBeenLiked($event, $component, $data)
	{
		$this->__postEvent('user', 'user', 'post', 'liked', $data['user'], $data['model']->user_ik, $data['model']->ik);
	}

	public function afterPostHasBeenShared($event, $component, $data)
	{
		$this->__postEvent('user', 'user', 'post', 'shared', $data['user'], $data['model']->user_ik, $data['model']->ik);
	}
}