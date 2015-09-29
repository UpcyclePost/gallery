<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->

<html class="no-js" lang="" xmlns="http://www.w3.org/1999/html">
<!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <title>Upmod</title>
        <meta content="" name="description">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link href="apple-touch-icon.png" rel="apple-touch-icon">
        <!-- <link href="{{ static_url('upmod/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/css/bootstrap-theme.min.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/css/main.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/style.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/responsive.css') }}" rel="stylesheet"> -->

        <link href="{{ static_url('upmod/css/styles.css') }}?5" rel="stylesheet">

        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="{{ static_url('upmod/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    </head>

    <body>

        <header class="header_area{% if isIndexPage is not defined %} subpages{% endif %}">
            <nav class="mainmenu">
                <div class="container">
                    <div class="row">
                        <a class="logo" href="{{ url('') }}"></a>
                        <div class="search">
                            <form>
                                <input type="search" placeholder="Search the world's largest upcyle hand-crafted community" class="form-control" id="universal-search">
                            </form>
                        </div>
                        <button class="hamburger-menu" id="hamburger">
                            <i class="fa fa-bars"></i>
                        </button>
                        <ul class="user-actions">
                            <li><a href="{{ url('profile/login') }}">Sign in</a></li>
                            <li><a href="{{ url('profile/register') }}" class="btn btn-default signup_btn">Sign up</a></li>
                        </ul>
                    </div>
                </div>
                <div class="main-dd-menu">
                    <div class="container">
                        <div class="row">
                            <div class="column clearfix">
                                <h3>Shop Categories</h3>
                                <ul class="categories">
                                    <?php $categories = Helpers::getCategoryList(); ?>
                                    {% for _category in categories %}
                                        <li><a href="{{ url('browse/products/' ~ _category['title']|url) }}">{{ _category['title'] }}</a></li>
                                    {% endfor %}
                                </ul>
                            </div>
                            <div class="column clearfix">
                                <div class="left-menu">
                                    <h3>Share your ideas</h3>
                                    <ul class="links">
                                        <li><a href="{{ url('post/idea') }}">Upload your images</a></li>
                                        <li><a href="{{ url('browse/members') }}">View member gallery</a></li>
                                    </ul>
                                </div>
                                <div class="right-menu">
                                    <h3>Sell your products</h3>
                                    <ul class="links">
                                        <li><a href="{{ url('shop/module/marketplace/sellerrequest') }}">Create your shop</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="column clearfix">
                                <h3>Upmod</h3>
                                <ul class="links">
                                    <li><a href="{{ url('about') }}">About us</a></li>
                                    <li><a href="{{ url('blog') }}">Blog</a></li>
                                    <li><a href="{{ url('contact') }}">Contact us</a></li>
                                </ul>
                                <div class="social-icons">
                                    <a href="http://www.facebook.com/upcyclepost"><i class="fa fa-facebook-square"></i></a>
                                    <a href="http://www.linkedin.com/company/upcyclepost-com"><i class="fa fa-linkedin-square"></i></a>
                                    <a href="http://www.twitter.com/upcyclepost"><i class="fa fa-twitter-square"></i></a>
                                    <a href="http://www.pinterest.com/upcyclepost"><i class="fa fa-pinterest-square"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            {% if isIndexPage is defined %}
                <nav class="submenu">
                    <div class="container hidden-xs">
                        <div class="row">
                            <div class="left_menu">
                                <ul>
                                    <li>
                                        <a href="{{ url('browse/products/art') }}">Art</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('browse/products/fashion') }}">Fashion</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('browse/products/furniture') }}">Furniture</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('browse/products/home') }}">Home</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('browse/products/jewelry') }}">Jewelry</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('browse/products/vintage') }}">Vintage</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="right_menu">
                                <a class="menu-toggle">See all categories</a>
                            </div>
                        </div>
                    </div>
                </nav>

                <section class="header_content">
                    <div class="container">
                        <div class="row">
                            <div class="head_text">
                                <h1>Remaking Our World With Upcycled Products</h1>

                                <div class="head_button">
                                    <a class="btn btn-default prodct_btn" href="{{ url('browse/products') }}">Browse Products</a>
                                    <a class="btn btn-default move_btn" href="{{ url('profile/register') }}">Join the Movement</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            {% else %}
                <section>
                    <div class="container">
                        <div class="row">
                            <div class="page_title_text">
                                {% if page_title_text is defined %}
                                    {{ page_title_text }}
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </section>
            {% endif %}
        </header>

        {% if page_header_text is defined %}
            <div class="content-container">
                <div class="content-header">
                    <h1>
                        {{ page_header_text }}
                    </h1>
                </div>
            </div>
        {% endif %}