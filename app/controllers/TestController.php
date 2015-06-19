<?php

class TestController extends ControllerBase
{

	public function psAction()
	{
		$user = User::findFirst(['email = ?0', 'bind' => ['erinsapparel@gmail.com']]);

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

