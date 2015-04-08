<div id="iso-container">
    <div id="{{ isodiv }}">
        {% if results is defined %}
            {% for item in results %}
                <article class="gallery-post">
                    <a href="{{ item['url'] }}">
                        <div class="gallery-img">
                            <img class="img-responsive" src="{{ item['thumbnail'] }}" alt="{{ item['title'] }}" />
                        </div>
                    </a>
                    <div class="gallery-meta">
                        <div class="author-category">
                            <span><a href="{{ url('profile/view/') ~ item["user"] }}">{{ item['userName'] }}</a></span>
                        </div>
                    </div>
                </article>
            {% else %}

            {% endfor %}
        {% endif %}
    </div>
</div>