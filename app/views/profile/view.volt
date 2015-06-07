<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "0919549b-9f77-444b-bd9a-4c8683b78c51", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

{% if profile.custom_background %}
    <style type="text/css">
        #page {
            background: url('{{ profile.backgroundUrl() }}') #fff no-repeat center top fixed !important;
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
    <div class="col-sm-7 col-md-7 col-lg-4 col-xs-12">
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
                    <div class="form-group">
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
        {% if profile.twitter is not '' or profile.facebook is not '' or shop_results[0] is defined or isOwnProfile %}
        <div class="login-panel">
            <div class="login-panel-header">
                <h1>Find me on</h1>
            </div>
            <div class="login-panel-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        {% if shop_results[0] is defined %}
                            <h4><a href="{{ profile.shopUrl() }}">UpcyclePost</a></h4>
                        {% endif %}
                        {% if profile.twitter is not '' %}
                            <a href="{{ profile.twitter }}" target="_blank">Twitter</a>
                            <br />
                        {% endif %}
                        {% if profile.facebook is not '' %}
                            <a href="{{ profile.facebook }}" target="_blank">Facebook</a>
                        {% endif %}
                    </div>
                </form>
            </div>
        </div>
        {% endif %}
    </div>
    {% if shop_results[0] is defined %}
        <div class="col-sm-4 col-lg-2 col-xs-12">
            <div class="login-panel">
                <div class="login-panel-header">
                    <h1><a href="{{ profile.shopUrl() }}">{{ profile.user_name }}'s shop</a></h1>
                </div>
                <div class="login-panel-body">
                    <div id="shop-iso-container">
                        <div id="shop-iso">
                            {% for item in shop_results %}
                                <article style="margin-bottom: 20px;">
                                    <a href="{{ item['url'] }}"><img border="0" src="{{ item['image'] }}" style="max-height: 60px; max-width: 80px;"></a>
                                </article>
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
    <div class="login-panel full-screen profile-board">
        <div class="login-panel-header">
            <center><h1>{{ profile.user_name }}'s Board</h1></center>
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
{% endif %}