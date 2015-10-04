<?php

class TestController extends ControllerBase
{

	public function psAction()
	{
		$user = User::findFirst(['email = ?0', 'bind' => ['abluedragonfly@yahoo.com']]);

		$psService = new \Up\Services\PrestashopIntegrationService();

		try
		{
			$psService->loginToPrestashop($user);
		}
		catch (Exception $e)
		{
			var_dump($e);
			die();
		}
	}

	public function cropAction()
	{
		$imageService = new ImageProcessingService('/var/www/html/shop/img/p/4/0/0/1/4001.jpg');
		$imageService->createThumbnail('/var/www/html/files/posts/thumbnails/products/TEST-IM-2.png', 372, 238, true);
	}

}

