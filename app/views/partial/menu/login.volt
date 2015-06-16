{% if !isLoggedIn %}
        <a href="{{ url('profile/login') }}" class="btn"><i class="fa fa-sign-in fa-fw"></i> Sign In</a>
        <a href="{{ url('profile/register') }}" class="btn"><i class="fa fa-sign-in fa-fw fa-rotate-270"></i> Sign Up</a>
{% endif %}

<div class="btn-group">
    <button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
        <span>Browse</span> <i class="fa fa-camera"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        <li><a href="{{ url('shops') }}"><i class="fa up-shop-1"></i> Shops</a></li>
        <li><a href="{{ url('gallery') }}"><i class="fa fa-fw fa-lightbulb-o"></i> Ideas</a></li>
        <li><a href="{{ url('search/users') }}"><i class="fa fa-fw fa-users"></i> Users</a></li>
    </ul>
</div>

{% if isLoggedIn %}
    {% if myShopId is defined %}
        <div class="btn-group">
            <button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
                <span>Your Shop{% if totalUnshippedItems > 0 %} <font class="items">{{ totalUnshippedItems|pretty }}</font>{% endif %}</span> <i class="fa up-shop-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
                <li class="divider"></li>
                <li><a href="/shop/module/marketplace/addproduct?shop={{ myShopId }}"><i class="fa fa-fw fa-plus"></i> Add Product</a></li>
                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}&l=3"><i class="fa fa-fw fa-list"></i> Product List</a></li>
                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}&l=4"><i class="fa fa-fw fa-tasks"></i> Orders{%if totalUnshippedItems > 0 %} <font class="items pull-right">{{ totalUnshippedItems|pretty }}</font>{% endif %}</a></li>
                <li class="divider"></li>
                <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}&l=2&edit-profile=1"><i class="fa fa-fw fa-gears"></i> Shop Profile</a></li>
                <li><a href="{{ url('shops/' ~ auth['ik']) }}"><i class="fa fa-fw fa-eye"></i> View Shop</a></li>
            </ul>
        </div>
    {% endif %}

    <div class="btn-group">
        <button type="button" class="btn btn-user dropdown-toggle circular" data-toggle="dropdown">
            <span>
            {% if auth['impersonating'] %}
                <font class="items">{{ auth['user_name'] }}</font>
            {% else %}
                You
            {% endif %}
            </span>
            <i {% if auth['impersonating'] %}style="color:#85c125;" {% endif %}class="fa fa-user"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
            {% if actualRole == 'Admins' or actualRole == 'Moderators' %}
                {% if auth['impersonating'] %}
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
{% endif %}

{% if ps_Available %}
<div class="pull-right text-center cart">
    <a style="margin: 0; padding: 0" href="{{ url('shop/quick-order') }}"><i class="fa fa-fw fa-shopping-cart"></i><br>Cart{% if totalCartItems > 0 %}<span class="items">{{ totalCartItems|pretty }}</span>{% endif %}</a>
</div>
{% endif %}