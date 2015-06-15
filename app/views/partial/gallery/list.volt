<div id="iso-container">
    <div id="{{ isodiv }}">
        {% if users is defined %}
            {% for user in users %}
                {% if user.custom_background %}
                    <article class="gallery-post">
                        <a href="{{ user.url() }}">
                            <div class="gallery-img">
                                <img class="img-responsive" src="{{ user.backgroundUrl() }}" alt="{{ user.user_name }}" />
                            </div>
                        </a>
                        <div class="gallery-meta">
                            <div class="author-category">
                                <span><a href="{{ url('profile/view/') ~ user.ik }}">{{ user.user_name }}</a></span>
                            </div>
                            <ul class="gallery-engagement clearfix">
                                <li><i class="fa fa-eye"></i> {{ user.views|pretty }}</li>
                            </ul>
                        </div>
                    </article>
                {% endif %}
            {% endfor %}
        {% endif %}
        {% if results is defined %}
            {% for post in results %}
                {% if post['promotion'] is defined %}
                    <article class="gallery-post">
                        <a href="{{ post['url'] }}">
                            <div class="gallery-img">
                                <img class="img-responsive" src="//i.upcyclepost.com/{{ post['thumbnail'] }}" alt="{{ post['title'] }}" />
                            </div>
                        </a>
                        <div class="gallery-meta">
                            <div class="author-category">
                                by <span>UpcyclePost</span> in <span>Promotions</span>
                            </div>
                        </div>
                    </article>
                {% elseif post['market'] is defined %}
                    <article class="gallery-post">
                        <a href="{{ post['url'] }}">
                            <div class="gallery-img" style="position: relative;">
                                <img class="img-responsive" src="{{ post['image'] }}" alt="{{ post['title'] }}" />
                            </div>
                        </a>
                        <div class="gallery-meta">
                            <div class="author-category" style="line-height: 1.8em;">
                                <strong><a href="{{ post['url'] }}"><?=Helpers::Truncate($post['title'], 40)?></a></strong>
                                <br>
                                <div class="pull-left">
                                    <a href="{{ url('shops/') ~ post['userName'] }}" style="color:#999">{{ post['shopName'] }}</a>
                                </div>
                                <div class="pull-right">
                                    <strong><a href="{{ post['url'] }}">${{ post['price']|pretty }}</a></strong>
                                </div>
                                <br clear="all">
                            </div>
                        </div>
                    </article>
                {% else %}
                    <article class="gallery-post">
                        <a href="{{ url('gallery/' ~ post['categoryTitle']|url ~ '/' ~ post['title']|url ~ '-' ~ post['ik']) }}">
                            <div class="gallery-img">
                                <img class="img-responsive" src="//i.upcyclepost.com/post/{{ post['id'] }}-{{ post['ik'] }}.small.png" alt="{{ post['title'] }}" />
                            </div>
                        </a>
                        <div class="gallery-meta">
                            <div class="author-category">
                                by <span><a href="{{ url('profile/view/') ~ post["user"] }}">{{ post['userName'] }}</a></span> in <span><a href="{{ url('gallery/' ~ post['categoryTitle']|url) }}">{{ post['categoryTitle'] }}</a></span>
                            </div>
                            <ul class="gallery-engagement clearfix">
                                <li><i class="fa fa-eye"></i> {{ post['views']|pretty }}</li>
                                <li><i class="fa fa-heart"></i> {{ post['likes']|pretty }}</li>
                                {% if isLoggedIn and post['user'] == auth['ik'] and deleteable is defined and deleteable === true %}
                                    <li><i class="fa fa-trash-o"></i> <a class="deletePost" data-url="{{ url('post/remove/' ~ post['ik']) }}">Delete</a></li>
                                {% endif %}
                            </ul>
                        </div>
                    </article>
                {% endif %}
            {% else %}

            {% endfor %}
        {% endif %}
    </div>
</div>