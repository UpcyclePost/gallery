<?php

class ProfileController extends ControllerBase
{
	public function indexAction()
	{

	}

	public function messagesAction()
	{
		$auth = $this->session->get('auth');

		$messages = Message::find([
			                          'conditions' => 'to_user_ik = ?0',
			                          'order'      => 'ik DESC',
			                          'bind'       => [$auth[ 'ik' ]]
		                          ]);

		$this->view->messages = $messages;
	}

	public function messageAction($messageIk)
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js');
		$message = Message::findFirst(['conditions' => 'ik = ?0 AND to_user_ik = ?1', 'bind' => [$messageIk, $this->session->get('auth')[ 'ik' ]]]);

		if ($message === false)
		{
			$this->flash->error('The message selected was not found.');

			return $this->response->redirect('profile/messages');
		}
		else
		{
			if ($message->read == null)
			{
				$message->read = date('Y-m-d H:i:s');
				$message->update();
			}
		}

		$this->view->message = $message;
	}

	public function messageSendAction($userIk)
	{
		$this->session->set('redirectTo', $this->router->getRewriteUri());
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js');

		if ($userIk == $this->session->get('auth')[ 'ik' ])
		{
			$this->view->disable();

			$this->flash->error('Are you sure you want to send yourself a message?');

			return $this->response->redirect('profile/messages');
		}

		$user = User::findFirst($userIk);

		if ($user === false)
		{
			return $this->response->redirect('profile/messages');
		}

		if ($this->request->isPost())
		{
			$this->view->disable();

			$message = new Message();
			$message->from_user_ik = $this->session->get('auth')[ 'ik' ];
			$message->to_user_ik = $userIk;
			$message->subject = strip_tags($this->request->getPost('subject'));
			$message->message = strip_tags($this->request->getPost('message'));
			$message->sent = date('Y-m-d H:i:s');

			if ($this->request->hasPost('reply-to'))
			{
				$message->reply_to_ik = (int)$this->request->getPost('reply-to');
			}

			if ($message->save())
			{
				$this->flash->success('Your message has been sent.');
				$this->eventManager->fire('user:whenPrivateMessageHasBeenSent', $this, ['sender' => $this->auth[ 'ik' ], 'recipient' => $userIk, 'messageIk' => $message->ik]);

				// Notify the user that they have received a new message
				try
				{
					ob_start();
					$this->view->partial('emails/profile/message-received', [
						'who'  => $user->name,
						'from' => $this->auth[ 'user_name' ],
						'slug' => $message->slug(),
					]);
					$body = ob_get_contents();
					ob_end_clean();

					//Send
					Helpers::sendEmail($user->email, sprintf('%s sent you a message on UpcyclePost', $this->auth[ 'user_name' ]), $body, 'UpcyclePost', 'noreply@upcyclepost.com');
				}
				catch (Exception $e)
				{
				}
			}
			else
			{
				$this->flash->error('An error occurred while sending your message.');
			}

			return $this->response->redirect('profile/messages');
		}

		$this->assets->addJs('js/gallery/layout.js');
		$this->view->profile = $user;
	}

	public function editAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/profile/edit.js')
		             ->addJs('js/libraries/jquery/jquery.blockUI.js');

		if (($profile = $this->__getProfile()) !== \false)
		{
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
					$profile->location = strip_tags($this->request->getPost('location'));
				}
				if ($this->request->has('about'))
				{
					$profile->about = strip_tags($this->request->getPost('about'));
				}
				if ($this->request->has('etsy'))
				{
					$profile->etsy = strip_tags($this->request->getPost('etsy'));
				}
				if ($this->request->has('twitter'))
				{
					$profile->twitter = strip_tags($this->request->getPost('twitter'));
				}
				if ($this->request->has('facebook'))
				{
					$profile->facebook = strip_tags($this->request->getPost('facebook'));
				}

				if ($this->request->hasPost('marketplace'))
				{
					$profile->contact_for_marketplace = 1;
				}
				else
				{
					$profile->contact_for_marketplace = 0;
				}

				$profile->update();

				if ($this->request->hasFiles())
				{
					// Let's upload a background image.
					foreach ($this->request->getUploadedFiles() AS $file)
					{
						if ($file->getType() == 'image/gif' || $file->getType() == 'image/png' || $file->getType() == 'image/jpeg')
						{
							$fileName = sprintf('%s-%s-%s%s', $profile->ik, Helpers::createShortCode($profile->ik), time(), strrchr($file->getName(), '.'));
							//profileImageDir
							$permanentFile = $this->config->application->profileImageDir . $fileName;
							if ($profile->custom_background)
							{
								unlink(sprintf('%s%s', $this->config->application->profileImageDir, $profile->custom_background));
							}

							if ($file->moveTo($permanentFile))
							{
								$profile->custom_background = $fileName;
							}

							// Let's plan to have more than one background image in the future - perhaps rotating?
							break;
						}
					}
				}

				if ($profile->update())
				{
					$this->flash->success('Your profile has been updated.');

					if ($this->auth[ 'name' ] != $profile->name)
					{
						$this->auth[ 'name' ] = $profile->name;
						$this->session->set('auth', $this->auth);
					}

					return $this->response->redirect('http://www.upcyclepost.com/profile/view/' . $this->auth[ 'ik' ], true);
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

	public function viewAction($userId)
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

		$this->view->title = $user->user_name . ' | UpcyclePost';

		$this->view->profile = $user;

		if ($user->custom_background)
		{
			$this->view->og_img = 'http://i.upcyclepost.com/profile/' . $user->custom_background;
			list($width, $height, $type, $attr) = @getimagesize($this->config->application->profileImageDir . $user->custom_background);
			$this->view->og_img_size = [$width, $height];
			$this->view->og_description = $user->about;
		}

		$this->view->results = Post::searchIndex(0, 150, false, $user->ik);

		$subscriptionService = new SubscriptionService();
		$this->view->following = $subscriptionService->userIsSubscribed(
			'user',
			$this->auth[ 'ik' ],
			$user->ik
		);
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
					$path = substr($redirect, 1);
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

	protected function __signIn($email, $password)
	{
		$user = User::findFirst(['email = ?0', 'bind' => [$email]]);

		if ($user && $user !== false)
		{
			if ($user->password == password_hash($password, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => $user->salt]))
			{
				// Authentication success
				$user->login = date('Y-m-d H:i:s');
				$user->save();

				// Set cookie variables
				$this->cookies->set('disqus', base64_encode(json_encode(['id' => $user->ik, 'username' => $user->name, 'email' => $user->email])), time() + 15 * 86400);

				// Set session variables
				$this->session->set('auth', ['ik' => $user->ik, 'email' => $user->email, 'name' => $user->name, 'user_name' => $user->user_name, 'role' => $user->role, 'type' => $user->type]);

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
			if (isset($auth[$var]))
			{
				$auth[$var] = $data;

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
			return preg_replace('/[^a-zA-Z0-9\- ]/', '', $this->request->getPost('userName'));
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