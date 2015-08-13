<?php

$di->set('router', function ()
{
	$router = new Phalcon\Mvc\Router();

	$router->add('/search/quick/products', ['controller' => 'search', 'action' => 'countProducts']);
	$router->add('/search/quick/users', ['controller' => 'search', 'action' => 'countUsers']);
	$router->add('/search/quick/shops', ['controller' => 'search', 'action' => 'countShops']);
	$router->add('/search/quick/ideas', ['controller' => 'search', 'action' => 'countIdeas']);

	$router->add('/shops/:params', ['controller' => 'shop', 'action' => 'view', 'params' => 1]);
	$router->add('/shops/{slug:[0-9]+}', ['controller' => 'shop', 'action' => 'redirect'])->convert('slug', function($slug) {
		return $slug;
	});
	$router->add('/shops', ['controller' => 'shop', 'action' => 'shops']);
	$router->add('/shops/my/customize', ['controller' => 'shop', 'action' => 'customize']);
	$router->add('/shops/my/customize/upload/logo', ['controller' => 'shop', 'action' => 'uploadLogo']);
	$router->add('/shops/my/customize/upload/background', ['controller' => 'shop', 'action' => 'uploadBackground']);

	$router->add('/profile/view/:params', ['controller' => 'profile', 'action' => 'view', 'params' => 1]);
	$router->add('/profile/register/thankyou', ['controller' => 'profile', 'action' => 'registerThankYou']);
	$router->add('/profile/impersonate/end', ['controller' => 'profile', 'action' => 'stopImpersonating']);
	$router->add('/profiles/:params', ['controller' => 'profile', 'action' => 'view', 'params' => 1]);

	$router->add('/profile/edit/upload/avatar', ['controller' => 'profile', 'action' => 'uploadAvatar']);
	$router->add('/profile/edit/upload/background', ['controller' => 'profile', 'action' => 'uploadBackground']);

	// Messaging
	$router->add('/profile/messages', ['controller' => 'message', 'action' => 'inbox']);
	$router->add('/profile/messages/send/:params', ['controller' => 'message', 'action' => 'send', 'params' => 1]);
	$router->add('/profile/messages/sent', ['controller' => 'message', 'action' => 'sent']);
	$router->add('/profile/message/{slug:.+}',
	             [
		             'controller' => 'message',
		             'action'     => 'view'
	             ])
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $message_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $message_ik));
	       });

	$router->add('/profile/forgot-password', ['controller' => 'profile', 'action' => 'forgotPassword']);
	$router->add('/profile/reset-password', ['controller' => 'profile', 'action' => 'resetPassword']);

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+}/more',
	             [
		             'controller' => 'gallery',
		             'action'     => 'more'
	             ]
	)->convert('slug', function ($slug)
	{
		return strtolower(preg_replace('/[^a-zA-Z]/', '', $slug));
	});

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+}',
	             [
		             'controller' => 'gallery',
		             'action'     => 'index'
	             ]
	)->convert('slug', function ($slug)
	{
		return strtolower(preg_replace('/[^a-zA-Z]/', '', $slug));
	});

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+/.+\-[0-9]+}',
	             [
		             'controller' => 'gallery',
		             'action'     => 'view'
	             ]
	)
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $post_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $post_ik));
	       });

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+/.+\-[0-9]+}/up',
	             [
		             'controller' => 'post',
		             'action'     => 'like'
	             ]
	)
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $post_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $post_ik));
	       });

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+/.+\-[0-9]+}/share',
	             [
		             'controller' => 'post',
		             'action'     => 'share'
	             ]
	)
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $post_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $post_ik));
	       });

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+/.+\-[0-9]+}/remove',
	             [
		             'controller' => 'post',
		             'action'     => 'remove'
	             ]
	)
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $post_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $post_ik));
	       });

	$router->add('/gallery/{slug:[a-zA-Z0-9 \-]+/.+\-[0-9]+}/report',
	             [
		             'controller' => 'post',
		             'action'     => 'report'
	             ]
	)
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $post_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $post_ik));
	       });

	return $router;
});