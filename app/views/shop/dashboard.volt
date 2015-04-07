{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <font class="btn btn-blue">{{ shop_name }}</font>
            <a class="btn btn-gray" href="http://www.upcyclepost.com/shops/{{ shop_url }}">http://www.upcyclepost.com/shops/{{ shop_url }}</a>
        </div>
        <div class="login-panel-header">
            <a class="btn btn-green" href="{{ url('shop/get-paid') }}">Payment Settings</a>
            <a class="btn btn-gray" href="{{ url('shop/settings') }}">Shop Settings</a>
            {% if can_list_items %}
            <a class="btn btn-gray" href="{{ url('shop/list') }}">List an Item</a>
            {% endif %}
            <a class="btn btn-blue" href="{{ url('profile/messages') }}" style="float: right">Private Messages</a>
        </div>
    </div>

    {% for item in to_ship %}
        {% if loop.first %}
        <div class="login-panel">
                <div class="login-panel-header">
                    <h1>New sales to ship</h1>
                </div>
                <form role="form" class="form-horizontal">
                <div class="login-panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 control-label">Item</label>
                        <label class="col-xs-12 col-sm-3 control-label">Sale Date</label>
                        <label class="col-xs-12 col-sm-2 control-label">Sold To</label>
                        <label class="col-xs-8 col-sm-2 control-label">Amount</label>
                        <label class="col-xs-8 col-sm-2 control-label">Actions</label>
                    </div>
        {% endif %}
        <div class="form-group">
            <div class="col-xs-12 col-sm-3"><a href="{{ item.Post.url() }}">{{ item.Post.title }}</a></div>
            <div class="col-xs-12 col-sm-3">{{ item.sold_at|formatDate }}</div>
            <div class="col-xs-12 col-sm-2"><a href="{{ url('profile/view/') ~ item.User.ik }}">{{ item.User.name }}</a></div>
            <div class="col-xs-8 col-sm-2">${{ item.total_amount|pretty }}</div>
            <div class="col-xs-8 col-sm-2"><a href="{{ url('shop/ship/' ~ item.post_ik) }}">I've Shipped It!</a></div>
        </div>

        {% if loop.last %}
                    </div>
                </form>
            </div>
        {% endif %}
    {% endfor %}

    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Listings</h1>
        </div>

        {% if !listings %}
        <div class="login-panel-body">
            {% if total_items_listed is 0 %}
            <h3>Get started by <a href="{{ url('shop/list') }}">listing your first item</a>.</h3>
            {% else %}
            <h3>Looks like everything has sold, time to <a href="{{ url('shop/list') }}">list a new item</a>.</h3>
            {% endif %}
        </div>
        {% else %}
        <form role="form" class="form-horizontal">
        <div class="login-panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 control-label">Item</label>
                <label class="col-xs-12 col-sm-3 control-label">List Date</label>
                <label class="col-xs-8 col-sm-2 control-label">Views</label>
                <label class="col-xs-8 col-sm-2 control-label">Price</label>
                <label class="col-xs-8 col-sm-2 control-label">Actions</label>
            </div>
            {% for item in listings %}
                {% if item.deleted is 0 %}
                <div class="form-group">
                    <div class="col-xs-12 col-sm-3"><a href="{{ item.Post.url() }}">{{ item.Post.title }}</a></div>
                    <div class="col-xs-12 col-sm-3">{{ item.listed_at|formatDate }}</div>
                    <div class="col-xs-8 col-sm-2">{{ item.Post.views|pretty }}</div>
                    <div class="col-xs-8 col-sm-2">${{ item.price|pretty }}</div>
                    <div class="col-xs-8 col-sm-2"><a href="{{ url('shop/listing/edit/' ~ item.post_ik) }}">Edit</a> &nbsp; <a href="{{ url('shop/listing/delete/' ~ item.post_ik) }}" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a></div>
                </div>
                {% endif %}
            {% endfor %}
        </div>
        </form>
        {% endif %}
    </div>

    <div class="login-panel">
        <div class="login-panel-header">
            <h4>Balance</h4>
        </div>
        <div class="login-panel-header">
            <div>We send you your sales revenue each Monday.  The next scheduled transfer date is <strong>{{ next_transfer_date }}</strong>.</div>
            <br />
            <div>
                <label class="control-label">Unpaid Balance</label>: ${{ balance|pretty }}
            </div>

            <div>
                <label class="control-label">Last Paid</label>: {{ last_payment_sent }}
            </div>
        </div>
    </div>

    <div class="login-panel">
        <div class="login-panel-header">
            <h4>Stats</h4>
        </div>
        <div class="login-panel-header">
            <div>
                <label class="control-label">Current Listings</label>: {{ items_listed|pretty }}
            </div>

            <div>
                <label class="control-label">Total Listings</label>: {{ total_items_listed|pretty }}
            </div>

            <div>
                <label class="control-label">Total Sales</label>: {{ items_sold|pretty }}
            </div>

            <div>
                <label class="control-label">Total Revenue</label>: ${{ revenue|pretty }}
            </div>
        </div>
    </div>
</div>