{% if isMore is not defined %}
    <div class="row" id="items-display-container">
{% endif %}

    <?php if (isset($results) && count($results) > 0) { ?>
        {% for _post in results %}
            {% if _post['_user'] is defined %}
                {{ partial('partial/gallery/user') }}
            {% elseif _post['_shop'] is defined %}
                {{ partial('partial/gallery/shop') }}
            {% elseif _post['market'] is defined %}
                {{ partial('partial/gallery/product') }}
            {% else %}
                {{ partial('partial/gallery/idea') }}
            {% endif %}
        {% else %}

        {% endfor %}
    <?php } ?>

{% if isMore is not defined %}
    </div>
{% endif %}