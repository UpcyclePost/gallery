<?php

$di->set('router', function ()
{
	$router = new Phalcon\Mvc\Router();

	$router->add('/shop/get-paid', ['controller' => 'shop', 'action' => 'getPaid']);
	$router->add('/shop/list', ['controller' => 'listing', 'action' => 'upload']);
	$router->add('/shop/list/upload', ['controller' => 'listing', 'action' => 'thumbnail']);
	$router->add('/shop/list/details', ['controller' => 'listing', 'action' => 'details']);
	$router->add('/shop/list/submit', ['controller' => 'listing', 'action' => 'submit']);
	$router->add('/shop/list/publish', ['controller' => 'listing', 'action' => 'publish']);
	$router->add('/shop/listing/delete/:params', ['controller' => 'listing', 'action' => 'delete', 'params' => 1]);
	$router->add('/shop/listing/edit/:params', ['controller' => 'listing', 'action' => 'edit', 'params' => 1]);
	$router->add('/shop/listing/edit/publish/:params', ['controller' => 'listing', 'action' => 'publish', 'params' => 1]);
	$router->add('/shops/{slug:[a-zA-Z0-9\-]+}',
	             [
		             'controller' => 'shop',
		             'action'     => 'view'
	             ])
	       ->convert('slug', function ($slug)
	       {
		       return strtolower($slug);
	       });

	$router->add('/profile/view/:params', ['controller' => 'profile', 'action' => 'view', 'params' => 1]);
	$router->add('/profile/messages/send/:params', ['controller' => 'profile', 'action' => 'messageSend', 'params' => 1]);
	$router->add('/profile/message/{slug:.+}',
	             [
		             'controller' => 'profile',
		             'action'     => 'message'
	             ])
	       ->convert('slug', function ($slug)
	       {
		       $s = explode('-', $slug);
		       $message_ik = $s[ count($s) - 1 ];

		       return strtolower(preg_replace('/[^0-9]/', '', $message_ik));
	       });

	$router->add('/market/buy/:params', ['controller' => 'buy', 'action' => 'index', 'params' => 1]);
	$router->add('/market/buy/:int/purchase', ['controller' => 'buy', 'action' => 'process', 'postIk' => 1]);
	$router->add('/market/buy/:int/success', ['controller' => 'buy', 'action' => 'success', 'postIk' => 1]);

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