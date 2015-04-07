<?php
$searchCategory = ($category == 'New') ? '' : $category;
?>
<form id="more" data-url="{{ url('gallery/' ~ searchCategory|url) ~ '/more' }}" data-search-term="{% if searchTerm is defined %}{{ searchTerm }}{% endif %}"></form>
<div class="content-container">
    <div class="content-header">
        <h1>
            {% if searchTerm is not defined or searchTerm is empty %}
                {{ category }}
            {% else %}
                "{{ searchTerm }}" in {{ category }}
            {% endif %}
        </h1>
    </div>
    <aside class="cat-sidebar" data-spy="affix" data-offset-top="{{ offsetTop }}">
        {{ partial('partial/menu/sidebar') }}
    </aside>
    <div class="content">
        <div class="posts-container">
            {% set isodiv = "iso" %}
            {{ partial('partial/gallery/list') }}
        </div>
    </div>
</div>