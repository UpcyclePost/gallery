(function (window, document) {
	var site;

	function UP() {
		this.init();
	}

	UP.prototype = {
		init: function () {
			$('nav#mobile-slide-menu').mmenu({
				offCanvas: {
					position: "right",
					zposition: "front"
				}
			});

			$('nav#mobile-slide-menu').removeClass('hidden');

			this.menu = new Menu();
			this.search = new Search();
		}
	};

	function Search() {
		this.init();
	}

	Search.prototype = {
		init: function () {
			var products = new Bloodhound({
				limit: 'Infinity',
				items: 4,
				datumTokenizer: function(d) { console.log(d); return d.id; },
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				identify: function(data) {
					return data.id;
				},
				remote: {
					url: '/search/quick/products?term=%QUERY',
					wildcard: '%QUERY',
					filter: function (products) {
						return $.map(products, function (product) {
							return {
								id: product.id,
								query: product.query,
								value: product.text,
								key: product.value
							};
						});
					}
				}
			});
			products.initialize();

			var shops = new Bloodhound({
				limit: 'Infinity',
				datumTokenizer: function(d) { return d.id; },
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				identify: function(data) {
					return data.id;
				},
				remote: {
					url: '/search/quick/shops?term=%QUERY',
					wildcard: '%QUERY',
					filter: function (shops) {
						return $.map(shops, function (shop) {
							return {
								id: shop.id,
								query: shop.query,
								value: shop.text,
								key: shop.value
							};
						});
					}
				}
			});
			shops.initialize();

			var ideas = new Bloodhound({
				limit: 'Infinity',
				datumTokenizer: function(d) { return d.id; },
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				identify: function(data) {
					return data.id;
				},
				remote: {
					url: '/search/quick/ideas?term=%QUERY',
					wildcard: '%QUERY',
					filter: function (ideas) {
						return $.map(ideas, function (idea) {
							return {
								id: idea.id,
								query: idea.query,
								value: idea.text,
								key: idea.value
							};
						});
					}
				}
			});
			ideas.initialize();

			var users = new Bloodhound({
				limit: 'Infinity',
				datumTokenizer: function(d) { return d.id; },
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				identify: function(data) {
					return data.id;
				},
				remote: {
					url: '/search/quick/users?term=%QUERY',
					wildcard: '%QUERY',
					filter: function (users) {
						return $.map(users, function (user) {
							return {
								id: user.id,
								query: user.query,
								value: user.text,
								key: user.value
							};
						});
					}
				}
			});
			users.initialize();

			var searchCtl = $('#universal-search').typeahead(
				{
					hint: true,
					highlight: true,
					minLength: 1,
					limit: 'Infinity',
					items: 12
				},
				{
					// Products
					name: 'products',
					display: 'key',
					limit: 'Infinity',
					source: products.ttAdapter(),
					templates: {
						suggestion: function(data) {
							return '<div>'+data.value+' <span style="font-weight:lighter;"> in Products</span></div>'
						}
					}
				},
				{
					// Shops
					name: 'shops',
					display: 'key',
					source: shops.ttAdapter(),
					templates: {
						suggestion: function(data) {
							return '<div>'+data.key+' <span style="font-weight:lighter;"> in Shops</span></div>'
						}
					}
				},
				{
					// Ideas
					name: 'ideas',
					display: 'key',
					source: ideas.ttAdapter(),
					templates: {
						suggestion: function(data) {
							return '<div>'+data.value+' <span style="font-weight:lighter;"> in Ideas</span></div>'
						}
					}
				},
				{
					// Users
					name: 'users',
					display: 'key',
					source: users.ttAdapter(),
					templates: {
						suggestion: function(data) {
							return '<div>'+data.key+' <span style="font-weight:lighter;"> in Users</span></div>'
						}
					}
				}
			);

			searchCtl.on('typeahead:selected', function (evt, data) {
				console.log(data.value);
			});
		}
	}

	function Menu() {
		this.init();
	}

	Menu.prototype = {


		init: function () {
			$('.mobile-menu').on('click', function (e) {
				var menu = $('#main-menu');

				if (menu.hasClass('show')) {
					menu.removeClass('show');
					$(document).unbind('click', site.menu.close);
				} else {
					menu.addClass('show');

					$(document).on('click', site.menu.close);
				}
				e.preventDefault();
			});
		},

		close: function () {
			if (!$(event.target).hasClass('mobile-menu') && $('.main-menu').has($(event.target)).length == 0) {
				$('#main-menu').removeClass('show');
				$(document).unbind('click', site.menu.close);

				return false;
			}
		}
	};

	site = new UP();
})(this, this.document);
