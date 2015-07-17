<?php

class ProfileController extends ControllerBase
{
	public function indexAction()
	{

	}

	public function impersonateAction()
	{
		if ($this->auth[ 'role' ] == 'Moderators' || $this->auth[ 'role' ] == 'Admins')
		{
			if ($this->request->isPost() && $this->request->has('user'))
			{
				$this->session->set('originalAuth', $this->auth);

				$user = User::findFirst(['(email = ?0 OR user_name = ?0) AND role != ?1', 'bind' => [$this->request->getPost('user'), 'Admins']]);

				if ($user)
				{
					$this->__setLogin($user, \true);

					return $this->response->redirect('');
				}
				else
				{
					$this->flash->error('The specified user was not found.');

					return $this->response->redirect('profile/impersonate');
				}
			}
		}
		else
		{
			return $this->response->redirect('error/404');
		}
	}

	public function stopImpersonatingAction()
	{
		if ($this->session->has('originalAuth'))
		{
			$this->session->set('auth', $this->session->get('originalAuth'));

			return $this->response->redirect('');
		}
		else
		{
			// How did we get here?
			return $this->logoutAction();
		}
	}

	public function editAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/profile/edit.js')
		             ->addJs('js/libraries/jquery/jquery.blockUI.js')
		             ->addJs('js/libraries/dropzone/dropzone.js');

		$this->view->flow = 'member';
		if ($this->request->has('flow'))
		{
			$this->view->flow = $this->request->get('flow');
		}
		else if ($this->request->hasPost('flow'))
		{
			$this->view->flow = $this->request->getPost('flow');
		}

		// If the variables were changed either through direct action or accident
		// reset the flow to member.
		if ($this->view->flow != 'member' && $this->view->flow != 'shop')
		{
			$this->view->flow = 'member';
		}

		if (($profile = $this->__getProfile()) !== \false)
		{
			$this->view->websites = json_decode($profile->websites, \true) ?: [];

			if ($this->request->isPost())
			{
				$this->view->disable();

				if ($this->request->has('gender'))
				{
					$profile->gender = $this->request->getPost('gender');
				}
				if ($this->request->has('firstName'))
				{
					$profile->first_name = strip_tags($this->request->getPost('firstName'));
				}
				if ($this->request->has('lastName'))
				{
					$profile->last_name = strip_tags($this->request->getPost('lastName'));
				}
				if ($this->request->has('firstName'))
				{
					$profile->name = sprintf('%s %s', $profile->first_name, $profile->last_name);
				}
				if ($this->request->has('location'))
				{
					$profile->location = substr(strip_tags($this->request->getPost('location')), 0, 35);
				}
				if ($this->request->has('about'))
				{
					$profile->about = strip_tags($this->request->getPost('about'));
				}

				if ($this->request->hasPost('website') && $this->request->hasPost('website_url'))
				{
					$websites = [];
					$postWebsites = $this->request->getPost('website');
					$postUrls = $this->request->getPost('website_url');
					for ($i = 0; $i < count($postWebsites); $i++)
					{
						if (trim($postWebsites[ $i ]) != '')
						{
							if (isset($postUrls[ $i ]) && trim($postUrls[ $i ]) != '')
							{
								$websites[] = ['type' => $postWebsites[ $i ], 'url' => $postUrls[ $i ]];
							}
						}
					}

					if (!empty($websites))
					{
						$profile->websites = json_encode($websites);
					}
				}

				if ($this->request->hasPost('background'))
				{
					if ($profile->custom_background)
					{
						unlink(sprintf('%s%s', $this->config->application->profileImageDir, $profile->custom_background));
					}

					$profile->custom_background = $this->request->getPost('background');
				}

				if ($this->request->hasPost('avatar'))
				{
					if ($profile->avatar)
					{
						unlink(sprintf('%s%s', $this->config->application->profileAvatarDir, $profile->avatar));
					}

					$profile->avatar = $this->request->getPost('avatar');
				}

				if ($profile->update())
				{
					$this->flash->success('Your profile has been updated.');

					if ($this->auth[ 'name' ] != $profile->name)
					{
						$this->auth[ 'name' ] = $profile->name;
						$this->session->set('auth', $this->auth);
					}

					if ($this->request->hasPost('flow'))
					{
						if ($this->request->getPost('flow') == 'shop')
						{
							return $this->response->redirect('shop/module/marketplace/sellerrequest');
						}
					}

					return $this->response->redirect($profile->url(\false), \true);
				}
				else
				{
					$this->flash->error('There was an error saving your profile.');
				}

				return $this->response->redirect('profile/edit');
			}

			$this->view->profile = $profile;
		}
		else
		{
			return $this->response->redirect('profile/edit');
		}
	}

	public function uploadAvatarAction()
	{
		if (!$this->request->isPost() || !$this->request->isAjax())
		{
			// This function only accepts POSTed requests
			return $this->response->redirect('profile/edit');
		}
		else
		{
			// Nothing to see here
			$this->view->disable();

			if (!$this->request->hasFiles())
			{
				echo json_encode(['success' => false]);
			}
			else
			{
				if (($profile = $this->__getProfile()) !== \false)
				{
					foreach ($this->request->getUploadedFiles() AS $file)
					{
						if ($file->getType() == 'image/gif' || $file->getType() == 'image/png' || $file->getType() == 'image/jpeg')
						{
							$fileName = sprintf('%s-%s-%s%s', $profile->ik, Helpers::createShortCode($profile->ik), time(), strrchr($file->getName(), '.'));

							//profileImageDir
							$permanentFile = $this->config->application->profileAvatarDir . $fileName;

							if ($file->moveTo($permanentFile))
							{
								// Create a thumbnail of the profile background
								$imageProcessingService = new ImageProcessingService($permanentFile);

								$imageProcessingService->createThumbnail($permanentFile, 100, 100, \true);

								echo json_encode(['success' => true, 'data' => ['file' => $fileName, 'preview' => $this->imageUrl->get(sprintf('profile/avatar/%s', $fileName))]]);

								return;
							}
						}

						break;
					}
				}
			}
		}

		echo json_encode(['success' => false]);
	}

	public function uploadBackgroundAction()
	{
		if (!$this->request->isPost() || !$this->request->isAjax())
		{
			// This function only accepts POSTed requests
			return $this->response->redirect('profile/edit');
		}
		else
		{
			// Nothing to see here
			$this->view->disable();

			if (!$this->request->hasFiles())
			{
				echo json_encode(['success' => false]);
			}
			else
			{
				if (($profile = $this->__getProfile()) !== \false)
				{
					foreach ($this->request->getUploadedFiles() AS $file)
					{
						if ($file->getType() == 'image/gif' || $file->getType() == 'image/png' || $file->getType() == 'image/jpeg')
						{
							$fileName = sprintf('%s-%s-%s%s', $profile->ik, Helpers::createShortCode($profile->ik), time(), strrchr($file->getName(), '.'));
							$thumbnailFileName = sprintf('thumb-%s', $fileName);

							//profileImageDir
							$permanentFile = $this->config->application->profileImageDir . $fileName;
							$thumbnailFile = sprintf('%s%s', $this->config->application->profileImageDir, $thumbnailFileName);

							if ($file->moveTo($permanentFile))
							{
								// Create a thumbnail of the profile background
								$imageProcessingService = new ImageProcessingService($permanentFile);
								list($width, $height, $type, $attr) = @getimagesize($permanentFile);

								$imageProcessingService->createThumbnail($thumbnailFile, ($width >= 244) ? 244 : $width);

								echo json_encode(['success' => true, 'data' => ['file' => $fileName, 'preview' => $this->imageUrl->get(sprintf('profile/%s', $thumbnailFileName))]]);

								return;
							}

							// Let's plan to have more than one background image in the future - perhaps rotating?
							break;
						}
					}
				}
			}
		}

		echo json_encode(['success' => false]);
	}

	public function settingsAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/profile/settings.js');

		$auth = $this->session->get('auth');

		$profile = User::findFirst($auth[ 'ik' ]);

		if ($this->request->isPost())
		{
			$this->view->disable();

			if ($this->request->hasPost('password'))
			{
				$_password = $this->request->getPost('password');
				$_newPassword = $this->request->getPost('newPassword');
				$hash = password_hash($_password, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => $profile->salt]);

				if ($hash == $profile->password)
				{
					$_passwordMatch = $this->request->getPost('newPasswordConfirm') == $_newPassword;

					if (strlen($_password) < 8)
					{
						$this->flash->error('Your new password must be at least 8 characters.');
					}
					else if (!$_passwordMatch)
					{
						// Password mismatch, redirect
						$this->flash->error('Your new passwords do not match.');
					}
					else
					{
						$profile->password = password_hash($_newPassword, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => $profile->salt]);

						if ($profile->update())
						{
							$this->flash->success('Your password has been changed.');
						}
						else
						{
							$this->flash->error('There was an error updating your password.');
						}
					}
				}
				else
				{
					$this->flash->error('You must enter your current password.');
				}
			}
			else if ($this->request->hasPost('userName'))
			{
				$username = $this->__getUserNameFromPost();

				if ($profile->user_name != $username)
				{
					if (strlen($username) >= 5)
					{
						if ($this->__userNameExists($username, \true))
						{
							$this->flash->error('That username is already taken.');
						}
						else
						{
							$profile->user_name = $username;

							if ($profile->update())
							{
								$this->__updateSessionAuth('user_name', $profile->user_name);
								$this->flash->success('Your username has been changed.');
							}
							else
							{
								$this->flash->error('There was an error updating your username.');
							}
						}
					}
					else
					{
						$this->flash->error('The selected username is invalid.');
					}
				}
			}

			return $this->response->redirect('profile/settings');
		}

		$this->view->profile = $profile;
	}

	public function logoutAction()
	{
		$this->cookies->set('disqus', base64_encode(json_encode([])), time() + 15 * 86400);
		$this->cookies->delete('disqus');
		$this->session->destroy();

		$prestashopIntegrationService = new \Up\Services\PrestashopIntegrationService();
		$prestashopIntegrationService->logoutOfPrestashop();

		return $this->response->redirect('profile/login');
	}

	public function resetPasswordAction()
	{
		$this->view->title = 'Reset Password | UpcyclePost';
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/profile/reset-password.js');

		$token = '';
		if (count($_GET) > 0 && isset($_GET[ 'r' ]))
		{
			$token = $_GET[ 'r' ];
		}

		//Check token
		$when = date('Y-m-d H:i:s', strtotime('-2 hours'));
		$user = User::findFirst(['token = ?0 AND token_requested > ?1', 'bind' => [$token, $when]]);

		if ($user !== false)
		{
			if ($this->request->isPost())
			{
				//Update password
				$_password = $this->request->getPost('password');
				$_passwordMatch = $this->request->getPost('passwordConfirm') == $_password;

				if (strlen($_password) < 8)
				{
					$this->flash->error('Your new password must be at least 8 characters.');
				}
				else if (!$_passwordMatch)
				{
					$this->flash->error('Your passwords do not match.');
				}
				else
				{
					$hash = password_hash($_password, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => $user->salt]);
					$user->password = $hash;
					$user->token = '';

					if ($user->update())
					{
						$this->flash->success('Your password has been changed.');

						return $this->response->redirect('profile/settings');
					}
					else
					{
						$this->flash->error('Something went wrong, please try again.');
					}
				}
			}
		}
		else
		{
			$this->flash->error('Please try resetting your password again.');

			return $this->response->redirect('/profile/forgot-password');
		}
	}

	public function forgotPasswordAction()
	{
		$this->view->title = 'Forgot Password | UpcyclePost';

		if ($this->request->isPost())
		{
			$reset = $this->session->get('password-reset-sent');

			if ($reset && (time() + 300) > $reset)
			{
				$this->flash->error('You have already requested a password reset, please wait to try again.');
			}
			else
			{
				$_email = $this->request->getPost('email');
				$user = User::findFirst(['email = ?0', 'bind' => [$_email]]);

				if ($user !== false)
				{
					$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
					$token = hash('sha256', $user->email . $salt . uniqid('', true));

					$user->token = $token;
					$user->token_requested = date('Y-m-d H:i:s');
					if ($user->save())
					{
						ob_start();
						$this->view->partial('emails/reset-password', ['token' => $token]);
						$body = ob_get_contents();
						ob_end_clean();

						$success = Helpers::sendEmail($user->email, 'UpcyclePost password reset', $body);

						if ($success)
						{
							$this->session->set('password-reset-sent', time());
							$this->flash->success('An email has been sent to you with instructions on resetting your password.');
						}
					}
				}
			}

			$this->response->redirect('profile/login');
		}
	}

	public function viewAction($userId = \false)
	{
		$this->assets->addJs('js/gallery/layout.js')
		             ->addJs('js/social/follow.js')
		             ->addJs('js/profile/view.js');

		if (!$userId || is_null($userId))
		{
			if (isset($this->auth[ 'ik' ]))
			{
				$userId = $this->auth[ 'ik' ];
			}
			else
			{
				// Not viewing any profile
				return $this->response->redirect('');
			}
		}

		$user = User::findFirst($userId);

		if ($user === false)
		{
			return $this->response->redirect('gallery');
		}

		$this->view->title = sprintf('%s | Upcycling Ideas, Articles and Products | UpcyclePost', $user->user_name);
		$this->view->metaDescription = sprintf("Discover the greatest upcycled products and post what inspires you. %s, %s : %s",
		                                       $user->user_name, $user->location, str_replace('"', "'", Helpers::tokenTruncate($user->about, 65)));

		$this->view->profile = $user;

		$this->view->websites = json_decode($user->websites, \true) ?: [];

		if ($user->custom_background)
		{
			$this->view->og_img = $user->backgroundUrl();
			list($width, $height, $type, $attr) = @getimagesize($this->config->application->profileImageDir . $user->custom_background);
			$this->view->og_img_size = [$width, $height];
			$this->view->og_description = $this->view->metaDescription;
		}

		$this->view->results = (new SearchService())->findPosts(\false, \false, 0, 150, $user->ik);
		$this->view->shop_results = (new \Up\Services\PrestashopIntegrationService())->findRecentProducts(20, $user);

		$subscriptionService = new SubscriptionService();
		$this->view->following = $subscriptionService->userIsSubscribed(
			'user',
			$this->auth[ 'ik' ],
			$user->ik
		);

		$this->view->canonical_url = $user->url();
		$this->view->isOwnProfile = ($this->view->isLoggedIn && $this->auth[ 'ik' ] == $user->ik);

		if (!$this->view->isOwnProfile)
		{
			$user->views = $user->views + 1;
			$user->save();
		}
	}

	public function registerAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/profile/register.js');

		$this->view->title = 'Sign Up | UpcyclePost';

		if (isset($this->auth[ 'ik' ]))
		{
			// The user is already logged in...
			return $this->response->redirect('profile/settings');
		}

		if ($this->request->isPost())
		{
			$_email = $this->request->getPost('email');
			$_password = $this->request->getPost('password');

			$user = User::findFirst(['email = ?0', 'bind' => [$_email]]);

			// Not yet a member, sign up

			if ($user !== false)
			{
				// User already exists
				$this->flash->error('This email address is already registered');

				return $this->response->redirect('profile/register');
			}
			else
			{
				$_userName = $this->__getUserNameFromPost();
				if ($this->__userNameExists($_userName))
				{
					$this->flash->error('This username is already taken.');

					return $this->response->redirect('profile/register');
				}
				else
				{

					$_passwordMatch = $this->request->getPost('passwordConfirm') == $_password;

					if (!$_passwordMatch)
					{
						// Password mismatch, redirect
						$this->flash->error('Your passwords do not match.');

						return $this->response->redirect('profile/register');
					}
					else
					{
						$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
						$hash = password_hash($_password, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => $salt]);

						$_firstName = $this->request->getPost('firstName');
						$_lastName = $this->request->getPost('lastName');

						if (!$_firstName || !$_lastName)
						{
							// Name doesn't look valid
							$this->flash->error('The name you entered doesn\'t look right, please try again.');

							return $this->response->redirect('profile/register');
						}

						// Looks good, create the user
						$user = new User();
						$user->email = $_email;
						$user->password = $hash;
						$user->salt = $salt;
						$user->name = sprintf('%s %s', $_firstName, $_lastName);
						$user->first_name = $_firstName;
						$user->last_name = $_lastName;
						$user->user_name = $_userName;
						$user->role = 'Users';
						$user->type = 'member';
						$user->gender = 'Unspecified';
						$user->registered = date('Y-m-d H:i:s');
						$user->login = date('Y-m-d H:i:s');
						$user->token = '';
						$user->about = '';
						$user->location = '';

						if (!$user->save())
						{
							// Error saving user
							$this->flash->error('Something unexpected happened, please try again.');

							return $this->response->redirect('profile/register');
						}
						else
						{
							// Save User Referral
							$referral = new Referral();
							$referral->user_ik = $user->ik;
							$referral->source = $this->request->getPost('source');
							$referral->save();

							// Subscribe to MC
							if ($this->request->hasPost('mcRegister'))
							{
								// We should use the MailChimp API for this, but it requires some additional MC setup.
								file_get_contents(sprintf('http://upcyclepost.us8.list-manage.com/subscribe/post-json?u=30f90ae9c8a620f475f37c82b&id=71699905b3&c=jQuery1110045520341652445495_1425168664704&EMAIL=%s', urlencode($_email)));

								$marketing = new Marketing();
								$marketing->email = $_email;
								$marketing->ip = (isset($_SERVER[ 'REMOTE_ADDR' ])) ? $_SERVER[ 'REMOTE_ADDR' ] : '';
								$marketing->forwarded_ip = (isset($_SERVER[ 'HTTP_X_FORWARDED_FOR' ])) ? $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] : '';
								$marketing->subscribed = date('Y-m-d H:i:s');

								$marketing->save();
							}

							// Register Prestahop account
							$psService = new \Up\Services\PrestashopIntegrationService();
							$psService->registerPrestashopUser($user);
						}
					}
				}
			}

			$path = 'profile/login';

			if (($result = $this->__signIn($_email, $_password)) === true)
			{
				$redirect = $this->session->get('requested-resource');
				if (!$redirect)
				{
					if ($this->request->hasQuery('redirect'))
					{
						$redirect = '/profile/disqus';
					}
					else
					{
						$redirect = $this->session->get('redirectTo');
					}
				}

				if ($redirect != false)
				{
					$path = substr($redirect, 1);
					$this->session->remove('requested-resource');
				}
				else
				{
					$path = 'profile/register/thankyou';
				}
			}
			else
			{
				$this->flash->error($result);
			}

			return $this->response->redirect($path);
		}
	}

	public function registerThankYouAction()
	{
		$this->view->title = 'Thank You | UpcyclePost';
		$profile = $this->__getProfile();

		$this->view->profile_url = $profile->url();
	}

	public function loginAction()
	{
		$this->view->title = 'Sign In | UpcyclePost';

		if ($auth = $this->session->get('auth'))
		{
			if (isset($auth[ 'ik' ]))
			{
				// Already logged in, redirect to settings
				return $this->response->redirect('profile/settings');
			}
		}

		if ($this->request->get('redirect') && $this->request->get('redirect') == 'cart')
		{
			$this->session->set('requested-resource', '/shop/quick-order');
		}
		else if ($this->request->get('back'))
		{
			$back = $this->request->get('back');
			if (substr($back, 0, 4) == 'http')
			{
				$this->session->set('requested-resource', $back);
			}
			else
			{
				$this->session->set('requested-resource', sprintf('/shop/index.php?controller=%s', $back));
			}
		}

		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/profile/login.js');

		if ($this->request->isPost())
		{
			$path = 'profile/login';

			$_email = $this->request->getPost('email');
			$_password = $this->request->getPost('password');

			if (($result = $this->__signIn($_email, $_password)) === true)
			{
				// Redirect
				$redirect = $this->session->get('requested-resource');
				if (!$redirect)
				{
					if ($this->request->hasQuery('redirect'))
					{
						$redirect = '/profile/disqus';
					}
					else
					{
						$redirect = $this->session->get('redirectTo');
					}
				}

				if ($redirect != false)
				{
					// Only substring (to remove leading slash) if the redirect starts with a slash.
					if (substr($redirect, 0, 1) == '/')
					{
						$path = substr($redirect, 1);
					}
					else
					{
						$path = $redirect;
					}
					$this->session->remove('requested-resource');
				}
				else
				{
					$path = '';
				}
			}
			else
			{
				$this->flash->error($result);
			}

			return $this->response->redirect($path);
		}
	}

	public function dashboardAction()
	{
		$profile = User::findFirst($this->auth[ 'ik' ]);

		$this->view->name = $profile->name;

		$this->view->purchases = Sale::find([
			                                    'conditions' => 'sold_to_user_ik = ?0',
			                                    'bind'       => [$this->auth[ 'ik' ]]
		                                    ]);

		$subscriptionService = new SubscriptionService();
		$this->view->feed = $subscriptionService->getSubscribedEvents('user', $this->auth[ 'ik' ]);
	}

	public function feedAction($type = \false)
	{
		$profile = User::findFirst($this->auth[ 'ik' ]);

		$actualType = \false;
		switch ($type)
		{
			case 'following':
				$actualType = 'created';
				break;
			case 'followers':
				$actualType = 'followed';
				break;
			case 'messages':
				$actualType = 'messaged';
				break;
		}

		$this->view->name = $profile->name;
		$subscriptionService = new SubscriptionService();
		$this->view->feed = $subscriptionService->getSubscribedEvents('user', $this->auth[ 'ik' ], $actualType);
		$this->view->viewing = ($type) ? $type : 'everything';
	}

	protected function __setLogin(User $user, $impersonating = \false)
	{
		// We only set the disqus and login times when not impersonating.
		if (!$impersonating)
		{
			// Authentication success
			$user->login = date('Y-m-d H:i:s');
			$user->save();

			// Set cookie variables
			$this->cookies->set('disqus', base64_encode(json_encode(['id' => $user->ik, 'username' => $user->name, 'email' => $user->email])), time() + 15 * 86400);
		}

		$authArray = [
			'ik'            => $user->ik,
			'email'         => $user->email,
			'name'          => $user->name,
			'user_name'     => $user->user_name,
			'role'          => $user->role,
			'type'          => $user->type,
			'impersonating' => $impersonating
		];

		if ($impersonating)
		{
			$authArray[ 'originalRole' ] = $this->auth[ 'role' ];
		}

		$psService = new \Up\Services\PrestashopIntegrationService();
		$psService->loginToPrestashop($user);

		if (($shopId = $psService->getShopId($user)) !== \false)
		{
			$authArray[ 'shopId' ] = $shopId;
		}

		// Set session variables
		$this->session->set('auth', $authArray);
		$this->auth = $authArray;
		$this->view->auth = $authArray;
	}

	protected function __signIn($email, $password)
	{
		$user = User::findFirst(['email = ?0', 'bind' => [$email]]);

		if ($user && $user !== false)
		{
			if ($user->password == password_hash($password, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => $user->salt]))
			{
				$this->__setLogin($user);

				return \true;
			}
			else
			{
				return 'An account with that email and password was not found.  <a href="/profile/forgot-password">Forgot your password?</a>';
			}
		}
		else
		{
			return 'That email is not registered to any account, please <a href="/profile/register">create a new account.</a>';
		}

		return \false;
	}

	protected function __updateSessionAuth($var, $data)
	{
		$auth = $this->session->get('auth');
		// We only update session variables that existed when logging in.
		if ($auth)
		{
			if (isset($auth[ $var ]))
			{
				$auth[ $var ] = $data;

				$this->session->set('auth', $auth);
			}
		}
	}

	/**
	 * @return mixed|string
	 */
	protected function __getUserNameFromPost()
	{
		$username = '';

		if ($this->request->hasPost('userName'))
		{
			return preg_replace('/[^a-zA-Z0-9]/', '', $this->request->getPost('userName'));
		}

		return $username;
	}

	protected function __userNameExists($username, $myIk = \false)
	{
		$exists = \false;

		if ($myIk)
		{
			$user = User::findFirst(['user_name = ?0 AND ik <> ?1', 'bind' => [$username, $this->auth[ 'ik' ]]]);
		}
		else
		{
			$user = User::findFirst(['user_name = ?0', 'bind' => [$username]]);
		}

		if ($user)
		{
			$exists = \true;
		}

		return $exists;
	}
}