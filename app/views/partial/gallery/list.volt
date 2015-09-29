<div class="row">
    {% if results is defined %}
        {% for post in results %}
            {% if post['_user'] is defined %}
                {{ partial('partial/gallery/user') }}
            {% elseif post['_shop'] is defined %}
                {{ partial('partial/gallery/shop') }}
            {% elseif post['market'] is defined %}
                {{ partial('partial/gallery/product') }}
            {% else %}
                {{ partial('partial/gallery/idea') }}
            {% endif %}
        {% else %}

        {% endfor %}
    {% endif %}
</div>