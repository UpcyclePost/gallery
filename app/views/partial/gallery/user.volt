<div class="col-lg-3 col-md-4 col-sm-4">
    <article class="product_image thumbnail">
        <a href="{{ _post['url'] }}">
            <div class="thumbnail-container">
                <img alt="{{ _post['title'] }}" src="{{ _post['thumbnail'] }}" style="min-height:200px;min-width:273px">
                <div class="product-icons">
                    <div class="icon-circle">
                        <i class="fa fa-heart"></i>
                    </div>
                    <div class="icon-circle">
                        <i class="fa fa-share-square-o"></i>
                    </div>
                </div>
                <div class="overlay">
                    <div class="btn-group like">
                        <button class="btn btn-default" type="button"><i class="fa fa-heart"></i> Like</button>
                    </div>

                    <div class="btn-group share">
                        <span class='st_facebook' st_url="{{ _post['url'] }}" st_title="{{ _post['title'] }}" st_image="{{ _post['thumbnail'] }}"></span>
                        <br>
                        <span class='st_twitter' st_via='Upmodinc' st_url="{{ _post['url'] }}" st_title="{{ _post['title'] }}" st_image="{{ _post['thumbnail'] }}"></span>
                        <br>
                        <span class='st_googleplus' st_url="{{ _post['url'] }}" st_title="{{ _post['title'] }}" st_image="{{ _post['thumbnail'] }}"></span>
                        <br>
                        <span class='st_pinterest' st_url="{{ _post['url'] }}" st_title="{{ _post['title'] }}" st_image="{{ _post['thumbnail'] }}"></span>
                    </div>
                </div>
            </div>
        </a>

        <div class="caption">
            <a href="{{ _post['url'] }}">
                <h2><?=Helpers::Truncate($_post['title'], 40)?></h2>
            </a>

            <div class="product-meta clearfix">
                <a class="author" href="{{ _post['shopUrl'] }}">
                    {% if _post['shopName'] %}
                        <a href="{{ _post['shopUrl'] }}"><?=Helpers::Truncate($_post['shopName'], 40)?></a>
                    {% else %}
                        &nbsp;
                    {% endif %}
                </a>
            </div>
        </div>
    </article>
</div>
