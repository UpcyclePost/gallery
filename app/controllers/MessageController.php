<?php

class MessageController extends ControllerBase
{
	public function inboxAction()
	{
		// Find all messages sent to this user.
		// @TODO: We need to paginate this.
		$messages = Message::find([
			                          'conditions' => 'to_user_ik = ?0',
			                          'order'      => 'ik DESC',
			                          'bind'       => [$this->auth[ 'ik' ]]
		                          ]);

		// Set view variables
		$this->view->messages = $messages;
		$this->view->viewing = 'inbox';
	}

	public function sentAction()
	{
		// Find all messages sent by this user.
		// @TODO: We need to paginate this.
		$messages = Message::find([
			                          'conditions' => 'from_user_ik = ?0',
			                          'order'      => 'ik DESC',
			                          'bind'       => [$this->auth[ 'ik' ]]
		                          ]);

		// Set view variables
		$this->view->messages = $messages;
		$this->view->viewing = 'sent';
	}

	/**
	 * @param int $messageIk
	 *
	 * @return mixed
	 */
	public function viewAction($messageIk)
	{
		// Find the selected message.
		$message = Message::findFirst([
			                              'conditions' => 'ik = ?0 AND (to_user_ik = ?1 OR from_user_ik = ?1)',
			                              'bind'       => [$messageIk, $this->auth[ 'ik' ]]
		                              ]);

		// If the message was not found, redirect back to the inbox.
		if ($message === false)
		{
			$this->flash->error('The message selected was not found.');

			return $this->response->redirect('profile/messages');
		}
		else
		{
			// Mark the message as read if it has not already been.
			if ($message->read == null && $message->to_user_ik == $this->auth[ 'ik' ])
			{
				$message->read = date('Y-m-d H:i:s');
				$message->update();
			}
		}

		// Set view variables
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js');
		$this->view->message = $message;
	}

	public function sendAction($toUserIk)
	{
		// Set the redirect location in case the user is not already logged in.
		$this->session->set('redirectTo', $this->router->getRewriteUri());

		// Make sure the user isn't trying to send a message to themselves.
		if ($toUserIk == $this->auth[ 'ik' ])
		{
			$this->view->disable();

			$this->flash->error('Are you sure you want to send yourself a message?');

			return $this->response->redirect('profile/messages');
		}

		// Look up the recipient.
		$user = User::findFirst($toUserIk);

		if ($user === false)
		{
			// If the recipient was not found, just redirect back to the inbox.
			return $this->response->redirect('profile/messages');
		}

		if ($this->request->isPost())
		{
			$this->view->disable();

			$message = new Message();
			$message->from_user_ik = $this->session->get('auth')[ 'ik' ];
			$message->to_user_ik = $toUserIk;
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
				$this->eventManager->fire('user:whenPrivateMessageHasBeenSent', $this, ['sender' => $this->auth[ 'ik' ], 'recipient' => $toUserIk, 'messageIk' => $message->ik]);

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

		// Set view variables
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
			->addJs('js/gallery/layout.js?v=0.26.3');
		$this->view->profile = $user;
	}
}
