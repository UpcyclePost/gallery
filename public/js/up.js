(function (window, document) {
	var __up;

	function UP() {
		this.init();
	}

	UP.prototype = {
		init: function () {
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
								key: product.value,
								url: product.hasOwnProperty('url') ? product.url : ''
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
								key: shop.value,
								url: shop.hasOwnProperty('url') ? shop.url : ''
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
								key: idea.value,
								url: idea.hasOwnProperty('url') ? idea.url : ''
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
								key: user.value,
								url: user.hasOwnProperty('url') ? user.url : ''
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
				if (data.hasOwnProperty('url') && data.url.length)
				{
					window.location = data.url;
				}
			});
		}
	}

	__up = new UP();
})(this, this.document);