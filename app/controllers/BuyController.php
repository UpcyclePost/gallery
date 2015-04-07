<?php

class BuyController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->view->title = 'Market | UpcyclePost';
	}

	public function indexAction($itemIk)
	{
		$this->session->set('redirectTo', $this->router->getRewriteUri());

		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('https://js.stripe.com/v2/')
		             ->addJs('js/buy/index.js');

		// Make sure the user is logged in
		$auth = $this->session->get('auth');

		// Make sure the item is actually for sale ( and not already sold )
		$post = Post::findFirst($itemIk);
		if (!$post->Market)
		{
			$this->view->disable();
			$this->flash->error('The selected item is not listed for sale.');

			return $this->response->redirect('gallery');
		}

		if ($post->Market->status != 'available')
		{
			$this->view->disable();

			$this->flash->error('The selected item is no longer available.') .

			$redirect = 'gallery';

			return $this->response->redirect($redirect);
		}

		$this->view->post = $post;
		$this->view->thumbnail = $post->thumbnail('small');

		$this->view->user = User::findFirst($auth['ik']);
		$this->view->itemHash = hash_hmac('ripemd160', sprintf('%s%s%s', $itemIk, $post->Market->price, $post->User->ik), $post->User->salt);

		// ???

		// Profit
	}

	public function processAction()
	{
		if ($this->request->isPost())
		{
			$itemIk = $this->dispatcher->getParam('postIk');

			// Submit payment if there is a valid token
			if ($this->request->hasPost('stripeToken') && $this->request->hasPost('token'))
			{
				$post = Post::findFirst($itemIk);

				if ($post->user_ik == $this->auth['ik'])
				{
					$this->flash->error('You attempted to purchase your own item, perhaps you should keep it.');

					return $this->response->redirect('shop/dashboard');
				}

				$stripeToken = $this->request->getPost('stripeToken');
				$token = $this->request->getPost('token');
				$tokenCompare = hash_hmac('ripemd160', sprintf('%s%s%s', $itemIk, $post->Market->price, $post->User->ik), $post->User->salt);

				// Verify token
				if (strlen($stripeToken) > 0 && $token == $tokenCompare)
				{
					// Token verified, grab the related models to verify the item is still listed.
					/**
					 * @var Market
					 */
					$marketModel = Market::findFirst([
						                                 'conditions' => 'post_ik = ?0 AND status = ?1 AND deleted = ?2',
						                                 'bind'       => [$itemIk, 'available', 0]
					                                 ]);

					if (!$marketModel)
					{
						$this->flash->error('This item is no longer available.');

						return $this->response->redirect('market/buy/' . $itemIk);
					}
					else
					{
						$creditCardModel = new CreditCard();
						$creditCardModel->post_ik = $itemIk;
						$creditCardModel->user_ik = $this->auth['ik'];
						$creditCardModel->success = 0;
						$creditCardModel->amount = $post->Market->price + $post->Market->shipping_price;
						$creditCardModel->save();

						// Models found, submit payment
						Stripe::setApiKey(Phalcon\DI::getDefault()->get('stripe')->get('private_key'));
						$result = \false;
						try
						{
							$result = Stripe_Charge::create([
								                                'amount'      => ($post->Market->price + $post->Market->shipping_price) * 100,
								                                'currency'    => 'USD',
								                                'card'        => $stripeToken,
								                                'description' => 'UP ' . $post->title
							                                ]);
						}
						catch (Stripe_CardError $e)
						{
							$creditCardModel->response = $e->getJsonBody();
							$creditCardModel->save();

							// Payment or card declined
							$body = $e->getJsonBody();
							$err = $body['error'];

							$this->flash->error($err['message']);

							return $this->response->redirect('market/buy/' . $itemIk);
						}
						catch (Exception $e)
						{
							var_dump($e);
							die($e->getMessage());
						}

						if (!$result)
						{
							$this->flash->error('An error occurred while processing your payment [01].  Your card has not been charged.  Please try again.');

							return $this->response->redirect('market/buy/' . $itemIk);
						}
						else
						{
							$creditCardModel->response = json_encode($result);
							$creditCardModel->save();
							// The charge request was successful, verify it was paid and save the payment
							if ($result->paid)
							{
								// Mark the item as sold
								$marketModel->sold_to_user_ik = $this->auth['ik'];
								$marketModel->status = 'sold';
								$marketModel->sold_at = date('Y-m-d H:i:s');
								$marketModel->updated_at = date('Y-m-d H:i:s');
								$marketModel->save();

								$creditCardModel->success = $result->paid;
								$creditCardModel->stripe_id = $result->id;
								$creditCardModel->processed_at = date('Y-m-d H:i:s');
								$creditCardModel->cvc_result = $result->card->cvc_check;
								$creditCardModel->address_result = $result->card->address_line_1_check;
								$creditCardModel->zip_result = $result->card->address_zip_check;
								$creditCardModel->card_token = $result->card->id;
								$creditCardModel->amount = ($result->amount / 100);

								$creditCardModel->save();

								// Create a new Payment
								$paymentModel = new Payment();
								$paymentModel->post_ik = $itemIk;
								$paymentModel->shop_ik = $post->User->Shop->ik;
								$paymentModel->from_user_ik = $this->auth['ik'];
								$paymentModel->to_user_ik = $post->User->Shop->user_ik;
								$paymentModel->amount = $post->Market->price + $post->Market->shipping_price;
								$paymentModel->authorized_amount = ($result->amount / 100);
								$paymentModel->applied_at = date('Y-m-d H:i:s');
								$paymentModel->stripe_id = $result->id;

								// Create a new Sale
								$saleModel = new Sale();
								$saleModel->sold_by_shop_ik = $post->User->Shop->ik;
								$saleModel->sold_to_user_ik = $this->auth['ik'];
								$saleModel->amount = $post->Market->price;
								$saleModel->ship_amount = $post->Market->shipping_price;
								$saleModel->total_amount = $post->Market->price + $post->Market->shipping_price;
								$saleModel->transaction_fee = $saleModel->calculateFee();
								$saleModel->listing_fee = 0.25;

								$saleModel->ship_name = $this->request->getPost('name');
								$saleModel->ship_address = $this->request->getPost('shippingAddress');
								$saleModel->ship_city = $this->request->getPost('shippingCity');
								$saleModel->ship_st = $this->request->getPost('shippingState');
								$saleModel->ship_zip = $this->request->getPost('shippingZip');

								$saleModel->shipped = 0;
								$saleModel->sold_at = date('Y-m-d H:i:s');
								$saleModel->post_ik = $itemIk;

								$saleModel->save();

								$shopModel = Shop::findFirst($post->User->Shop->ik);
								$shopModel->balance = $shopModel->balance + $saleModel->getBalanceOwed();
								$shopModel->total_revenue = $shopModel->total_revenue + $saleModel->getBalanceOwed();

								$shopModel->save();

								$paymentModel->save();
							}
							else
							{
								$this->flash->error('An error occurred while processing your payment.  Your card has not been charged.  Please try again.');

								return $this->response->redirect('markey/buy/' . $itemIk);
							}
						}

						// Success!

						// Send order confirmation
						// If this is unsuccessful, let the order continue processing - we can resend this at any time.
						try
						{
							ob_start();
							$this->view->partial('emails/buy/order-confirmation', [
								'who'            => $this->auth['name'],
								'post_thumbnail' => $post->thumbnail('small'),
								'post_url'       => $post->url(),
								'post_title'     => $post->title,
								'name'           => $this->request->getPost('name'),
								'address_line_1' => strtoupper($this->request->getPost('shippingAddress')),
								'address_line_2' => strtoupper(sprintf('%s, %s %s', $this->request->getPost('shippingCity'), $this->request->getPost('shippingState'), $this->request->getPost('shippingZip'))),
								'total_price'    => $post->Market->price + $post->Market->shipping_price
							]);
							$body = ob_get_contents();
							ob_end_clean();

							//Send
							Helpers::sendEmail($this->auth['email'], sprintf('Your UpcyclePost order of %s', $post->title), $body);
						}
						catch (Exception $e)
						{
						}

						return $this->response->redirect('market/buy/' . $itemIk . '/success');
					}
				}
				else
				{
					$this->flash->error('Please try your payment again.');

					return $this->response->redirect('markey/buy/' . $itemIk);
				}
			}
			else
			{
				$this->flash->error('Please try your payment again.');

				return $this->response->redirect('markey/buy/' . $itemIk);
			}
		}
		else
		{
			return $this->response->redirect('');
		}
	}

	public function successAction()
	{
		if (!$this->request->isPost())
		{
			$itemIk = $this->dispatcher->getParam('postIk');

			// Look up the sale.

			$saleModel = Sale::findFirst([
				                             'conditions' => 'sold_to_user_ik = ?0 AND post_ik = ?1',
				                             'bind'       => [$this->auth['ik'], $itemIk]
			                             ]);

			if (!$saleModel)
			{
				return $this->response->redirect('');
			}
			else
			{
				$this->view->amount = $saleModel->total_amount;
				$this->view->shopName = $saleModel->Shop->name;
			}
		}
	}
}