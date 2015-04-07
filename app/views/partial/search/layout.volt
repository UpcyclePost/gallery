<div class="content-container">
    <div class="content-header">
        <h1>
            {% if searchTerm is not defined or searchTerm is empty %}
                Browse Users
            {% else %}
                "{{ searchTerm }}" in Users
            {% endif %}
        </h1>
    </div>
    <aside class="cat-sidebar" data-spy="affix" data-offset-top="{{ offsetTop }}">
        {{ partial('partial/menu/sidebar') }}
    </aside>
    <div class="content">
        <div class="posts-container">
            {% set isodiv = "iso" %}
            {{ partial('partial/search/list') }}
        </div>
    </div>
</div>