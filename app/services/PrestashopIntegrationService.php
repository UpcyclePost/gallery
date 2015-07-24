<?php namespace Up\Services;

class PrestashopIntegrationService
{
	protected $__service;
	protected $__config;
	protected $__shopConnection;
	protected $__customer;

	public function __construct()
	{
		$this->__config = \Phalcon\DI::getDefault()->get('config');
		$this->__shopConnection = \Phalcon\DI::getDefault()->get('prestashopDb');
		$this->__service = new \PrestaShopWebservice($this->__config->prestashop->get('baseUrl'), 'QPB2Y4CGYQWYCEA1WXQ13W6M865NKPEU', \false);
	}

	public function isPrestashopAvailable()
	{
		$result = $this->__shopConnection->query('SELECT `value` FROM upshop.up_configuration WHERE name = ?', ['PS_CATALOG_MODE']);

		while ($r = $result->fetchArray())
		{
			return ($r['value'] == '1') ? \false : \true;
		}

		return \false;
	}

	public function getCMSBlock($id)
	{
		$cmsResult = $this->__shopConnection->query('SELECT meta_title AS title, content FROM upshop.up_cms_lang WHERE id_cms = ? AND id_lang = 1 AND id_shop = 1 LIMIT 1', [$id]);

		while ($r = $cmsResult->fetchArray())
		{
			return $r;
		}

		return ['title' => '', 'content' => ''];
	}

	public function findShops()
	{
		$result = [];

		$shopsResult = $this->__shopConnection->query('SELECT shop_name, email FROM upshop.up_marketplace_shop u INNER JOIN upshop.up_customer c ON c.id_customer = u.id_customer WHERE u.is_active = 1 AND (SELECT count(distinct product.id_product) FROM upshop.up_marketplace_shop shop INNER JOIN upshop.up_customer ps_customer on ps_customer.id_customer = shop.id_customer INNER JOIN upshop.up_marketplace_shop_product product ON product.id_shop = shop.id INNER JOIN upshop.up_marketplace_seller_product seller_product ON seller_product.id = product.marketplace_seller_id_product INNER JOIN upshop.up_product_shop product_shop ON product_shop.id_product = product.id_product WHERE ps_customer.email = c.email) >= 3 ORDER BY rand()');
		while ($r = $shopsResult->fetchArray())
		{
			if (($user = \User::findFirst(['email = ?0', 'bind' => [$r[ 'email' ]]])))
			{
				$shopThumbnail = \false;
				$views = 0;
				if ($user->Shop)
				{
					if ($user->Shop->background)
					{
						$shopThumbnail = $user->Shop->backgroundThumbUrl();
					}
					else
					{
						$shopThumbnail = $user->backgroundThumbUrl();
					}
					$views = $user->Shop->views;
				}
				else
				{
					$shopThumbnail = $user->backgroundThumbUrl();
				}

				$result[ ] = [
					'url'       => $user->shopUrl(),
					'thumbnail' => $shopThumbnail,
					'title'     => $r['shop_name'],
					'user'      => $user->ik,
					'userName'  => $r['shop_name'],
				    'views'     => $views
				];
			}
		}

		return $result;
	}

	public function getTotalOrdersWaitingToShip(\User $user)
	{
		$ordersResult = $this->__shopConnection->query('SELECT count(*) AS total from `up_marketplace_shop_product` msp
			join `up_order_detail` ordd on (ordd.`product_id`=msp.`id_product`)
			join `up_orders` ord on (ordd.`id_order`=ord.`id_order`)
			join `up_marketplace_seller_product` msep on (msep.`id` = msp.`marketplace_seller_id_product`)
			join `up_marketplace_customer` mkc on (mkc.`marketplace_seller_id` = msep.`id_seller`)
			join `up_customer` cus on (mkc.`id_customer`=cus.`id_customer`)
			join `up_order_state_lang` ords on (ord.`current_state`=ords.`id_order_state`)
			where ords.id_lang=1 and cus.`email`= ? and ord.current_state in (2)', [$user->email]);

		$totalWaiting = 0;

		while ($r = $ordersResult->fetchArray())
		{
			$totalWaiting = $r['total'];
		}

		return $totalWaiting;
	}

	public function getShopNameByEmail($email)
	{
		$shopsResult = $this->__shopConnection->query('SELECT shop_name FROM upshop.up_marketplace_shop u INNER JOIN upshop.up_customer c ON c.id_customer = u.id_customer WHERE email = ?', [$email]);
		while ($r = $shopsResult->fetchArray())
		{
			return $r['shop_name'];
		}

		return \false;
	}

	public function getShopByEmail($email)
	{
		$shopsResult = $this->__shopConnection->query('SELECT id AS shop_id, shop_name, link_rewrite AS shop_link_rewrite, c.id_customer AS shop_ps_id, about_us AS shop_about FROM upshop.up_marketplace_shop u INNER JOIN upshop.up_customer c ON c.id_customer = u.id_customer WHERE email = ?', [$email]);
		while ($r = $shopsResult->fetchArray())
		{
			return $r;
		}

		return \false;
	}

	public function findCategories()
	{
		$result = [];

		if (xcache_isset('prestashop_categories'))
		{
			$result = xcache_get('prestashop_categories');
		}
		else
		{
			$response = $this->__service->get([
				                                  'resource' => 'categories',
				                                  'display'  => '[id,name,link_rewrite]'
			                                  ]);

			foreach ($response->categories->category AS $category)
			{
				$result[ (string)$category->id ] = [
					'id'   => (string)$category->id,
					'name' => (string)$category->name->language,
					'url'  => (string)$category->link_rewrite->language
				];
			}

			xcache_set('prestashop_categories', $result, 3600);
		}

		return $result;
	}

	public function findProductsByIds(Array $ids)
	{
		$result = [];
		$users = [];

		$categories = $this->findCategories();

		$sql = sprintf("SELECT product.id_product, ps_customer.id_customer AS id_user, product_name, ps_customer.email, category.id_category, product_shop.price, lang.link_rewrite, shop_name,
				if(lang.meta_description = '', if(lang.description = '', lang.description_short, lang.description), lang.meta_description) AS description,
				ifnull((SELECT sum(counter) FROM upshop.up_page page INNER JOIN upshop.up_page_viewed viewed ON viewed.id_page = page.id_page WHERE id_page_type = 3 AND id_object = product.id_product), 0) AS counter,
		        ifnull((SELECT group_concat(DISTINCT tag.name) AS tags FROM upshop.up_product_tag product_tag INNER JOIN upshop.up_tag tag ON tag.id_tag = product_tag.id_tag WHERE product_tag.id_product = product.id_product), '') AS tags,
		        (SELECT id_image FROM upshop.up_image image WHERE image.id_product = product.id_product ORDER BY cover DESC LIMIT 1) AS id_image,
		        seller_product.date_add AS posted
				  FROM upshop.up_marketplace_shop shop
				  INNER JOIN upshop.up_customer ps_customer on ps_customer.id_customer = shop.id_customer
				  INNER JOIN upshop.up_marketplace_shop_product product ON product.id_shop = shop.id
				  INNER JOIN upshop.up_marketplace_seller_product seller_product ON seller_product.id = product.marketplace_seller_id_product
				  INNER JOIN upshop.up_product_shop product_shop ON product_shop.id_product = product.id_product
				  LEFT OUTER JOIN upshop.up_category_product category on category.id_product = product.id_product and category.id_category <> 2
				  INNER JOIN upshop.up_product_lang lang on lang.id_product = product.id_product
				  WHERE seller_product.quantity > 0 AND product.id_product IN(%s) GROUP BY product.id ORDER BY product.id DESC", implode(',', $ids));

		$productsResult = $this->__shopConnection->query($sql);

		$this->getProductsFromResult($productsResult, $result, $users, $categories);

		return $result;
	}

	public function findFrontPageProducts()
	{
		$result = [];
		$users = [];

		$categories = $this->findCategories();

		$sortedCategories = [];
		$sortOrder = ['Furniture', 'Electronics', 'Home', 'Jewelry', 'Fashion', 'Art', 'Automotive', 'Crafts', 'Vintage', 'Glass', 'Wood', 'Office', 'Holidays', 'Outdoors', 'Yard', 'Hardware', 'Plastic', 'Metal', 'Toys', 'Pets', 'Paper', 'Musical', 'Sporting Goods'];

		foreach ($sortOrder AS $sortedCategory)
		{
			if (($key = array_search($sortedCategory, array_column($categories, 'name', 'id'))) !== \false)
			{
				$sortedCategories[ $key ] = $categories[ $key ];
			}
		}

		$foundIds = [];
		$foundUsers = [];
		foreach ($sortedCategories AS $id => $category)
		{
			$sql = "SELECT product.id_product, ps_customer.id_customer AS id_user, product_name, ps_customer.email, category.id_category, product_shop.price, lang.link_rewrite, shop_name,
				if(lang.meta_description = '', if(lang.description = '', lang.description_short, lang.description), lang.meta_description) AS description,
				ifnull((SELECT sum(counter) FROM upshop.up_page page INNER JOIN upshop.up_page_viewed viewed ON viewed.id_page = page.id_page WHERE id_page_type = 3 AND id_object = product.id_product), 0) AS counter,
		        ifnull((SELECT group_concat(DISTINCT tag.name) AS tags FROM upshop.up_product_tag product_tag INNER JOIN upshop.up_tag tag ON tag.id_tag = product_tag.id_tag WHERE product_tag.id_product = product.id_product), '') AS tags,
		        (SELECT id_image FROM upshop.up_image image WHERE image.id_product = product.id_product ORDER BY cover DESC LIMIT 1) AS id_image,
		        seller_product.date_add AS posted
				  FROM upshop.up_marketplace_shop shop
				  INNER JOIN upshop.up_customer ps_customer on ps_customer.id_customer = shop.id_customer
				  INNER JOIN upshop.up_marketplace_shop_product product ON product.id_shop = shop.id
				  INNER JOIN upshop.up_marketplace_seller_product seller_product ON seller_product.id = product.marketplace_seller_id_product
				  INNER JOIN upshop.up_product_shop product_shop ON product_shop.id_product = product.id_product
				  LEFT OUTER JOIN upshop.up_category_product category on category.id_product = product.id_product and category.id_category <> 2
				  INNER JOIN upshop.up_product_lang lang on lang.id_product = product.id_product
				  WHERE seller_product.quantity > 0 AND category.id_category = ?";

			if (!empty($foundIds))
			{
				$sql .= sprintf(" AND product.id_product NOT IN(%s)", implode(',', $foundIds));
			}

			if (!empty($foundUsers))
			{
				$sql .= sprintf(" AND ps_customer.id_customer NOT IN(%s)", implode(',', $foundUsers));
			}

			$sql .= " GROUP BY product.id ORDER BY rand() LIMIT 2";

			$productsResult = $this->__shopConnection->query($sql, [$id]);

			$products = [];
			$this->getProductsFromResult($productsResult, $products, $users, $categories);

			foreach ($products AS $product)
			{
				$result[] = $product;
				$foundIds[] = $product['ik'];
				$foundUsers[] = $product['id_user'];
			}
		}

		return $result;
	}

	protected function getProductsFromResult($productsResult, Array &$result, Array &$users, $categories = \false)
	{
		$url = \Phalcon\DI::getDefault()->get('imageUrl');
		if (!$categories)
		{
			$categories = $this->findCategories();
		}

		while ($productInformation = $productsResult->fetchArray())
		{
			$theCategory = (isset($productInformation['id_category']) && !is_null($productInformation['id_category'])) ? $productInformation['id_category'] : 2;
			$userId = $productInformation['id_user'];
			$email = $productInformation['email'];

			$userName = 'Unknown';
			$userIk = 0;

			if (isset($categories[ $theCategory ]))
			{
				$category = $categories[ $theCategory ];
				$hasImage = (isset($productInformation['id_image']) && !is_null($productInformation['id_image']));

				if (!isset($users[ $userId ]))
				{
					$user = \User::findFirst(['email = ?0', 'bind' => [$email]]);
					if ($user)
					{
						$userName = $user->user_name;
						$userIk = $user->ik;

						$users[ $userId ] = [
							'ik'        => $userIk,
							'user_name' => $userName
						];
					}
				}
				else
				{
					$userIk = $users[ $userId ]['ik'];
					$userName = $users[ $userId ]['user_name'];
				}

				// Create a thumbnail of the image
				$imageName = sprintf('%s.jpg', $productInformation['id_image']);
				$imageUrl = sprintf('%s/%s/image.jpg', $this->__config->prestashop->get('baseUrl'), $productInformation['id_image']);

				if ($hasImage)
				{
					$imagePath = $this->__config->prestashop->get('baseImagePath');
					$len = strlen($productInformation['id_image']);
					for ($i = 0; $i < $len; $i++)
					{
						$imagePath = sprintf('%s/%s', $imagePath, $productInformation['id_image'][ $i ]);
					}

					$imagePath = sprintf('%s/%s.jpg', $imagePath, $productInformation['id_image']);

					$thumbnailImagePath = sprintf('%s%s', $this->__config->application->productThumbnailDir, $imageName);

					if (file_exists($thumbnailImagePath))
					{
						$imageUrl = $url->get(sprintf('post/products/%s', $imageName));
					}
					elseif (file_exists($imagePath))
					{
						list($width, $height, $type, $attr) = @getimagesize($imagePath);

						$imageProcessingService = new \ImageProcessingService($imagePath);

						if ($imageProcessingService->createThumbnail($thumbnailImagePath, ($width >= 244) ? 244 : $width))
						{
							$imageUrl = $url->get(sprintf('post/products/%s', $imageName));
						}
					}
				}

				$result[] = [
					'id_user'       => $productInformation['id_user'],
					'ik'            => $productInformation['id_product'],
					'url'           => sprintf('%s/%s/%s-%s.html', $this->__config->prestashop->get('baseUrl'), $category['url'], $productInformation['id_product'], $productInformation['link_rewrite']),
					'title'         => $productInformation['product_name'],
					'categoryTitle' => $category['name'],
					'categoryUrl'   => sprintf('%s/%s-%s', $this->__config->prestashop->get('baseUrl'), $category['id'], $category['url']),
					'categoryIk'    => $category['id'],
					'image'         => ($hasImage) ? $imageUrl : 'http://www.upcyclepost.com/shop/img/p/en-default-home_default.jpg',
					'user'          => $userIk,
					'userName'      => $userName,
					'shopName'      => $productInformation['shop_name'],
					'price'         => money_format('%i', $productInformation['price']),
					'views'         => $productInformation['counter'],
					'market'        => \true,
					'description'   => $productInformation['description'],
					'tags'          => $productInformation['tags'],
					'posted'        => $productInformation['posted']
				];
			}
		}

		return $result;
	}

	public function countProducts($user = \false)
	{
		$total = 0;

		$sql = 'SELECT count(distinct product.id_product) AS total FROM upshop.up_marketplace_shop shop INNER JOIN upshop.up_customer ps_customer on ps_customer.id_customer = shop.id_customer INNER JOIN upshop.up_marketplace_shop_product product ON product.id_shop = shop.id INNER JOIN upshop.up_marketplace_seller_product seller_product ON seller_product.id = product.marketplace_seller_id_product INNER JOIN upshop.up_product_shop product_shop ON product_shop.id_product = product.id_product';
		if ($user !== \false)
		{
			$sql .= ' WHERE ps_customer.email = ?';
			$countResult = $this->__shopConnection->query($sql, [$user->email]);
		}
		else
		{
			$countResult = $this->__shopConnection->query($sql);
		}

		foreach ($countResult AS $result)
		{
			$total = $result['total'];
		}

		return $total;
	}

	/**
	 * @param mixed $limit
	 * @param bool  $user
	 *
	 * @return array
	 */
	public function findRecentProducts($limit = 49, $user = \false)
	{
		$result = [];
		$users = [];

		// Lookup Prestashop categories
		$categories = $this->findCategories();

		// Build the query to find products
		$sql = "SELECT product.id_product, ps_customer.id_customer AS id_user, product_name, ps_customer.email, category.id_category, product_shop.price, lang.link_rewrite, shop_name,
				if(lang.meta_description = '', if(lang.description = '', lang.description_short, lang.description), lang.meta_description) AS description,
				ifnull((SELECT sum(counter) FROM upshop.up_page page INNER JOIN upshop.up_page_viewed viewed ON viewed.id_page = page.id_page WHERE id_page_type = 3 AND id_object = product.id_product), 0) AS counter,
		        ifnull((SELECT group_concat(DISTINCT tag.name) AS tags FROM upshop.up_product_tag product_tag INNER JOIN upshop.up_tag tag ON tag.id_tag = product_tag.id_tag WHERE product_tag.id_product = product.id_product), '') AS tags,
		        (SELECT id_image FROM upshop.up_image image WHERE image.id_product = product.id_product ORDER BY cover DESC LIMIT 1) AS id_image,
		        seller_product.date_add AS posted
				  FROM upshop.up_marketplace_shop shop
				  INNER JOIN upshop.up_customer ps_customer on ps_customer.id_customer = shop.id_customer
				  INNER JOIN upshop.up_marketplace_shop_product product ON product.id_shop = shop.id
				  INNER JOIN upshop.up_marketplace_seller_product seller_product ON seller_product.id = product.marketplace_seller_id_product
				  INNER JOIN upshop.up_product_shop product_shop ON product_shop.id_product = product.id_product
				  LEFT OUTER JOIN upshop.up_category_product category on category.id_product = product.id_product and category.id_category <> 2
				  INNER JOIN upshop.up_product_lang lang on lang.id_product = product.id_product
				  WHERE seller_product.quantity > 0";

		if ($user)
		{
			$sql = sprintf('%s AND ps_customer.email=?', $sql);
		}

		$sql = sprintf('%s GROUP BY product.id ORDER BY product.id desc', $sql);

		if ($limit)
		{
			$sql = sprintf('%s LIMIT %s', $sql, (int)$limit);
		}

		if ($user)
		{
			$productsResult = $this->__shopConnection->query($sql, [$user->email]);
		}
		else
		{
			$productsResult = $this->__shopConnection->query($sql);
		}

		$this->getProductsFromResult($productsResult, $result, $users, $categories);

		return $result;
	}

	public function getShopId(\User $user)
	{
		$result = \false;

		if (($customer = $this->__lookupPrestashopUserByEmail($user->email)) !== \false)
		{
			$shopConnection = \Phalcon\DI::getDefault()->get('prestashopDb');

			// Build the query to find products
			$sql = "SELECT id FROM upshop.up_marketplace_shop WHERE id_customer = ?";

			$shopResult = $shopConnection->query($sql, [$customer[0]]);

			while ($shopInformation = $shopResult->fetchArray())
			{
				$result = $shopInformation['id'];
			}
		}

		return $result;
	}

	public function loginToPrestashop(\User $user, $justCreated = \false)
	{
		if (($customer = $this->__lookupPrestashopUserByEmail($user->email)) !== \false)
		{
			list ($customerId, $customerLastName, $customerFirstName, $customerPasswordHash, $customerEmail)
				= $customer;

			$core = new PrestashopCookieControllerCore();
			$core->setCookie($customerId, $customerLastName, $customerFirstName, $customerPasswordHash, $customerEmail);

			return \true;
		}
		else if (!$justCreated)
		{
			// If we haven't just created this user, then attempt to log them in to Prestashop
			if ($this->registerPrestashopUser($user))
			{
				return $this->loginToPrestashop($user, \true);
			}
		}

		return \false;
	}

	public function logoutOfPrestashop()
	{
		$core = new PrestashopCookieControllerCore();
		$core->logout();

		return \true;
	}

	public function registerPrestashopUser(\User $user)
	{
		$xml = $this->__service->get([
			                             'resource' => 'customers?schema=blank',
			                             'schema'   => 'blank'
		                             ]);
		$resources = $xml->children()->children();

		$firstName = $user->first_name;
		$lastName = $user->last_name;

		if (!$firstName && !$lastName)
		{
			list($firstName, $lastName) = explode(' ', $user->name);
		}

		$details = [
			'id_default_group'         => 3,
			'id_lang'                  => 1,
			'secure_key'               => md5(time()),
			'deleted'                  => 0,
			'passwd'                   => md5($user->password),
			'lastname'                 => preg_replace('/[^A-Za-z\s]/', '', $firstName),
			'firstname'                => preg_replace('/[^A-Za-z\s]/', '', $lastName),
			'email'                    => $user->email,
			'newsletter'               => $user->contact_for_marketplace == 1 ? 1 : 0,
			'optin'                    => $user->contact_for_marketplace == 1 ? 1 : 0,
			'active'                   => 1,
			'is_guest'                 => 0,
			'id_shop'                  => 1,
			'id_shop_group'            => 1,
			'last_passwd_gen'          => $user->registered,
			'date_add'                 => $user->registered,
			'date_upd'                 => $user->registered,
			'outstanding_allow_amount' => 0.00,
			'show_public_prices'       => 0,
			'id_risk'                  => 0,
			'max_payment_days'         => 0,
			'birthday'                 => '0000-00-00',
			'website'                  => $user->ik
		];

		foreach ($resources AS $nodeKey => $node)
		{
			if (isset($details[ $nodeKey ]))
			{
				$resources->$nodeKey = $details[ $nodeKey ];
			}
		}

		$resources->associations->groups->group->id = 3;

		try
		{
			$result = $this->__service->add([
				                                'resource' => 'customers',
				                                'postXml'  => $xml->asXML()
			                                ]);
		}
		catch (PrestaShopWebserviceException $e)
		{
			return \false;
		}

		return \true;
	}

	public function getTotalItemsInCart()
	{
		$core = new PrestashopCookieControllerCore();

		return count($core->getItemsInCart());
	}

	/**
	 * Looks up a PrestaShop user through the PrestaShop API.
	 *
	 * @param $email
	 *
	 * @return array|bool
	 * @throws \PrestaShopWebserviceException
	 */
	protected function __lookupPrestashopUserByEmail($email)
	{
		// @TODO: Cache with xcache
		if (isset($this->__customer) && is_array($this->__customer))
		{
			return $this->__customer;
		}

		$response = $this->__service->get([
			                        'resource' => 'customers',
			                        'filter'   => [
				                        'email' => $email
			                        ],
		                            'display'   => 'full'
		                        ]);

		if ($response->customers)
		{
			if ($response->customers->count() == 1)
			{
				// We found this user
				$customer = $response->customers[0]->customer;

				if (!is_null($customer->id))
				{
					// We convert all of the SimpleXMLElement objects to strings to get their values
					$this->__customer = [
						(string)$customer->id,
						(string)$customer->lastname,
						(string)$customer->firstname,
						(string)$customer->passwd,
						(string)$customer->email
					];

					return $this->__customer;
				}
			}
			else if ($response->customers->count() == 0)
			{
				// We found no users
			}
		}

		// We either didn't get a response, or found too many users.
		// Neither of these should occur, but handle it anyway.
		return \false;
	}

	protected function __getPrestashopUserEmail($id)
	{
		$response = $this->__service->get([
			                                  'resource' => 'customers',
			                                  'filter'   => [
				                                  'id' => $id
			                                  ],
			                                  'display'   => 'full'
		                                  ]);

		if ($response->customers)
		{
			if ($response->customers->count() == 1)
			{
				// We found this user
				$customer = $response->customers[0]->customer;
				// We convert all of the SimpleXMLElement objects to strings to get their values
				return (string)$customer->email;
			}
		}

		return \false;
	}
}

class PrestashopCookieControllerCore extends \FrontController
{
	public function init()
	{
		parent::init();
	}

	public function initContent()
	{
		parent::initContent();
	}

	public function getCookie()
	{
		return $this->context->cookie;
	}

	public function setCookie($customerId, $customerLastName, $customerFirstName, $customerPasswordHash, $customerEmail)
	{
		$customer = new \Customer();
		$customer->getByEmail($customerEmail);

		$customer->logged = 1;
		$this->context = \Context::getContext();
		$this->context->customer = $customer;

		$this->context->cookie->id_customer = $customerId;
		$this->context->cookie->customer_lastname = $customerLastName;
		$this->context->cookie->customer_firstname = $customerFirstName;
		$this->context->cookie->logged = 1;

		$this->context->cookie->is_guest = 0;
		$this->context->cookie->passwd = $customerPasswordHash;
		$this->context->cookie->email = $customerEmail;

		if (!$this->context->cart)
		{
			if ($this->context->cookie->id_cart)
			{
				$this->context->cart = new \Cart($this->context->cookie->id_cart);
			}
			else if ($id_cart = (int)\Cart::lastNoneOrderedCart($this->context->customer->id))
			{
				$this->context->cart = new \Cart($id_cart);
			}
		}

		if (\Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart) || \Cart::getNbProducts($this->context->cookie->id_cart) == 0) && $id_cart = (int)\Cart::lastNoneOrderedCart($this->context->customer->id))
		{
			$this->context->cart = new \Cart($id_cart);
		}
		else if ($this->context->cart)
		{
			$id_carrier = (int)$this->context->cart->id_carrier;
			$this->context->cart->id_carrier = 0;
			$this->context->cart->setDeliveryOption(null);
			$this->context->cart->id_address_delivery = (int)\Address::getFirstCustomerAddressId((int)($customer->id));
			$this->context->cart->id_address_invoice = (int)\Address::getFirstCustomerAddressId((int)($customer->id));
		}

		if ($this->context->cart)
		{
			$this->context->cart->id_customer = (int)$customer->id;
			$this->context->cart->secure_key = $customer->secure_key;

			if ($this->ajax && isset($id_carrier) && $id_carrier && \Configuration::get('PS_ORDER_PROCESS_TYPE'))
			{
				$delivery_option = array($this->context->cart->id_address_delivery => $id_carrier.',');
				$this->context->cart->setDeliveryOption($delivery_option);
			}

			$this->context->cart->save();

			$this->context->cookie->id_cart = (int)$this->context->cart->id;

			$this->context->cookie->write();
		}

		if ($this->context->cart)
		{
			$this->context->cart->autosetProductAddress();

			\CartRule::autoRemoveFromCart($this->context);
			\CartRule::autoAddToCart($this->context);
		}
	}

	public function getItemsInCart()
	{
		$this->context = \Context::getContext();

		if (!$this->context->cart)
		{
			if ($this->context->cookie->id_cart)
			{
				$this->context->cart = new \Cart($this->context->cookie->id_cart);
			}
		}

		if ($this->context->cart)
		{
			return $this->context->cart->getProducts();
		}

		return [];
	}

	public function logout()
	{
		$this->context->customer->logout();
	}
}