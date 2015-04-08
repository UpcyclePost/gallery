<nav class="menu hidden-xs hidden-sm" id="main-menu">
    <div class="content-container">
        <div class="menu-container clearfix">
            <div class="main-menu menu-categories clearfix">
                <h4 class="blue">Browse Categories</h4>
                <?php $categories = Helpers::getCategoryList(); ?>
                <div class="col-sm-3">
                    <ul>
                {% for _category in categories %}
                    {% if loop.index0 > 0 and loop.index0 % 6 is 0 %}
                        </ul></div><div class="col-sm-3"><ul>
                    {% endif %}
                        <li><a href="{{ url('gallery/' ~ _category['title']|url) }}">{{ _category['title'] }}</a></li>
                {% endfor %}
                    </ul>
                </div>
                <div class="col-sm-6" style="margin-top: 15px;">
                    <ul>
                       <a href="{{ url('search/users') }}"><li><i class="fa fa-users"></i> Visit Profile Gallery</a></li>
                    </ul>
                </div>
            </div>

            <div class="main-menu menu-child-menus clearfix">
                <div class="menu-col-1 clearfix">
                    <h4 class="blue">Company</h4>
                    <div class="col-xs-12">
                        <ul>
                            <li><a href="{{ url('about') }}">About Us</a></li>
                            <li><a target="_blank" href="http://www.facebook.com/upcyclepost">Facebook</a></li>
                            <li><a target="_blank" href="http://www.twitter.com/upcyclepost">Twitter</a></li>
                            <li><a target="_blank" href="http://www.linkedin.com/company/upcyclepost-com">LinkedIn</a></li>
                            <li><a target="_blank" href="{{ url('blog') }}">Blog</a></li>
                            <li><a target="_blank" href="{{ url('contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="menu-col-2 clearfix">
                    <h4 class="green">Do you have an idea?</h4>
                    <div class="col-xs-12">
                        <p>It doesn't matter if it's a work in progress, rough draft or a finished product.</p>
                        <a class="btn btn-green" href="{{ url('post/idea') }}"><i class="fa fa-camera"></i>Post Your Idea</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>