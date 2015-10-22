<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "0919549b-9f77-444b-bd9a-4c8683b78c51", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

<section class="post-details-container">
    <div class="post-details-panel clearfix">
        <div class="post-details-body">
            <div class="post-details-panel-header">
                <h1>{{ post.title }}</h1>
            </div>
            <div class="post-details-crumbs">
                <ol class="breadcrumb">
                    <li><a href="{{ url('browse/ideas') }}">Gallery</a></li>
                    <li><a href="{{ url('browse/ideas/' ~ post.Category.title|url) }}">{{ post.Category.title }}</a></li>
                    <li style="float: right;position:relative;bottom:10px;" class="attr-content" data-content=" ">
                        <span class='st_facebook'></span>
                        <span class='st_twitter' st_via='Upmodinc'></span>
                        <span class='st_googleplus'></span>
                        <span class='st_pinterest'></span>
                    </li>
                </ol>
            </div>
            <div class="post-details-img-container" style="position:relative;">
                <a href="{{ itemPrevLink }}">
                    <img src="{{ static_url('img/left.png') }}" style="position:absolute; left: 10px; z-index:99" alt="Previous Post">
                </a>
                <img src="<?=$post->thumbnail('big')?>" alt="{{ post.title }}" />
                <a href="{{ itemNextLink }}">
                    <img src="{{ static_url('img/right.png') }}" style="position:absolute; right: 10px; z-index:99" alt="Next Post">
                </a>
            </div>
            <div class="post-details-description">
                <h5>Description</h5>
                <p>{{ post.description }}</p>
            </div>
        </div>

        <aside class="post-sidebar">
            <div class="post-author clearfix">
                <div class="avatar">

                </div>
                <div class="author-name"><span>Post By</span><a href="{{ url('profile/view/') ~ post.User.ik }}">{{ post.User.user_name }}</a></div>
            </div>
            <ul class="post-actions col-2 clearfix">
                {% if isLoggedIn %}
                    <li>
                    {% if post.User.ik != auth['ik'] %}
                        <a class="follow" data-url="{{ url('follow/user/') ~ post.User.ik }}">
                            <i class="fa {% if following %}fa-check{%else%}fa-plus{% endif %}"></i>
                            {% if following %}Following{%else%}Follow{% endif %} {{ post.User.user_name }}
                        </a>
                    {% else %}
                        <a href="{{ url('post/edit/' ~ post.ik) }}"><i class="fa fa-pencil"></i> Edit it</a>
                    {% endif %}
                    </li>
                {% endif %}
                {% if enableLike %}
                    <li><a id="up-it" data-url="{{ url('gallery/' ~ post.Category.title|url ~ '/' ~ post.title|url ~ '-' ~ post.ik ~ '/up') }}"><i class="fa fa-heart"></i> Up it</a></li>
                {% else %}
                    {% if liked %}
                        <li><a><i class="fa fa-heart"></i> You like this</a></li>
                    {% else %}
                        <li><a href="{{ url('profile/login') }}"><i class="fa fa-heart"></i> Log in to Up this</a></li>
                    {% endif %}
                {% endif %}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" type="button" data-toggle="dropdown" id="share"><i class="fa fa-send"></i> Send it</a>
                    <ul class="dropdown-menu dropdown-menu-right send" role="menu">
                        <form role="form" id="share-form" data-url="{{ url('gallery/' ~ post.Category.title|url ~ '/' ~ post.title|url ~ '-' ~ post.ik ~ '/share') }}">
                            <div class="send-panel">
                                <div class="send-panel-header">
                                    <h4>Send this post to a friend</h4>
                                </div>
                                <div class="send-panel-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">What is your friends email?</label>
                                        <input type="email" class="form-control" id="shareEmail" placeholder="Enter email" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Add a message</label>
                                        <textarea class="form-control" rows="3" id="shareMessage"></textarea>
                                    </div>
                                </div>
                                <div class="send-panel-footer">
                                    <button type="submit" class="btn btn-green btn-block"><i class="fa fa-envelope"></i> Send Post</button>
                                </div>
                            </div>
                        </form>
                    </ul>
                </li>
            </ul>
            <div class="post-sidebar-container engage">
                <h5>Engagement</h5>
                <ul class="post-engagement clearfix">
                    <li><i class="fa fa-eye"></i> Views <span>{{ post.views|pretty }}</span></li>
                    <li><i class="fa fa-heart"></i> Ups <span id="ups">{{ post.likes|pretty }}</span></li>
                </ul>
            </div>
            <?php $tags = preg_split('@,@', $post->tags, NULL, PREG_SPLIT_NO_EMPTY); ?>
            {% if tags|length > 0 %}
                <div class="post-sidebar-container tags">
                    <h5>Post Tags</h5>
                    <ul class="post-tags clearfix">
                        {% for tag in tags %}
                            {% if tag is empty %}

                            {% else %}
                                <li><a href="">{{ tag }}</a></li>
                            {% endif %}
                        {% else %}
                            <li>None</li>
                        {% endfor %}
                    </ul>
                </div>
                <?php $materials = Helpers::getMaterials($tags); ?>
                {% if materials|length > 0 %}
                <div class="post-sidebar-container materials">
                    <h5>Materials Used</h5>
                    <ul class="post-tags materials clearfix">
                        {% for material in materials %}
                            <li><a href="">{{ material }}</a></li>
                        {% endfor %}
                    </ul>
                </div>
                {% endif %}
            {% endif %}
            <div class="post-sidebar-container last">
                <ul class="post-report clearfix">
                    {% if isLoggedIn and auth['role'] is 'Admins' %}
                    <li><a id="deletePost"><i class="fa fa-warning"></i> Delete This Post</a></li>
                    {% else %}
                    <li><a id="report-it" data-url="{{ url('gallery/' ~ post.Category.title|url ~ '/' ~ post.title|url ~ '-' ~ post.ik ~ '/report') }}"><i class="fa fa-warning"></i> Report This Post</a></li>
                    {% endif %}
                </ul>
            </div>
        </aside>
    </div>

    <div class="content-header">
        <h2>{{ post.User.user_name }}'s Board</h2>
    </div>
    {% set isodiv = "iso" %}
    {{ partial('partial/gallery/list') }}

    {{ partial('partial/disqus') }}

</section>

<script type="text/javascript">
    mixpanel.track('Idea Page', {
        'Idea Id': '{{ post.ik }}',
        'Idea Name': '{{ post.title }}',
        'Category Name': '{{ post.Category.title }}'
    });
</script>
