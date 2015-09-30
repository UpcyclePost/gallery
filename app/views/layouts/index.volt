{% set isIndexPage = true %}
{{ partial('partial/header') }}

        <section class="product_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 promo">
                        <div class="promo_icon">
                            <ul>
                                <li>
                                    <a href="{{ url('/up/shop') }}"><img alt="" src="{{ static_url('upmod/img/shop.png') }}"></a></a>

                                    <h2>Shop</h2>
                                </li>

                                <li>
                                    <a href="{{ url('/up/sell') }}"><img alt="" src="{{ static_url('upmod/img/sell.png') }}"></a>

                                    <h2>Sell</h2>
                                </li>

                                <li>
                                    <a href="{{ url('/up/share') }}"><img alt="" src="{{ static_url('upmod/img/share.png') }}"></a>

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
                                                        <button class="btn btn-default" type="button">Share <i class="fa fa-share-square-o"></i></button>
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
                                                    <a href="{{ url('shops/') ~ post['userName'] }}"><?=Helpers::Truncate($post['shopName'], 20)?></a>
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
                                                <button class="btn btn-default" type="button">Share <i class="fa fa-share-square-o"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="caption">
                                    <a href="{{ results[i]['url'] }}">
                                        <h2><?=Helpers::Truncate($results[$i]['title'], 38)?></h2>
                                    </a>

                                    <div class="product-meta clearfix">
                                        <a class="author" href="{{ url('shops/') ~ results[i]['userName'] }}">
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
                            <h2>We Love the upmod Artisans</h2>

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
                            <a href="http://www.upcyclepost.com/shops/alinescardboard">
                                <div class="thumbnail-container">
                                    <img alt="" src="{{ static_url('img/features/shop.png') }}" style="min-height:238px;">
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
                                            <button class="btn btn-default" type="button">Share <i class="fa fa-share-square-o"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="caption">
                                <a href="http://www.upcyclepost.com/shops/alinescardboard">
                                    <h2>Aline's Cardboard</h2>
                                </a>

                                <div class="product-meta clearfix">
                                    <a class="author" href="http://www.upcyclepost.com/shops/alinescardboard">
                                        Issaquah, WA
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
                            <a href="https://www.upcyclepost.com/shop/automotive/620-new-70-s-stylin-hubcap-celling-fixture.html">
                                <div class="thumbnail-container">
                                    <img alt="" src="{{ static_url('img/features/product.png') }}" style="min-height:238px;">
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
                                            <button class="btn btn-default" type="button">Share <i class="fa fa-share-square-o"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="caption">
                                <a href="https://www.upcyclepost.com/shop/automotive/620-new-70-s-stylin-hubcap-celling-fixture.html">
                                    <h2>New 70's Stylin' Hubcap Celling Fixture</h2>
                                </a>

                                <div class="product-meta clearfix">
                                    <a class="author" href="https://www.upcyclepost.com/shops/101790">
                                        Refitting the Planet
                                    </a>

                                    <a class="price" href="https://www.upcyclepost.com/shop/automotive/620-new-70-s-stylin-hubcap-celling-fixture.html">$450.00</a>
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
                            <a href="http://www.upcyclepost.com/profile/view/156">
                                <div class="thumbnail-container">
                                    <img alt="" src="{{ static_url('img/features/profile.png') }}" style="min-height:238px;">
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
                                            <button class="btn btn-default" type="button">Share <i class="fa fa-share-square-o"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="caption">
                                <a href="http://www.upcyclepost.com/profile/view/156">
                                    <h2>Raymond Guest</h2>
                                </a>

                                <div class="product-meta clearfix">
                                    <a class="author" href="http://www.upcyclepost.com/profile/view/156">
                                        Longview, TX
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
                            <a href="{{ url('/up/shop') }}"><img alt="" src="{{ static_url('upmod/img/shop.png') }}"></a>

                            <h2>Shop</h2>

                            <p>Buy one-of-a-kind works made<br>
                                by our upcycle artisans and<br>
                                upcycle community healthy.</p>

                            <h3><a href="{{ url('/up/shop') }}">Shop our wares</a></h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="work_sample">
                            <a href="{{ url('/up/sell') }}"><img alt="" src="{{ static_url('upmod/img/sell.png') }}"></a>

                            <h2>Sell</h2>

                            <p>Are you an upcycle artisan?<br>
                                Create your very own shop and<br>
                                sell your upcyled pieces online.</p>

                            <h3><a href="{{ url('/up/sell') }}">Create your shop</a></h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="work_sample pull-left">
                            <a href="{{ url('/up/share') }}"><img alt="" src="{{ static_url('upmod/img/share.png') }}"></a>

                            <h2>Share</h2>

                            <p>Join the upcyle community and<br>
                                share inspirations with other<br>
                                upcyclers. Spread the word!</p>

                            <h3><a href="{{ url('/up/share') }}">Join the community</a></h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

{{ partial('partial/footer') }}