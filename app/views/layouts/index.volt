{% set isIndexPage = true %}
{{ partial('partial/header') }}

        <section class="product_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 promo">
                        <div class="promo_icon">
                            <ul>
                                <li>
                                    <a href="{{ url('up/shop') }}"><img alt="Shop upcycled and recycled products at Upmod" src="{{ static_url('upmod/img/shop.png') }}"></a></a>

                                    <h2>Shop</h2>
                                </li>

                                <li>
                                    <a href="{{ url('sell') }}"><img alt="Sell upcycled and recycled products at Upmod" src="{{ static_url('upmod/img/sell.png') }}"></a>

                                    <h2>Sell</h2>
                                </li>

                                <li>
                                    <a href="{{ url('share') }}"><img alt="Share upcycled and recycled products at Upmod" src="{{ static_url('upmod/img/share.png') }}"></a>

                                    <h2>Share</h2>
                                </li>
                            </ul>
                            <p>Join the movement to shop, sell, and share upcycled products.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {% for post in results %}
                        {% if loop.index <= 8 %}
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <article class="product_image thumbnail">
                                        <a href="{{ post['url'] }}">
                                            <div class="thumbnail-container">
                                                <img alt="{{ post['title'] }}" src="{{ post['image'] }}" style="min-height:200px;min-width:273px">
                                                <div class="product-icons">
                                                    <div class="icon-circle">
                                                        <i class="fa fa-heart"></i>
                                                    </div>
                                                    <div class="icon-circle">
                                                        <i class="fa fa-share-square-o"></i>
                                                    </div>
                                                </div>
                                                <div class="overlay">
                                                    <div class="btn-group like">
                                                        <button class="btn btn-default" type="button"><i class="fa fa-heart"></i> Like</button>
                                                    </div>

                                                    <div class="btn-group share">
                                                        <span class='st_facebook' st_url="{{ post['url'] }}" st_title="{{ post['title'] }}" st_image="{{ post['image'] }}"></span>
                                                        <br>
                                                        <span class='st_twitter' st_via='Upmodinc' st_url="{{ post['url'] }}" st_title="{{ post['title'] }}" st_image="{{ post['image'] }}"></span>
                                                        <br>
                                                        <span class='st_googleplus' st_url="{{ post['url'] }}" st_title="{{ post['title'] }}" st_image="{{ post['image'] }}"></span>
                                                        <br>
                                                        <span class='st_pinterest' st_url="{{ post['url'] }}" st_title="{{ post['title'] }}" st_image="{{ post['image'] }}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                        <div class="caption">
                                            <a href="{{ post['url'] }}">
                                                <h2><?=Helpers::Truncate($post['title'], 28)?></h2>
                                            </a>

                                            <div class="product-meta clearfix">
                                                <a class="author" href="#">
                                                    <a href="{{ url('shops/') }}<?=Helpers::url($post['userName'])?>"><?=Helpers::Truncate($post['shopName'], 20)?></a>
                                                </a>

                                                <a class="price" href="{{ post['url'] }}">${{ post['price']|pretty }}</a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                        {% endif %}
                    {% endfor %}
                </div>
                <div class="row">
                    {% for i in 8..10 %}
                        <div class="col-lg-4 col-md-4 visible-lg visible-md">
                            <article class="product_image thumbnail wide">
                                <a href="{{ results[i]['url'] }}">
                                    <div class="thumbnail-container">
                                        <img alt="{{ results[i]['title'] }}" src="{{ results[i]['image'] }}" style="min-height:238px;">
                                        <div class="product-icons">
                                            <div class="icon-circle">
                                                <i class="fa fa-heart"></i>
                                            </div>
                                            <div class="icon-circle">
                                                <i class="fa fa-share-square-o"></i>
                                            </div>
                                        </div>
                                        <div class="overlay">
                                            <div class="btn-group like">
                                                <button class="btn btn-default" type="button"><i class="fa fa-heart"></i> Like</button>
                                            </div>

                                            <div class="btn-group share">
                                                <span class='st_facebook' st_url="{{ results[i]['url'] }}" st_title="{{ results[i]['title'] }}" st_image="{{ results[i]['image'] }}"></span>
                                                <br>
                                                <span class='st_twitter' st_via='Upmodinc' st_url="{{ results[i]['url'] }}" st_title="{{ results[i]['title'] }}" st_image="{{ results[i]['image'] }}"></span>
                                                <br>
                                                <span class='st_googleplus' st_url="{{ results[i]['url'] }}" st_title="{{ results[i]['title'] }}" st_image="{{ results[i]['image'] }}"></span>
                                                <br>
                                                <span class='st_pinterest' st_url="{{ results[i]['url'] }}" st_title="{{ results[i]['title'] }}" st_image="{{ results[i]['image'] }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="caption">
                                    <a href="{{ results[i]['url'] }}">
                                        <h2><?=Helpers::Truncate($results[$i]['title'], 38)?></h2>
                                    </a>

                                    <div class="product-meta clearfix">
                                        <a class="author" href="{{ url('shops/') }}<?=Helpers::url($results[$i]['userName'])?>">
                                            {{ results[i]['shopName'] }}
                                        </a>

                                        <a class="price" href="{{ results[i]['url'] }}">${{ results[i]['price']|pretty }}</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    {% endfor %}
                </div>
                <div class="more_button">
                    <a class="btn btn-default shop_more" href="{{ url('browse/products') }}">Shop more products</a>
                </div>
            </div>
        </section>

        <section class="unique_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="unik_text">
                            <h2>We Love the Upmod Artisans</h2>

                            <p>Upcycling Artisans power our community. Join the movement and sell your creations!</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="heading">
                            <h2>Great Shops</h2>
                        </div>

                        <div class="product_image thumbnail wide bot">
                            <a href="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop">
                                <div class="thumbnail-container">
                                    <img alt="WilmotArtWorks" src="{{ static_url('img/features/FeaturedShop2.png') }}" style="min-height:238px;">
                                    <div class="product-icons">
                                        <div class="icon-circle">
                                            <i class="fa fa-heart"></i>
                                        </div>
                                        <div class="icon-circle">
                                            <i class="fa fa-share-square-o"></i>
                                        </div>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn-group like">
                                            <button class="btn btn-default" type="button"><i class="fa fa-heart"></i> Like</button>
                                        </div>

                                        <div class="btn-group share">
                                            <span class='st_facebook' st_url="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop" st_title="WilmotArtWorks" st_image="{{ url('img/features/FeaturedShop2.png') }}"></span>
                                            <br>
                                            <span class='st_twitter' st_via='Upmodinc' st_url="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop" st_title="WilmotArtWorks" st_image="{{ url('img/features/FeaturedShop2.png') }}"></span>
                                            <br>
                                            <span class='st_googleplus' st_url="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop" st_title="WilmotArtWorks" st_image="{{ url('img/features/FeaturedShop2.png') }}"></span>
                                            <br>
                                            <span class='st_pinterest' st_url="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop" st_title="WilmotArtWorks" st_image="{{ url('img/features/FeaturedShop2.png') }}"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="caption">
                                <a href="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop">
                                    <h2>WilmotArtWorks</h2>
                                </a>

                                <div class="product-meta clearfix">
                                    <a class="author" href="https://www.upmod.com/shops/wilmotartworks?utm_source=upmod&utm_medium=Featured&utm_content=WilmotArtWorks&utm_campaign=Featured%20Shop">
                                        East Millstone, NJ
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="buton">
                            <a class="btn btn-default shop_more unik" href="{{ url('shop/module/marketplace/sellerrequest') }}">Create your shop</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="heading">
                            <h2>Unique Products</h2>
                        </div>

                        <div class="product_image thumbnail wide bot">
                            <a href="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product">
                                <div class="thumbnail-container">
                                    <img alt="Industrial Reclaimed wood and chain coffee table" src="{{ static_url('img/features/FeaturedProduct2.jpg') }}" style="min-height:238px;">
                                    <div class="product-icons">
                                        <div class="icon-circle">
                                            <i class="fa fa-heart"></i>
                                        </div>
                                        <div class="icon-circle">
                                            <i class="fa fa-share-square-o"></i>
                                        </div>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn-group like">
                                            <button class="btn btn-default" type="button"><i class="fa fa-heart"></i> Like</button>
                                        </div>

                                        <div class="btn-group share">
                                            <span class='st_facebook' st_url="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product" st_title="Industrial Reclaimed wood and chain coffee table" st_image="{{ url('img/features/FeaturedProduct2.jpg') }}"></span>
                                            <br>
                                            <span class='st_twitter' st_via='Upmodinc' st_url="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product" st_title="Industrial Reclaimed wood and chain coffee table" st_image="{{ url('img/features/FeaturedProduct2.jpg') }}"></span>
                                            <br>
                                            <span class='st_googleplus' st_url="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product" st_title="Industrial Reclaimed wood and chain coffee table" st_image="{{ url('img/features/FeaturedProduct2.jpg') }}"></span>
                                            <br>
                                            <span class='st_pinterest' st_url="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product" st_title="Industrial Reclaimed wood and chain coffee table" st_image="{{ url('img/features/FeaturedProduct2.jpg') }}"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="caption">
                                <a href="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product">
                                    <h2>Reclaimed wood and chain coffee table</h2>
                                </a>

                                <div class="product-meta clearfix">
                                    <a class="author" href="https://www.upmod.com/shops/twistedarc">
                                        Twisted Arc Metal Works LLC.
                                    </a>

                                    <a class="price" href="https://www.upmod.com/shop/furniture/1899-industrial-reclaimed-wood-and-chain-coffee-table.html?utm_source=upmod&utm_medium=Featured&utm_content=Industrial%20Reclaimed%20wood%20and%20chain%20coffee%20table%20&utm_campaign=Featured%20Product">$3,500.00</a>
                                </div>
                            </div>
                        </div>

                        <div class="buton">
                            <a class="btn btn-default shop_more unik" href="{{ url('browse/products') }}">Shop more products</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="heading">
                            <h2>Avid Upcyclers</h2>
                        </div>

                        <div class="product_image thumbnail wide bot">
                            <a href="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan">
                                <div class="thumbnail-container">
                                    <img alt="Tami MacDonald Brinker" src="{{ static_url('img/features/FeaturedSeller2.png') }}" style="min-height:238px;">
                                    <div class="product-icons">
                                        <div class="icon-circle">
                                            <i class="fa fa-heart"></i>
                                        </div>
                                        <div class="icon-circle">
                                            <i class="fa fa-share-square-o"></i>
                                        </div>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn-group like">
                                            <button class="btn btn-default" type="button"><i class="fa fa-heart"></i> Like</button>
                                        </div>

                                        <div class="btn-group share">
                                            <span class='st_facebook' st_url="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan" st_title="Tami MacDonald Brinker" st_image="{{ url('img/features/FeaturedSeller2.png') }}"></span>
                                            <br>
                                            <span class='st_twitter' st_via='Upmodinc' st_url="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan" st_title="Tami MacDonald Brinker" st_image="{{ url('img/features/FeaturedSeller2.png') }}"></span>
                                            <br>
                                            <span class='st_googleplus' st_url="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan" st_title="Tami MacDonald Brinker" st_image="{{ url('img/features/FeaturedSeller2.png') }}"></span>
                                            <br>
                                            <span class='st_pinterest' st_url="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan" st_title="Tami MacDonald Brinker" st_image="{{ url('img/features/FeaturedSeller2.png') }}"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="caption">
                                <a href="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan">
                                    <h2>Tami MacDonald Brinker</h2>
                                </a>

                                <div class="product-meta clearfix">
                                    <a class="author" href="http://www.upmod.com/profile/view/103256?utm_source=upmod&utm_medium=Featured&utm_content=Tami%20MacDonald%20Brinker&utm_campaign=Featured%20Artisan">
                                        Michigan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="buton">
                            <a class="btn btn-default shop_more unik" href="{{ url('browse/members') }}">Meet fellow upcyclers</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="info_area">
            <div class="container">
       
		<div class="container info_text hidden-xs hidden-sm">
		        <h2 class="text-center" style="margin-bottom:10px;">What people say</h2>
		            <div class="text-center" style="height:120px;">
			        <p class="testimonial visible">&#8220;I really, really, appreciate a selling platform such as yours. It is nice to have notice of, and respect for those of us that are so passionate about recycling, reducing, and recreating&#8221;<br> - <strong>Erin Bass</strong></p>
                                <p class="testimonial hidden">&#8220;I love the idea of making upcycled art, so promoting it is &#39way up there&#39 for me. Upcycling and sustainability are in the public eye now, and being able to show people different ways to upcycle is fantastic. &quot;Upmod&quot; is a great resource and a wonderful service to the online community.&#8221;<br> - <strong>Douglas Walker</strong></p>
                                <p class="testimonial hidden">&#8220;Upmod provides a service to those who are looking for ways to make what they do useful— an upcycled product without a buyer hasn’t been completely upcycled yet. Likewise, for those who are looking for something they want that is meaningful in a deeper way, Upmod fills the need.&#8221;<br> - <strong>Gary Hovey</strong></p>
                                <p class="testimonial hidden">&#8220;Upmod is an excellent example of how humans can become conscious and do something positive for the planet.&#8221;<br> - <strong>Alvaro Tamarit</strong></p>
                                <p class="testimonial hidden">&#8220;I love the mission of Upmod. Having art attract and entertain people to use the site gets the creative side out. Let’s all think outside the box.&#8221;<br> - <strong>Jenny Ellsworth</strong></p>
                                <p class="testimonial hidden">&#8220;I think Upmod is fantastic, artists tend to be bad at self-promotion and I suspect that upcyclers are worse than most. Having ones work displayed in a well-designed, professionally executed website will be a huge benefit to most artists, removing a large source of stress and distraction and allowing them to focus on making art.&#8221;<br> - <strong>Andrew Chase</strong></p>
                                <p class="testimonial hidden">&#8220;Upmod is a fulcrum of sustainability, creativity, and ingenuity.&#8221;<br> - <strong>Laura Beth Love</strong></p>
                                <p class="testimonial hidden">&#8220;Upmod has become an important hub supporting, connecting and inspiring those dedicated to embracing a sustainable lifestyle.&#8221;<br> - <strong>Boris Bally</strong></p>
                                <p class="testimonial hidden">&#8220;I love the vision behind Upmod - it's like if Etsy, the Zero Waste Alliance, Pinterest and Freecycle all had a baby together, this would be their offspring! &#8221;<br> - <strong>Nancy Judd</strong></p>
                                <p class="testimonial hidden">&#8220;In an industry where we are all waiting for the catalyst that galvanizes and empowers the movement, sources like Upmod are necessary to lead us to that point of real engagement.&#8221;<br> - <strong>Rodney Allen Trice</strong></p>
			    </div>
		</div>

	         <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="info_text">
                            <h2>How does it work?</h2>

                            <p>Join and help power the community for upcycled hand-crafted products.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="work_sample pull-right">
                            <a href="{{ url('up/shop') }}"><img alt="Shop upcycled and recycled products at Upmod" src="{{ static_url('upmod/img/shop.png') }}"></a>

                            <h2>Shop</h2>

                            <p>Buy one-of-a-kind works made<br>
                                by our upcycle artisans and<br>
                                upcycle community healthy.</p>

                            <h3><a href="{{ url('up/shop') }}">Shop our wares</a></h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="work_sample">
                            <a href="{{ url('sell') }}"><img alt="Sell upcycled and recycled products at Upmod" src="{{ static_url('upmod/img/sell.png') }}"></a>

                            <h2>Sell</h2>

                            <p>Are you an upcycle artisan?<br>
                                Create your very own shop and<br>
                                sell your upcyled pieces online.</p>

                            <h3><a href="{{ url('sell') }}">Create your shop</a></h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="work_sample pull-left">
                            <a href="{{ url('share') }}"><img alt="Share upcycled and recycled products at Upmod" src="{{ static_url('upmod/img/share.png') }}"></a>

                            <h2>Share</h2>

                            <p>Join the upcyle community and<br>
                                share inspirations with other<br>
                                upcyclers. Spread the word!</p>

                            <h3><a href="{{ url('share') }}">Join the community</a></h3>
                        </div>
                    </div>
                </div>
		<div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="work_sample pull-left" style="margin-top:20px">
				<a href="https://mixpanel.com/f/partner"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mobile Analytics" /></a>
			</div>
		    </div>
		</div>
            </div>
        </section>

{{ partial('partial/footer') }}

