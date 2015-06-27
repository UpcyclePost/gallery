<?php

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Acl;

class Security extends Plugin
{
	public function getAcl()
	{
		//Create the ACL
		$acl = new Phalcon\Acl\Adapter\Memory();
		$acl->setDefaultAction(Phalcon\Acl::DENY);

		$roles = [
			'admins'     => new Phalcon\Acl\Role('Admins'),
			'moderators' => new Phalcon\Acl\Role('Moderators'),
			'users'      => new Phalcon\Acl\Role('Users'),
			'guests'     => new Phalcon\Acl\Role('Guests')
		];

		foreach ($roles AS $role)
		{
			$acl->addRole($role);
		}

		$privateResources = [
			'post'    => ['post', 'remove', 'edit'],
			'profile' => ['index', 'me', 'logout', 'settings', 'dashboard', 'edit', 'feed', 'uploadAvatar', 'uploadBackground', 'registerThankYou'],
			'message' => ['inbox', 'sent', 'view', 'send'],
			'buy'     => ['index', 'process', 'success'],
			'shop'    => ['customize', 'uploadLogo', 'uploadBackground'],
			'listing' => ['upload', 'thumbnail', 'edit', 'delete', 'publish', 'submit', 'details'],
			'follow'  => ['user', 'shop']
		];

		foreach ($privateResources as $resource => $actions)
		{
			$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
		}

		$publicResources = [
			'post'       => ['index', 'idea', 'render', 'details', 'upload', 'submit', 'like', 'report', 'send', 'share'],
			'gallery'    => ['index', 'view', 'more'],
			'profile'    => ['login', 'register', 'forgotPassword', 'resetPassword', 'view'],
			'contact'    => ['index'],
			'error'      => ['*'],
			'index'      => ['*'],
			'rest'       => ['*'],
			'about'      => ['*'],
			'terms'      => ['*'],
			'privacy'    => ['*'],
			'newsletter' => ['subscribe'],
			'shop'       => ['view', 'shops', 'redirect'],
			'search'     => ['*']
		];

		foreach ($publicResources as $resource => $actions)
		{
			$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
		}

		$moderatorResources = [
			'profile' => ['impersonate', 'stopImpersonating']
		];

		foreach ($moderatorResources as $resource => $actions)
		{
			$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
		}

		$adminResources = [
			'post' => ['delete'],
		    'test' => ['*']
		];

		foreach ($adminResources as $resource => $actions)
		{
			$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
		}

		//grant all roles access to public resources
		foreach ($roles as $role)
		{
			foreach ($publicResources as $resource => $actions)
			{
				$acl->allow($role->getName(), $resource, $actions);
			}
		}

		//grant Users access to private resources
		foreach ($privateResources AS $resource => $actions)
		{
			foreach ($actions AS $action)
			{
				$acl->allow('Users', $resource, $action);
				$acl->allow('Admins', $resource, $action);
				$acl->allow('Moderators', $resource, $action);
			}
		}

		foreach ($moderatorResources AS $resource => $actions)
		{
			foreach ($actions AS $action)
			{
				$acl->allow('Admins', $resource, $action);
				$acl->allow('Moderators', $resource, $action);
			}
		}

		//grant Admins access to admin resources
		foreach ($adminResources AS $resource => $actions)
		{
			foreach ($actions AS $action)
			{
				$acl->allow('Admins', $resource, $action);
			}
		}

		return $acl;
	}

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		//Check whether the "auth" variable exists in session to define the active role
		$auth = $this->session->get('auth');
		if (!$auth)
		{
			$role = 'Guests';
		}
		else
		{
			$role = (isset($auth[ 'originalRole' ])) ? $auth[ 'originalRole' ] : $auth[ 'role' ];
		}

		//Take the active controller/action from the dispatcher
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		//Obtain the ACL list
		$acl = $this->getAcl();

		//Check if the Role have access to the controller (resource)
		$allowed = $acl->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW)
		{
			$this->view->disable();
			$this->session->set('requested-resource', $this->router->getRewriteUri());
			//If the Role doesn't have access forward to the index controller
			$this->flash->error("You must log in to continue.");

			$this->response->redirect('profile/login');

			//Returning "false" we tell to the dispatcher to stop the current operation
			return false;
		}
	}
}