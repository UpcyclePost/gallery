<div class="col-lg-3 col-md-4 col-sm-4">
    <article class="product_image thumbnail">
        <a href="{{ url('gallery/' ~ post['categoryTitle']|url ~ '/' ~ post['title']|url ~ '-' ~ post['ik']) }}">
            <div class="thumbnail-container">
                <img alt="{{ post['title'] }}" src="<?=Helpers::getImageUrl(sprintf('post/%s-%s.small.png', $post['id'], $post['ik']))?>" style="min-height:200px;min-width:273px">
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
                        <button class="btn btn-default" type="button">Share <i class="fa fa-share-square-o"></i></button>
                    </div>
                </div>
            </div>
        </a>

        <div class="caption">
            <a href="{{ url('gallery/' ~ post['categoryTitle']|url ~ '/' ~ post['title']|url ~ '-' ~ post['ik']) }}">
                <h2><?=Helpers::tokenTruncate($post['title'], 22, true)?></h2>
            </a>

            <div class="product-meta clearfix">
                <a class="author" href="#">
                    <a href="{{ url('profile/view/') ~ post['user'] }}"><?=Helpers::Truncate($post['userName'], 40)?></a>
                </a>
            </div>
        </div>
    </article>
</div>