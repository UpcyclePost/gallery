{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <div style="float: left;">
                <h1>Your Feed</h1>
            </div>
            <div style="float: left;">
                <nav class="slug tabs clearfix feed-menu">
                    <li><a href="{{ url('profile/feed/following') }}" class="btn {%if viewing == 'following' %}btn-blue{% else %}btn-gray{% endif %}">Following</a></li>
                    <li><a href="{{ url('profile/feed/followers') }}" class="btn {%if viewing == 'followers' %}btn-blue{% else %}btn-gray{% endif %}">Followers</a></li>
                    <li><a href="{{ url('profile/feed/messages') }}" class="btn {%if viewing == 'messages' %}btn-blue{% else %}btn-gray{% endif %}">Messages</a></li>
                    <li><a href="{{ url('profile/feed') }}" class="btn {%if viewing == 'everything' %}btn-blue{% else %}btn-gray{% endif %}">Everything</a></li>
                </nav>
            </div>
            <br clear="all" />
        </div>
        <div class="login-panel-body">
        {{ partial('partial/profile/feed') }}
        </div>
    </div>
</div>