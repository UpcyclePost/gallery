<div class="sidebar-panel">
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

    {% if sidebarCMSBlock is defined %}
    <div class="sidebar-panel-body clearfix">
        {{ sidebarCMSBlock }}
    </div>
    {% endif %}

    <div class="sidebar-panel-header clearfix text-center">
        <a class="btn btn-green" href="{{ url('blog') }}"><i class="fa fa-rss"></i> Blog</a>
    </div>
</div>