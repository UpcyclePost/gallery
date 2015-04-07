<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <font class="btn btn-blue">{{ shop_name }}</font>
            <a class="btn btn-gray" href="http://www.upcyclepost.com/shops/{{ shop_url }}">http://www.upcyclepost.com/shops/{{ shop_url }}</a>
            <a class="btn btn-blue" href="{{ url('profile/messages/send/') ~ shop_user_ik }}" style="float: right">Send {{ shop_name }} a messages</a>
        </div>
        <div class="login-panel-header">
            Located in {{ shop_location }}
        </div>
    </div>

    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Items for Sale<h1>
        </div>
        <div class="login-panel-body">
        {% if results %}
            <form class="form-horizontal" role="form">
                {% set isodiv = "iso" %}
                {{ partial('partial/gallery/list') }}
            </form>
        {% else %}
            <h3>Looks like {{ shop_name }} has no items available right now.</h3>
        {% endif %}
        </div>
    </div>
</div>