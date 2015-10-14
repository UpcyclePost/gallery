{% if profile.Shop.background is defined %}
    <style type="text/css">
        .header_area.subpages {
            background:transparent!important;
        }

        body {
            background: url('{{ profile.Shop.backgroundUrl() }}')!important;
            background-size: cover!important;
            background-attachment: fixed!important;
            background-repeat: no-repeat!important;
            background-position: center center!important;
        }

        #content-main {
            min-height: 700px;
        }
    </style>
{% elseif custom_background is defined %}
    <style type="text/css">
        .header_area.subpages {
            background:transparent!important;
        }

        body {
            background: url('{{ profile.backgroundUrl() }}') #fff no-repeat center top fixed !important;
            background-size: cover!important;
            background-attachment: fixed!important;
            background-repeat: no-repeat!important;
            background-position: center center!important;
        }

        #content-main {
            min-height: 700px;
        }
    </style>
{% endif %}

<div class="content-container">
    {% if profile.Shop.logo %}
        <br><br>
        <div>
            <img src="{{ profile.Shop.logoUrl() }}" width="400" height="100">
        </div>
        <br><br>
    {% endif %}

    <div class="login-panel semi-opaque-7">
        <div class="login-panel-body">
            {% if displayShopNotVisibleMessage %}
                <div class="alert alert-danger">Your shop will appear in the <a href="{{ url('browse/shops') }}">Shop Gallery</a> after you have loaded at least three products.</div>
            {% endif %}

            <h1>About {{ shopName }}</h1>
            {% if profile.Shop.description is defined and profile.Shop.description and profile.Shop.description|length > 0 %}
                {{ profile.Shop.description }}
            {% else %}
                {{ shopAbout }}
            {% endif %}
            <br><br>
            <div class="col-lg-2 pull-right text-right">
                <i class="fa fa-envelope"></i> <a href="{{ url('profile/messages/send/') ~ profile.ik }}">Contact</a>
            </div>
            <div class="col-lg-2 pull-right text-right">
                <i class="fa fa-user"></i> <a href="{{ url('profile/view/') ~ profile.ik }}">Profile</a>
            </div>
            <br clear="all">
        </div>
        <div class="login-panel-footer text-right">
            <span class='st_facebook'></span>
            <span class='st_twitter'></span>
            <span class='st_googleplus'></span>
            <span class='st_pinterest'></span>
        </div>
    </div>

    <?php
    $has_results = (count($results) > 0);
    ?>
    {% if has_results %}
        <br clear="both">
        <div class="unique_area semi-opaque-7" style="margin-top:0;padding-top:10px;padding-bottom:10px;">
        <div class="content-container">
            <div class="unik_text" style="margin-bottom:0;">
                <h2>Products</h2>
            </div>
            <div class="login-panel-body">
                <form class="form-horizontal" role="form">
                    {{ partial('partial/gallery/list') }}
                </form>
            </div>
        </div>
        </div>
    {% endif %}

</div>