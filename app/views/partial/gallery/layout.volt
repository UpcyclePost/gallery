<?php
$searchCategory = (!isset($category) || $category == 'New') ? '' : $category;
?>
<form id="more" data-start="{% if start is defined %}{{ start }}{% else %}50{% endif %}" data-url="{{ url('gallery/' ~ searchCategory|url) ~ '/more' }}" data-search-term="{% if searchTerm is defined %}{{ searchTerm }}{% endif %}"></form>
<div class="content-container">
    <div class="content-header">
        <h1>
            {% if searchTerm is not defined or searchTerm is empty %}
                {{ category }}
            {% else %}
                "{{ searchTerm }}" in {{ category }}
            {% endif %}
        </h1>
        {% if subtitle is defined %}
            <h4 style="text-align: center; margin-bottom: 30px; margin-top: 0px;">{{ subtitle }}</h4>
        {% endif %}
    </div>
    <div class="content">
        {{ partial('partial/gallery/list') }}
    </div>
</div>