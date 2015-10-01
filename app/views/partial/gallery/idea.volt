<div class="col-lg-3 col-md-4 col-sm-4">
    <article class="product_image thumbnail">
        <a href="{{ url('gallery/' ~ _post['categoryTitle']|url ~ '/' ~ _post['title']|url ~ '-' ~ _post['ik']) }}">
            <div class="thumbnail-container">
                <img alt="{{ _post['title'] }}" src="<?=Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $_post['id'], $_post['ik']))?>" style="min-height:200px;min-width:273px">
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
                        <span class='st_facebook' st_url="{{ url('gallery/' ~ _post['categoryTitle']|url ~ '/' ~ _post['title']|url ~ '-' ~ _post['ik']) }}" st_title="{{ _post['title'] }}" st_image="<?=Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $_post['id'], $_post['ik']))?>"></span>
                        <br>
                        <span class='st_twitter' st_url="{{ url('gallery/' ~ _post['categoryTitle']|url ~ '/' ~ _post['title']|url ~ '-' ~ _post['ik']) }}" st_title="{{ _post['title'] }}" st_image="<?=Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $_post['id'], $_post['ik']))?>"></span>
                        <br>
                        <span class='st_googleplus' st_url="{{ url('gallery/' ~ _post['categoryTitle']|url ~ '/' ~ _post['title']|url ~ '-' ~ _post['ik']) }}" st_title="{{ _post['title'] }}" st_image="<?=Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $_post['id'], $_post['ik']))?>"></span>
                        <br>
                        <span class='st_pinterest' st_url="{{ url('gallery/' ~ _post['categoryTitle']|url ~ '/' ~ _post['title']|url ~ '-' ~ _post['ik']) }}" st_title="{{ _post['title'] }}" st_image="<?=Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $_post['id'], $_post['ik']))?>"></span>
                    </div>
                </div>
            </div>
        </a>

        <div class="caption">
            <a href="{{ url('gallery/' ~ _post['categoryTitle']|url ~ '/' ~ _post['title']|url ~ '-' ~ _post['ik']) }}">
                <h2><?=Helpers::tokenTruncate($_post['title'], 22, true)?></h2>
            </a>

            <div class="product-meta clearfix">
                <a class="author" href="#">
                    <a href="{{ url('profile/view/') ~ _post['user'] }}"><?=Helpers::Truncate($_post['userName'], 40)?></a>
                </a>
            </div>
        </div>
    </article>
</div>