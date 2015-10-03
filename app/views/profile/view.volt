<div class="content-container">
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "0919549b-9f77-444b-bd9a-4c8683b78c51", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

    {% if profile.custom_background %}
        <style type="text/css">
            .header_area.subpages {
                background:transparent!important;
            }

            body {
                background: url('{{ profile.backgroundUrl() }}')!important;
                -webkit-background-size: cover!important;
                -moz-background-size: cover!important;
                -o-background-size: cover!important;
                background-size: cover!important;
            }

            #content-main {
                min-height: 700px;
            }
        </style>
    {% endif %}

    {{ content() }}
    <?php
        $split = explode(" ", $profile->name);
        $name = $split[0];
    ?>

    <div class="account-settings-container profile-view-container">
        <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12" style="padding:0; padding-right:20px;">
            <div class="login-panel">
                <div class="login-panel-header">
                    <div class="row text-center profile-image circular">
                        <img src="{{ profile.avatarUrl() }}" height="100" width="100">
                    </div>
                    {% if isLoggedIn %}
                        <div style="float: right; display: inline-block">
                        {% if profile.ik != auth['ik'] %}
                            <a class="btn btn-gray" href="{{ url('profile/messages/send/') ~ profile.ik }}"><i class="fa fa-envelope icon-only"></i></a>
                            <a class="follow btn btn-green" data-url="{{ url('follow/user/') ~ profile.ik }}">
                                <i class="fa {% if following %}fa-check{%else%}fa-plus{% endif %}"></i>
                                {% if following %}Following{%else%}Follow{% endif %}
                            </a>
                        {% else %}
                            <a class="btn btn-green" href="{{ url('profile/edit') }}">
                                <i class="fa fa-edit"></i>
                                Edit Your Profile
                            </a>
                        {% endif %}
                        </div>
                    {% endif %}
                    <h1>{{ profile.user_name }}</h1>
                    {{ profile.location }}
                </div>
            </div>

            <div class="login-panel">
                <div class="login-panel-header">
                    {% if isOwnProfile and myShopId is not defined %}
                        <div style="float: right; display: inline-block; padding-bottom: 3px;">
                            <a class="btn btn-green" href="{{ url('shop/module/marketplace/sellerrequest') }}">
                                <i class="fa fa-fw fa-shopping-cart"></i>
                                Create your shop
                            </a>
                        </div>
                    {% endif %}
                    <h1>About</h1>
                </div>
                <div class="login-panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group" style="word-break: break-word;">
                            <?=nl2br($profile->about)?>
                        </div>
                    </form>
                </div>
                <div class="login-panel-footer text-right">
                    <div class="hidden-xs hidden-sm">
                        <span class='st_facebook_large'></span>
                        <span class='st_twitter_large'></span>
                        <span class='st_linkedin_large'></span>
                        <span class='st_googleplus_large'></span>
                        <span class='st_pinterest_large'></span>
                        <span class='st_stumbleupon_large'></span>
                        <span class='st_email_large'></span>
                        <span class='st_sharethis_large'></span>
                    </div>
                    <div class="hidden-lg hidden-md">
                        <span class='st_facebook'></span>
                        <span class='st_twitter'></span>
                        <span class='st_linkedin'></span>
                        <span class='st_googleplus'></span>
                        <span class='st_pinterest'></span>
                        <span class='st_stumbleupon'></span>
                        <span class='st_email'></span>
                        <span class='st_sharethis'></span>
                    </div>
                </div>
            </div>

            {% if websites|length > 0 %}
            <div class="login-panel">
                <div class="login-panel-header">
                    <h1>Find me on</h1>
                </div>
                <div class="login-panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            {% for index, website in websites %}
                                <?php
                                    if (substr($website['url'], 0, 4) != 'http')
                                    {
                                        $website['url'] = sprintf('http://%s', $website['url']);
                                    }
                                ?>
                                <div>
                                <a href="{{ website['url'] }}" target="_blank">
                                <div class="col-lg-3">
                                {% if website['type'] == 'twitter' %}
                                    <i class="fa fa-fw fa-twitter"></i> Twitter
                                {% elseif website['type'] == 'facebook' %}
                                    <i class="fa fa-fw fa-facebook"></i> Facebook
                                {% elseif website['type'] == 'pinterest' %}
                                    <i class="fa fa-fw fa-pinterest-p"></i> Pinterest
                                {% else %}
                                    <i class="fa fa-fw fa-external-link"></i> My Website
                                {% endif %}
                                </div>
                                <div class="col-lg-8">
                                {{ website['url'] }}
                                </div>
                                <br clear="all">
                                </a>
                                </div>
                            {% endfor %}
                        </div>
                    </form>
                </div>
            </div>
            {% endif %}

        </div>
        {% if shop_results[0] is defined %}
            <div class="col-sm-4 col-lg-4 col-xs-12">
                <div class="login-panel">
                    <div class="login-panel-header">
                        <h1><a href="{{ profile.shopUrl() }}">{{ profile.user_name }}'s shop</a></h1>
                    </div>
                    <div class="login-panel-body">
                        <div id="shop-iso-container">
                            <div id="shop-iso">
                                {% for item in shop_results %}
                                    <div class="col-lg-4">
                                        <article style="margin-bottom: 20px;">
                                            <a href="{{ item['url'] }}"><img border="0" src="{{ item['image'] }}" style="max-height: 60px; max-width: 80px;"></a>
                                        </article>
                                    </div>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    <div class="login-panel-footer text-right">
                        <a href="{{ profile.shopUrl() }}">Go to Shop</a>
                    </div>
                </div>
            </div>
        {% endif %}
    </div>

    <?php
    $has_results = (count($results) > 0);
    ?>
    {% if has_results %}
        <br clear="both">
        <div class="unique_area semi-opaque-7" style="margin-top:0;padding-top:10px;padding-bottom:10px;">
        <div class="content-container">
            <div class="unik_text" style="margin-bottom:0;">
                <h2>{{ profile.user_name }}'s Board</h2>
                {% if isLoggedIn and profile.ik == auth['ik'] %}
                    <div style="float: right; margin-top: -25px;" class="hidden-xs">
                        <a href="{{ url('post/idea') }}" class="btn btn-green"><i class="fa fa-camera"></i> Post Ideas</a>
                    </div>
                {% endif %}
            </div>
            <div class="login-panel-body">
                <form class="form-horizontal" role="form">
                    {% set isodiv = "iso" %}
                    {% set deleteable = true %}
                    {{ partial('partial/gallery/list') }}
                </form>
            </div>
        </div>
        </div>
    {% endif %}
</div>