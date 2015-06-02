{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <font class="btn btn-blue">{{ name }}</font>
            <a class="btn btn-gray" href="http://www.upcyclepost.com/profile/view/{{ auth['ik'] }}">Visit my profile</a>
        </div>
        <div class="login-panel-header">
            <a class="btn btn-gray" href="{{ url('profile/settings') }}">Settings</a>
            <a class="btn btn-blue" href="{{ url('profile/messages') }}" style="float: right">Private Messages</a>
            <br />
        </div>
    </div>

    <div class="login-panel">
        <div class="login-panel-header">
            <h1>What's new</h1>
        </div>
        <div class="login-panel-body">
        {{ partial('partial/profile/feed') }}
        </div>
    </div>


    <div class="login-panel">
        <div class="login-panel-header">
            <h4>Purchases</h4>
        </div>

        {% if !purchases %}
        <div class="login-panel-body">
            <h2>Looks like you haven't bought anything yet.</h2>
        </div>
        {% else %}
        <form role="form" class="form-horizontal">
        <div class="login-panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 control-label">Item</label>
                <label class="col-xs-12 col-sm-3 control-label">Date</label>
                <label class="col-xs-8 col-sm-2 control-label">Sold By</label>
                <label class="col-xs-8 col-sm-2 control-label">Amount</label>
                <label class="col-xs-8 col-sm-2 control-label">Status</label>
            </div>
            {% for item in purchases %}
                <div class="form-group">
                    <div class="col-xs-12 col-sm-3"><a href="{{ item.Post.url() }}">{{ item.Post.title }}</a></div>
                    <div class="col-xs-12 col-sm-3">{{ item.sold_at|formatDate }}</div>
                    <div class="col-xs-8 col-sm-2"><a href="{{ url('shops/' ~ item.Shop.url) }}">{{ item.Shop.name }}</a></div>
                    <div class="col-xs-8 col-sm-2">${{ item.total_amount|pretty }}</div>
                    <div class="col-xs-8 col-sm-2">{% if item.shipped is 1 %}Shipped{% else %}Not Shipped{% endif %}</div>
                </div>
            {% endfor %}
        </div>
        </form>
        {% endif %}
    </div>

</div>