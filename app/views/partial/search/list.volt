<div id="iso-container">
    <div id="{{ isodiv }}">
        {% if results is defined %}
            {% for item in results %}
                <article class="gallery-post{% if item['shopName'] is defined %} is-shop{% endif %}">
                    <a href="{{ item['url'] }}">
                        {% if item['thumbnail'] %}
                        <div class="gallery-img">
                            <img class="img-responsive" src="{{ item['thumbnail'] }}" alt="{{ item['title'] }}" />
                        </div>
                        {% else %}
                        <div style="width: 244px; height: 244px;" class="text-center">
                            <div style="position: relative;top: 50%;transform: translateY(-50%);">
                                <h2>{{ item['userName'] }}</h2>
                            </div>
                        </div>
                        {% endif %}
                    </a>
                    <div class="gallery-meta">
                        <div class="author-category">
                            <span><a href="{{ item['url'] }}">{{ item['userName'] }}</a></span>
                        </div>
                        <ul class="gallery-engagement clearfix">
                            <li><i class="fa fa-eye"></i> {{ item['views']|pretty }}</li>
                            {% if item['followers'] is defined %}
                                <li><i class="fa fa-heart"></i> {{ item['followers']|pretty }}</li>
                            {% endif %}
                            {% if item['shopName'] is defined %}
                                <li><a href="{{ item['shopUrl'] }}">{{ item['shopName'] }}</a></li>
                            {% endif %}
                        </ul>
                    </div>
                </article>
            {% else %}

            {% endfor %}
        {% endif %}
    </div>
</div>