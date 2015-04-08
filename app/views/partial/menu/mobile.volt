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
        <li><a href="{{ url('') }}"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="{{ url('post/idea') }}"><i class="fa fa-camera"></i> Post Ideas</a></li>
        <li><a href="{{ url('gallery') }}"><i class="fa fa-search"></i> Find Inspiration</a>
            <ul>
                {% for _category in categories %}
                <li><a href="{{ url('gallery/' ~ _category['title']|url) }}">{{ _category['title'] }}</a></li>
                {% endfor %}
            </ul>
        </li>
        <li><a href="{{ url('search/users') }}"><i class="fa fa-users"></i> Profile Gallery</a></li>
        {% if !isLoggedIn %}
        <li><a href="{{ url('profile/login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
        <li><a href="{{ url('profile/register') }}"><i class="fa fa-user"></i> Signup</a></li>
        {% else %}
        <li><a href="{{ url('profile/view/' ~ auth['ik']) }}"><i class="fa fa-user"></i> Profile</a></li>
        <li><a href="{{ url('profile/settings') }}"><i class="fa fa-gears"></i> Account</a></li>
        <li><a href="{{ url('profile/messages') }}"><i class="fa fa-envelope"{%if unread > 0 %}style="color: orange;"{% endif %}></i> Messages{% if unread > 0 %} ({{ unread }}){% endif %}</a></li>
        <li><a href="{{ url('profile/feed') }}"><i class="fa fa-rss"></i> Feed</a></li>
        <li class="divider"></li>
        <li><a href="{{ url('profile/logout') }}"><i class="fa fa-sign-out"></i> Log Out</a></li>
        {% endif %}
        <li><a href="{{ url('blog') }}"><i class="fa fa-rss"></i> Blog</a></li>
    </ul>

</nav>