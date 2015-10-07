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

        {% if og_img is defined %}
            <meta property="og:image" content="{{ og_img }}" />
        {% endif %}
        {% if og_img_size is defined %}
            <meta property="og:image:width" content="{{ og_img_size[0] }}" />
            <meta property="og:image:height" content="{{ og_img_size[1] }}" />
        {% endif %}
        {% if og_description is defined %}
            <meta property="og:description" content="{{ og_description }}" />
        {% endif %}
        {% if canonical_url is defined %}
            <meta property="og:url" content="{{ canonical_url }}" />
        {% endif %}
        <meta property="og:title" content="{{ title }}" />

        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link href="apple-touch-icon.png" rel="apple-touch-icon">
        <!-- <link href="{{ static_url('upmod/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/css/bootstrap-theme.min.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/css/main.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/style.css') }}" rel="stylesheet">
        <link href="{{ static_url('upmod/responsive.css') }}" rel="stylesheet"> -->

        <link href="{{ static_url('upmod/css/styles.css') }}?5" rel="stylesheet">

        <!-- UpcyclePost Icons -->
        <link href="{{ static_url('css/upcyclepost/css/upcyclepost.css') }}" rel="stylesheet">

        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="{{ static_url('upmod/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
        <script src="{{ url('up/config') }}" type="text/javascript"></script>

        <!-- Start of upcyclepost Zendesk Widget script -->
        <script>
        /*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","upcyclepost.zendesk.com");/*]]>*/
        </script>
        <!-- End of upcyclepost Zendesk Widget script -->

        <!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
        for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
        mixpanel.init("{{ _mixpanel_key }}");</script><!-- end Mixpanel -->

        <?php $this->assets->outputCss() ?>
    </head>

    <body>
        {% if canLoadMoreItems is defined And canLoadMoreItems %}
            <form id="more" data-start="50" data-url="{{ loadMoreItemsUrl }}" data-search-term="{{ loadMoreItemsSearchTerm }}"></form>
        {% endif %}
        <header class="header_area{% if isIndexPage is not defined %} subpages{% endif %}">
            <nav class="mainmenu{% if isLoggedIn %} logged_in{% endif %}">
                <div class="container">
                    <div class="row">
                        <a class="logo" href="{{ url('') }}"></a>
                        <div class="search">
                            <form method="post" id="universal-search-form">
                                <input type="search" placeholder="Search the world's largest upcyle hand-crafted community" class="form-control" id="universal-search" name="term">
                            </form>
                        </div>
                        <button class="hamburger-menu" id="hamburger">
                            <i class="fa fa-bars"></i>
                        </button>
                        <ul class="user-actions">
                            {% if !isLoggedIn %}
                                <li><a href="{{ url('profile/login') }}">Sign in</a></li>
                                <li><a href="{{ url('profile/register') }}" class="btn btn-default signup_btn">Sign up</a></li>
                            {% else %}
                                {% if myShopId is defined %}
                                    <li style="margin-top: 0;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
                                                <span>Your Shop</span>{% if totalUnshippedItems > 0 %} <font class="items">{{ totalUnshippedItems|pretty }}</font>{% endif %}</i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
                                                <li class="divider"></li>
                                                <li><a href="/shop/module/marketplace/addproduct?shop={{ myShopId }}"><i class="fa fa-fw fa-plus"></i> Add Product</a></li>
                                                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}&l=3"><i class="fa fa-fw fa-list"></i> Product List</a></li>
                                                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}&l=4"><i class="fa fa-fw fa-tasks"></i> Orders{%if totalUnshippedItems > 0 %} <font class="items pull-right">{{ totalUnshippedItems|pretty }}</font>{% endif %}</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{ url('shops/my/customize') }}"><i class="fa fa-fw fa-pencil"></i> Customize Shop</a></li>
                                                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}&l=2&edit-profile=1"><i class="fa fa-fw fa-gears"></i> Shop Profile</a></li>
                                                <li><a href="{{ url('shops/' ~ auth['ik']) }}"><i class="fa fa-fw fa-eye"></i> View Shop</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                {% endif %}

                                <li style="margin-top: 0;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-user dropdown-toggle circular" data-toggle="dropdown">
                                            <span>
                                            {% if impersonating %}
                                                <font class="items">{{ auth['user_name'] }}</font>
                                            {% else %}
                                                You
                                            {% endif %}
                                            </span>
                                            <i {% if auth['impersonating'] %}style="color:#85c125;" {% endif %}class="fa fa-user"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                            {% if actualRole == 'Admins' or actualRole == 'Moderators' %}
                                                {% if impersonating %}
                                                    <li><a href="{{ url('profile/impersonate/end') }}"><i class="fa fa-fw fa-minus-circle"></i> End Impersonation</a></li>
                                                {% else %}
                                                    <li><a href="{{ url('profile/impersonate') }}"><i class="fa fa-fw fa-paper-plane-o"></i> Impersonate</a></li>
                                                {% endif %}
                                                <li class="divider"></li>
                                            {% endif %}
                                            <li><a href="{{ url('shop/order-history') }}"><i class="fa fa-fw fa-tags"></i> Purchases</a></li>
                                            <li class="divider"></li>
                                            <li><a href="{{ url('profile/view/' ~ auth['ik']) }}"><i class="fa fa-fw fa-user"></i> Profile</a></li>
                                            <li><a href="{{ url('profile/settings') }}"><i class="fa fa-fw fa-gears"></i> Account</a></li>
                                            <li><a href="{{ url('profile/messages') }}"><i class="fa fa-fw fa-envelope"{%if unread > 0 %}style="color: orange;"{% endif %}></i> Messages{% if unread > 0 %} ({{ unread }}){% endif %}</a></li>
                                            <li><a href="{{ url('profile/feed') }}"><i class="fa fa-fw fa-rss"></i> Feed</a></li>
                                            <li class="divider"></li>
                                            <li><a href="{{ url('profile/logout') }}"><i class="fa fa-fw fa-sign-out"></i> Sign Out</a></li>
                                        </ul>
                                    </div>
                                </li>
                            {% endif %}
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
                                        <li><a href="{{ url('browse/shops') }}">View shop gallery</a>
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
                                    <a href="https://www.facebook.com/upmodinc" target="_blank"><i class="fa fa-facebook-square"></i></a>
                                    <a href="https://www.twitter.com/upmodinc" target="_blank"><i class="fa fa-twitter-square"></i></a>
                                    <a href="https://www.pinterest.com/upmodinc" target="_blank"><i class="fa fa-pinterest-square"></i></a>
                                    <a href="https://plus.google.com/+upmodinc" target="_blank"><i class="fa fa-google-plus-square"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
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
                            </ul>
                        </div>

                        <div class="right_menu">
                            <a class="menu-toggle">See all categories</a>
                        </div>
                    </div>
                </div>
            </nav>
            {% if isIndexPage is defined %}
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
            {% elseif page_title_text is defined %}
                <section>
                    <div class="container">
                        <div class="row">
                            <div class="page_title_text">
                                {{ page_title_text }}
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