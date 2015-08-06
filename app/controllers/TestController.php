<?php

class TestController extends ControllerBase
{

	public function psAction()
	{
		//$user = User::findFirst(['email = ?0', 'bind' => ['jim.nutt@comcast.net']]);

		//$psService = new \Up\Services\PrestashopIntegrationService();

		try
		{
			//$psService->loginToPrestashop($user);
			$u = new User();
			$u->save();
		}
		catch (Exception $e)
		{
			var_dump($e);
			die();
		}
	}

}

