<?php

class ShopController extends ControllerBase
{
	public function initialize()
	{
		parent::initialize();
		$this->view->title = 'Market | UpcyclePost';

		if (!$this->auth)
		{
			return $this->response->redirect('');
		}
	}

	/**
	 * The homepage of the dashboard
	 * Links to Listings, Settings, Balance
	 */
	public function dashboardAction()
	{
		if (($profile = $this->__getShopProfile()) instanceof User)
		{
			$this->__redirectIfNoShop($profile);
			$this->__redirectIfTermsRequired($profile);
			$this->__notifyUserIfNoPaymentMethods($profile);

			// Set items for the view
			$this->view->shop_name = $profile->Shop->name;
			$this->view->shop_url = $profile->Shop->url;
			$this->view->next_transfer_date = date('F j, Y', strtotime('next monday'));
			$this->view->last_payment_sent = $profile->Shop->last_transferred_at ? date('Y-m-d', strtotime($profile->Shop->last_transferred_at)) : 'Never';
			$this->view->balance = $profile->Shop->balance;
			$this->view->revenue = $profile->Shop->total_revenue;
			$this->view->items_sold = Sale::count([
				                                      'conditions' => 'sold_by_shop_ik = ?0',
				                                      'bind'       => [$profile->Shop->ik]
			                                      ]);
			$this->view->items_listed = Market::count([
				                                          'conditions' => 'shop_ik = ?0 AND status = ?1 AND deleted = ?2',
				                                          'bind'       => [$profile->Shop->ik, 'available', 0]
			                                          ]);
			$this->view->total_items_listed = Market::count([
				                                                'conditions' => 'shop_ik = ?0',
				                                                'bind'       => [$profile->Shop->ik]
			                                                ]);

			// We don't want to show deleted listings
			// but Phalcon cannot filter relationships.
			$listings = [];
			foreach ($profile->Shop->Market AS $marketModel)
			{
				if ($marketModel->deleted != 1 && $marketModel->status == 'available')
				{
					$listings[] = $marketModel;
				}
			}

			$this->view->listings = $listings;

			$this->view->to_ship = Sale::find([
				                                  'conditions' => 'sold_by_shop_ik = ?0 AND shipped = ?1',
				                                  'bind'       => [$profile->Shop->ik, 0]
			                                  ]);

			$this->view->can_list_items = $profile->Shop->canListItem();
		}
		else
		{
			// We didn't find a profile, this user should probably login.
			return $this->response->redirect('profile/login');
		}
	}

	/**
	 * The landing page for opening a shop.
	 * This is the result of clicking "Become a Seller"
	 */
	public function openAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('js/shop/open.js');

		if (($profile = $this->__getProfile()) instanceof User)
		{
			if ($profile->Shop)
			{
				// A shop already exists for this user
				return $this->response->redirect('shop/settings');
			}

			if ($this->request->isPost())
			{
				$shopName = strip_tags(trim($this->request->getPost('shopName')));
				$shopUrl = strtolower($this->request->getPost('shopUrl'));
				$validShopUrl = preg_match('/^[A-Za-z][a-z0-9\-]+$/i', $shopUrl);
				$shop = Shop::findFirst(['url = ?0', 'bind' => [$shopUrl]]);
				if (strlen($shopName) < 3)
				{
					$this->flash->error('Your shop name is too short.');

					return $this->response->redirect('shop/open');
				}
				else if (strlen($shopUrl) < 5)
				{
					$this->flash->error(sprintf('Your shop address (%s) is too short.', $shopUrl));

					return $this->response->redirect('shop/open');
				}
				else if ($validShopUrl != 1)
				{
					$this->flash->error(sprintf('Your shop address (%s) is invalid.', $shopUrl));

					return $this->response->redirect('shop/open');
				}
				else if ($shop)
				{
					$this->flash->error(sprintf('Your shop address (%s) is already taken.', $shopUrl));

					return $this->response->redirect('shop/open');
				}
				else
				{
					$shop = new Shop();
					$shop->user_ik = $profile->ik;
					$shop->name = $shopName;
					$shop->url = $shopUrl;
					$shop->created_at = date('Y-m-d H:i:s');
					$shop->updated_at = date('Y-m-d H:i:s');
					$shop->balance = 0;
					$shop->total_revenue = 0;
					$shop->preferred_currency = 'US Dollar';
					$shop->ships_to = 'United States';
					$shop->preferred_language = 'English';

					if (!$shop->save())
					{
						$this->flash->error('An error occurred while creating your shop.');
					}
					else
					{
						return $this->response->redirect('shop/terms');
					}
				}
			}
		}
		else
		{
			return $this->response->redirect('profile/login');
		}
	}

	public function termsAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js');

		if (($profile = $this->__getProfile()) instanceof User)
		{
			// Redirect the user to open a shop if they don't yet have a shop
			$this->__redirectIfNoShop($profile);

			if ($this->request->isPost())
			{
				// This is a pretty ugly workaround for relationship saving.  Despite setting this relationship
				// to cascade updates, they are not sent to the Shop model.
				$shop = Shop::findFirst($profile->Shop->ik);
				$shop->terms_agreed_at = date('Y-m-d H:i:s');
				$shop->updated_at = date('Y-m-d H:i:s');
				if (!$shop->save())
				{
					$this->flash->error('An error occurred, please try again.');
				}
				else
				{
					return $this->response->redirect('shop/settings');
				}
			}
		}
		else
		{
			return $this->response->redirect('profile/login');
		}
	}

	/**
	 * Shop settings, including all the information for the shop
	 * Each shop is a "customer" in Stripe, with a card and bank token.
	 */
	public function settingsAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js');

		if (($profile = $this->__getProfile()) instanceof User)
		{
			$this->__redirectIfNoShop($profile);
			$this->__redirectIfTermsRequired($profile);

			if ($this->request->isPost())
			{
				$errors = [];
				// This is a pretty ugly workaround for relationship saving.  Despite setting this relationship
				// to cascade updates, they are not sent to the Shop model.
				$shop = Shop::findFirst($profile->Shop->ik);

				// Save new Shop settings
				$shop->address = trim(strip_tags($this->request->getPost('address')));
				$shop->city = trim(strip_tags($this->request->getPost('city')));
				$shop->st = trim(strip_tags($this->request->getPost('st')));
				if (!is_numeric($this->request->getPost('zip')) || strlen($this->request->getPost('zip')) != 5)
				{
					$errors[] = 'You must enter a valid Zip.';
				}
				else
				{
					$shop->zip = $this->request->getPost('zip');
				}
				$shop->country = 'United States';
				$shop->preferred_language = 'English';
				$shop->preferred_currency = 'US Dollar';
				$shop->first_name = trim(strip_tags($this->request->getPost('firstName')));
				$shop->last_name = trim(strip_tags($this->request->getPost('lastName')));
				$shop->phone_number = substr(preg_replace("/[^0-9]+/", '', $this->request->getPost('phoneNumber')), 0, 10);
				$shop->last4 = trim(strip_tags($this->request->getPost('last4')));

				$shop->updated_at = date('Y-m-d H:i:s');

				// Let's activate the shop.
				if ($shop->is_active != 1)
				{
					// Set the user type to seller to enable some seller-specific features.
					$profile->type = 'seller';
					$profile->save();

					$shop->activated_at = date('Y-m-d H:i:s');
					$shop->is_active = 1;
				}

				if (count($errors) > 0)
				{
					$this->flash->error(sprintf('Please correct the following errors:<br /><br />%s', implode('<br />', $errors)));
				}
				else if (!$shop->save())
				{
					$this->flash->error('An error occurred, please try again.');
				}
				else
				{
					if (!$shop->survey)
					{
						// Make them complete the survey.
						return $this->response->redirect('shop/survey');
					}
					else
					{
						return $this->response->redirect('shop/dashboard');
					}
				}
			}

			$this->view->shop = $profile->Shop;
		}
		else
		{
			return $this->response->redirect('profile/login');
		}
	}

	/**
	 * This survey is part of the registration process.
	 */
	public function surveyAction()
	{
		if (($profile = $this->__getProfile()) instanceof User)
		{
			$this->__redirectIfNoShop($profile);
			$this->__redirectIfTermsRequired($profile);

			if ($profile->Shop->Survey)
			{
				// The survey has already been completed.
				return $this->response->redirect('shop/dashboard');
			}
			else
			{
				if ($this->request->ispost())
				{
					$survey = new Survey();
					$survey->shop_ik = $profile->Shop->ik;
					$survey->answer = $this->request->getPost('survey-q-1');

					if (!$survey->save())
					{
						$this->flash->error('An error occurred while saving your answer, please try again.');
					}
					else
					{
						return $this->response->redirect('shop/get-paid');
					}
				}
			}
		}
		else
		{
			return $this->response->redirect('profile/login');
		}
	}

	public function getPaidAction()
	{
		$this->assets->addJs('js/libraries/validate/jquery.validate.min.js')
		             ->addJs('https://js.stripe.com/v2/')
		             ->addJs('js/shop/getPaid.js');

		if (($profile = $this->__getProfile()) instanceof User)
		{
			$this->__redirectIfNoShop($profile);
			$this->__redirectIfTermsRequired($profile);

			if ($this->request->isPost())
			{
				if (!$this->request->getPost('stripeToken'))
				{
					// No token
					$this->flash->error('An error occurred when saving your payment details [Reference=01].');

					return $this->response->redirect('shop/get-paid');
				}
				$shop = Shop::findFirst($profile->Shop->ik);

				$stripeKey = Phalcon\DI::getDefault()->get('stripe')->get('private_key');
				Stripe::setApiKey($stripeKey);

				if (!$this->Shop->customer_token)
				{
					// Create the customer
					try
					{
						$customer = Stripe_Customer::create([
							                                    'description' => sprintf('Shop %s for User %s', $profile->Shop->name, $profile->email),
							                                    'card'        => $this->request->getPost('stripeToken')
						                                    ]);

						$shop->customer_token = $customer->id;
						$shop->save();
					}
					catch (Exception $e)
					{
						$this->flash->error('Something went wrong when saving your payment details [Reference=02].');

						return $this->response->redirect('shop/get-paid');
					}
				}
				else
				{
					// Update the customer
					try
					{
						$customer = Stripe_Customer::retrieve($profile->Shop->customer_token);
						$customer->card = $this->request->getPost('stripeToken');
						$customer->save();
					}
					catch (Exception $e)
					{
						$this->flash->error('Something went wrong when saving your payment details [Reference=03].');

						return $this->response->redirect('shop/get-paid');
					}
				}

				// Create Bank Account Token
				try
				{
					$bank_token = Stripe_Token::create([
						                     'bank_account'  => [
							                     'country'   => 'US',
							                     'routing_number'    => $this->request->getPost('bankRoutingNumber'),
							                     'account_number'    => $this->request->getPost('bankAccountNumber')
						                     ]
					                     ]);
				}
				catch (Exception $e)
				{
					$this->flash->error('Something went wrong when saving your payment details [Reference=BANK01].');

					return $this->response->redirect('shop/get-paid');
				}

				if (!$this->Shop->recipient_token)
				{
					try
					{
						$recipient = Stripe_Recipient::create([
							                                      'name'         => $this->request->getPost('bankAccountLegalName'),
							                                      'type'         => 'individual',
							                                      'bank_account' => $bank_token->id,
							                                      'description'  => sprintf('Shop %s for User %s', $profile->Shop->name, $profile->email),
							                                      'metadata'     => [
								                                      'ik'      => $profile->ik,
								                                      'shop_ik' => $profile->Shop->ik
							                                      ]
						                                      ]);
					}
					catch (Exception $e)
					{
						$this->flash->error('Something went wrong when saving your payment details [Reference=BANK02].');

						return $this->response->redirect('shop/get-paid');
					}
				}
				else
				{
					$recipient = Stripe_Recipient::retrieve($profile->Shop->recipient_token);
					$recipient->bank_account = $bank_token->id;
					$recipient->save();
				}

				// Save customer information
				try
				{
					$shop->recipient_legal_name = $this->request->getPost('bankAccountLegalName');
					$shop->recipient_token = $recipient->id;
					$shop->bank_token = $bank_token->id;
					$shop->customer_token = $customer->id;
					$shop->card_token = $this->request->getPost('stripeToken');

					$shop->updated_at = date('Y-m-d H:i:s');
					if (!$shop->save())
					{
						$this->flash->error('An error occurred, please try again. [Reference=04].');

						return $this->response->redirect('shop/get-paid');
					}
					else
					{
						return $this->response->redirect('shop/dashboard');
					}
				}
				catch (Exception $e)
				{
					$this->flash->error('Something went wrong when saving your payment details [Reference=05].');

					return $this->response->redirect('shop/get-paid');
				}
			}
		}
		else
		{
			return $this->response->redirect('profile/login');
		}
	}

	public function viewAction($url)
	{
		$shop = Shop::findFirst([
			                        'conditions' => 'url = ?0',
			                        'bind'       => [$url]
		                        ]);

		if (!$shop)
		{
			// This shop was not found
			$this->flash->error('The shop was not found, enjoy the gallery.');

			return $this->response->redirect('gallery');
		}

		$this->view->shop_user_ik = $shop->User->ik;
		$this->view->shop_name = $shop->name;
		$this->view->shop_url = $shop->url;
		$this->view->shop_location = $shop->city . ', ' . $shop->st;

		// We only want to show available listings
		// but Phalcon cannot filter relationships.
		$listings = [];
		foreach ($shop->Market AS $marketModel)
		{
			if ($marketModel->deleted != 1 && $marketModel->status == 'available')
			{
				// We only need the post information for each of these listings
				$listings[] = [
					'url'           => $marketModel->Post->url,
					'thumbnail'     => $marketModel->Post->thumbnail(),
					'title'         => $marketModel->Post->title,
					'categoryTitle' => $marketModel->Post->Category->title,
					'ik'            => $marketModel->Post->ik,
					'id'            => $marketModel->Post->id,
					'user'          => $marketModel->Post->User->ik,
					'userName'      => $marketModel->Post->User->name,
					'views'         => $marketModel->Post->views,
					'likes'         => $marketModel->Post->likes
				];
			}
		}

		$this->view->results = $listings;
	}

	public function shipAction($listingIk)
	{
		$listingIk = intval($listingIk);

		$shopModel = Shop::findFirst([
			                             'conditions' => 'user_ik = ?0',
			                             'bind'       => [$this->auth['ik']]
		                             ]);

		if (!$shopModel)
		{
			$this->flash->error('The listing you selected to ship was not found.');

			return $this->response->redirect('shop/dashboard');
		}

		$saleModel = Sale::findFirst([
			                             'conditions' => 'post_ik = ?0 AND sold_by_shop_ik = ?1 AND shipped = ?2',
			                             'bind'       => [$listingIk, $shopModel->ik, 0]
		                             ]);
		if (!$saleModel)
		{
			$this->flash->error('This listing has already been shipped.');

			return $this->response->redirect('shop/dashboard');
		}
		else
		{
			$saleModel->shipped = 1;
			$saleModel->shipped_at = date('Y-m-d H:i:s');
			if ($saleModel->save())
			{
				// Send shipping confirmation
				// If this is unsuccessful, it can be resent at any time.
				//@TODO: This should be moved to an event for cleaner code.
				try
				{
					ob_start();
					$this->view->partial('emails/buy/order-confirmation', [
						'who'            => $saleModel->User->name,
						'post_thumbnail' => $saleModel->Post->thumbnail('small'),
						'post_url'       => $saleModel->Post->url(),
						'post_title'     => $saleModel->Post->title,
						'name'           => $saleModel->ship_name,
						'address_line_1' => strtoupper($saleModel->ship_address),
						'address_line_2' => strtoupper(sprintf('%s, %s %s', $saleModel->ship_city, $saleModel->ship_st, $saleModel->ship_zip)),
						'total_price'    => $saleModel->total_amount
					]);
					$body = ob_get_contents();
					ob_end_clean();

					//Send
					Helpers::sendEmail($saleModel->user->email, sprintf('Your UpcyclePost order of %s has shipped!', $saleModel->Post->title), $body);
				}
				catch (Exception $e)
				{
				}

				$this->flash->success('The listing has been marked as shipped, and the buyer notified.');
			}
			else
			{
				$this->flash->error('An error occurred while marking the item as shipped.');
			}
		}

		return $this->response->redirect('shop/dashboard');
	}

	/**
	 * Redirects user to open a shop if they have no shop.
	 *
	 * @param User $profile
	 *
	 * @return mixed
	 */
	protected function __redirectIfNoShop(User &$profile)
	{
		if (!$profile->Shop)
		{
			$this->flash->error('Please open a shop to continue.');

			return $this->response->redirect('shop/open');
		}
	}

	/**
	 * Redirects user to agree to terms of service if they have not.
	 *
	 * @param User $profile
	 *
	 * @return mixed
	 */
	protected function __redirectIfTermsRequired(User &$profile)
	{
		if (!$profile->Shop->terms_agreed_at)
		{
			$this->flash->error('You must agree to the terms of service.');

			return $this->response->redirect('shop/terms');
		}
	}

	/**
	 * Notifies a user that they have no payment methods.
	 *
	 * @param User $profile
	 *
	 * @return mixed
	 */
	protected function __notifyUserIfNoPaymentMethods(User &$profile)
	{
		if (!$profile->Shop->bank_token && !$profile->Shop->card_token)
		{
			$this->flash->warning('Please add payment information through Get Paid to list an item.');
		}
	}
}