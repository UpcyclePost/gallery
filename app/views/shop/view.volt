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
{% endif %}

<div class="content-container">
    {% if profile.Shop.logo %}
        <div class="text-center">
            <img class="text-center" src="{{ profile.Shop.logoUrl() }}" width="400" height="100">
        </div>
        <br><br>
    {% endif %}

    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="login-panel semi-opaque-7">
            <div class="login-panel-header">
                <h1>{{ shopName }}</h1>
            </div>
            <div class="login-panel-body">
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
                <div class="row">
                    {{ profile.Shop.description }}
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