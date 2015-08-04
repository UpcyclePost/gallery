{% if profile.Shop.background is defined %}
    <style type="text/css">
        #page {
            background: url('{{ profile.Shop.backgroundUrl() }}') #fff no-repeat center top fixed !important;
            -webkit-background-size: cover!important;
            -moz-background-size: cover!important;
            -o-background-size: cover!important;
            background-size: cover!important;
        }

        #content-main {
            min-height: 700px;
        }
    </style>
{% elseif custom_background is defined %}
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

<div class="content-container">
    {% if profile.Shop.logo %}
        <div style="margin-left: 15px;">
            <img src="{{ profile.Shop.logoUrl() }}" width="400" height="100">
        </div>
        <br><br>
    {% endif %}

    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="login-panel semi-opaque-7">
            <div class="login-panel-header">
                <h1>{{ shopName }}</h1>
            </div>
            <div class="login-panel-body">
                {% if displayShopNotVisibleMessage %}
                    <div class="alert alert-danger">Your shop will appear in the <a href="{{ url('shops') }}">Shop Gallery</a> after you have loaded at least three products.</div>
                {% endif %}

                {% set offsetTop = 88 %}
                {% set isodiv = "iso" %}
                {{ partial('partial/gallery/list') }}
            </div>
            <br clear="all">
            <div class="login-panel-footer text-right">
                <a href="{{ url('shops') }}"><i class="fa fa-angle-double-right"></i> Continue shopping on UpcyclePost</a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
        <div class="login-panel semi-opaque-7">
            <div class="login-panel-header">
                <h1>About {{ shopName }}</h1>
            </div>
            <div class="login-panel-body">
                <div class="row text-center profile-image circular">
                    <img src="{{ profile.avatarUrl() }}" height="100" width="100">
                </div>
                <div class="row">
                    {% if profile.Shop.description is defined and profile.Shop.description and profile.Shop.description|length > 0 %}
                        {{ profile.Shop.description }}
                    {% else %}
                        {{ shopAbout }}
                    {% endif %}
                </div>
                <div class="row">

                </div>
            </div>
            <div class="login-panel-footer">
                <div>
                    <div class="col-lg-4 pull-right text-right">
                        <i class="fa fa-envelope"></i> <a href="{{ url('profile/messages/send/') ~ profile.ik }}">Contact</a>
                    </div>
                    <div class="col-lg-4 pull-right text-right">
                        <i class="fa fa-user"></i> <a href="{{ url('profile/view/') ~ profile.ik }}">Profile</a>
                    </div>
                </div>
                <br clear="all">
            </div>
        </div>
    </div>
</div>