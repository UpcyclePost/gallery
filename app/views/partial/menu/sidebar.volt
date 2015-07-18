<div class="sidebar-panel">
    <div class="sidebar-panel-header">
        <h5>Do you Upcycle?</h5>
    </div>

    {% if isLoggedIn and myShopId is not defined %}
    <div class="sidebar-panel-header clearfix text-center">
    <a class="btn btn-green" href="{{ url('shop/module/marketplace/sellerrequest') }}">
        <i class="fa fa-fw fa-shopping-cart"></i>
        Create a Shop
    </a>
    {% elseif isLoggedIn and myShopId is defined %}
    <div class="sidebar-panel-header clearfix text-center">
    <a class="btn btn-green" href="{{ url('shops/' ~ auth['ik']) }}">
        <i class="fa fa-fw fa-eye"></i>
        View your Shop
    </a>
    {% else %}
    <div class="sidebar-panel-header clearfix text-center">
    <a class="btn btn-green" href="{{ url('profile/edit') }}">
        <i class="fa fa-fw fa-shopping-cart"></i>
        Create a Shop
    </a>
    {% endif %}
    </div>
</div>

<div class="sidebar-panel" style="margin-top: 35px;">
    <div class="sidebar-panel-header">
        <h5>Categories</h5>
    </div>
    <div class="sidebar-panel-body clearfix">
        <?php $categories = Helpers::getCategoryList(); ?>
        <ul class="cat-list left-col">
        {% for _category in categories %}
            {% if loop.index0 is 12 %}
                </ul><ul class="cat-list right-col">
            {% endif %}
            <li><a href="{{ url('gallery/' ~ _category['title']|url) }}">{{ _category['title'] }}</a></li>
        {% endfor %}
        </ul>
    </div>

</div>

{% if sidebarCMSBlock is defined %}
    <div class="sidebar-panel" style="margin-top: 35px;">
        <div class="sidebar-panel-header">
            <h5>{{ sidebarCMSBlock['title'] }}</h5>
        </div>
        <div class="sidebar-panel-body clearfix">
            {{ sidebarCMSBlock['content'] }}
        </div>
    </div>
{% endif %}
