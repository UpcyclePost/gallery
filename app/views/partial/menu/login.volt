{% if !isLoggedIn %}
        <a href="{{ url('profile/login') }}" class="btn btn-blue"><i class="fa fa-sign-in"></i> Sign In</a>
        <a href="{{ url('profile/register') }}" class="btn btn-blue"><i class="fa fa-user"></i> Sign Up</a>
        <a href="{{ url('blog') }}" class="btn btn-blue hidden-md"><i class="fa fa-rss"></i> Blog</a>
{% else %}
        <a href="{{ url('post/idea') }}" class="btn btn-green"><i class="fa fa-camera"></i> Post Ideas</a>
        <a href="{{ url('blog') }}" class="btn btn-blue hidden-md"><i class="fa fa-rss"></i> Blog</a>
        <div class="btn-group">
            <button type="button" class="btn btn-user dropdown-toggle" data-toggle="dropdown">
                <span>You</span> <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="{{ url('profile/view/' ~ auth['ik']) }}"><i class="fa fa-user"></i> Profile</a></li>
                <li><a href="{{ url('profile/settings') }}"><i class="fa fa-gears"></i> Account</a></li>
                <li><a href="{{ url('profile/messages') }}"><i class="fa fa-envelope"{%if unread > 0 %}style="color: orange;"{% endif %}></i> Messages{% if unread > 0 %} ({{ unread }}){% endif %}</a></li>
                <li><a href="{{ url('profile/feed') }}"><i class="fa fa-rss"></i> Feed</a></li>
                <li class="divider"></li>
                <li><a href="{{ url('profile/logout') }}"><i class="fa fa-sign-out"></i> Log Out</a></li>
            </ul>
        </div>
{% endif %}