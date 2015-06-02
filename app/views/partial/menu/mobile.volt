<!-- Mobile Footer -->
<nav class="mobile-footer mm-fixed-bottom hidden-lg hidden-md">
    <ul class="clearfix">
        <li><a href="{{ url('') }}"><i class="fa fa-home"></i><span>Home</span></a></li>
        <li><a href="{{ url('post/idea') }}"><i class="fa fa-camera"></i><span>Post Idea</span></a></li>
        {% if !isLoggedIn %}
            <li><a href="{{ url('profile/login') }}"><i class="fa fa-sign-in"></i><span>Login</span></a></li>
            <li><a href="{{ url('profile/login') }}"><i class="fa fa-user"></i><span>Signup</span></a></li>
        {% else %}
            <li><a href="{{ url('profile/view/' ~ auth['ik']) }}"><i class="fa fa-user"></i><span>Profile</span></a></li>
            <li><a href="{{ url('profile/feed') }}"><i class="fa fa-rss"></i><span>Feed</span></a></li>
        {% endif %}
    </ul>
</nav>

<!-- Mobile Search Modal -->
<div class="search modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="mobile-search clearfix">
        <form method="post" action="{{ url('gallery') }}">
            <input type="search" name="term" class="form-control" id="mobilesearch" placeholder="Search UpcyclePost">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        </form>
    </div>
</div>

<!-- Mobile Slide Menu -->
<nav id="mobile-slide-menu" class="hidden">
    <ul>
        <li><a href="{{ url('') }}"><i class="fa fa-home fa-fw"></i> Home</a></li>
        <li><a href="{{ url('post/idea') }}"><i class="fa fa-camera fa-fw"></i> Post Ideas</a></li>
        <li><a href="{{ url('gallery') }}"><i class="fa fa-search fa-fw"></i> Find Inspiration</a>
            <ul>
                {% for _category in categories %}
                <li><a href="{{ url('gallery/' ~ _category['title']|url) }}">{{ _category['title'] }}</a></li>
                {% endfor %}
            </ul>
        </li>
        <li><a href="{{ url('gallery') }}"><i class="fa fa-camera fa-fw"></i> Browse</a>
            <ul>
                <li><a href="{{ url('gallery') }}"><i class="fa fa-fw fa-lightbulb-o"></i> Ideas</a></li>
                <li><a href="{{ url('shops') }}"><i class="fa fa-fw fa-tags"></i> Shops</a></li>
                <li><a href="{{ url('search/users') }}"><i class="fa fa-fw fa-users"></i> Users</a></li>
            </ul>
        </li>
        {% if !isLoggedIn %}
            <li><a href="{{ url('profile/login') }}"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
            <li><a href="{{ url('profile/register') }}"><i class="fa fa-sign-in fa-rotate-270 fa-fw"></i> Sign Up</a></li>
        {% else %}
            {% if myShopId is defined %}
                <li><a href="{{ url('shops/' ~ auth['user_name']) }}">Your Shop</a>
                    <ul>
                        <li><a href="/shop/module/marketplace/marketplaceaccount?shop={{ myShopId }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
                        <li class="divider"></li>
                        <li><a href="/shop/module/marketplace/addproduct?shop={{ myShopId }}"><i class="fa fa-fw fa-plus"></i> Add Product</a></li>
                    </ul>
                </li>
            {% endif %}
            <li><a href="{{ url('profile/view/' ~ auth['ik']) }}">You</a>
                <ul>
                    <li><a href="{{ url('shop/order-history') }}">Purchases</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('profile/settings') }}"><i class="fa fa-fw fa-gears"></i> Account</a></li>
                    <li><a href="{{ url('profile/messages') }}"><i class="fa fa-fw fa-envelope"{%if unread > 0 %}style="color: orange;"{% endif %}></i> Messages{% if unread > 0 %} ({{ unread }}){% endif %}</a></li>
                    <li><a href="{{ url('profile/feed') }}"><i class="fa fa-fw fa-rss"></i> Feed</a></li>
                </ul>
            </li>
            <li class="divider"></li>
        {% endif %}
        <li><a href="{{ url('blog') }}"><i class="fa fa-rss fa-fw"></i> Blog</a></li>

        {% if isLoggedIn %}
            <li><a href="{{ url('profile/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Sign Out</a></li>
        {% endif %}
    </ul>

</nav>