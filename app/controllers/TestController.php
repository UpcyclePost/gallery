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

}

