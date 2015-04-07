<div class="upload-container">
    <form action="{{ url('shop/list/submit') }}" method="post" id="post-details-form">
        <div class="upload-panel">
            <div class="upload-panel-header">
                <h1>You're Almost Done!</h1>
            </div>
            <div class="upload-panel-subheader text-center">
                Fill in details below to list your item.
            </div>
            <div class="upload-panel-body">
                {{ content() }}
                <div class="row">
                    <div class="col-sm-5">
                        <img src="{{ thumbnail }}" class="img-responsive">
                    </div>
                    <div class="col-sm-7">
                        <form role="form">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" id="title" class="form-control" required name="title" placeholder="Be as descriptive as possible." value="{{ postTitle }}">
                            </div>
                            <div class="form-group">
                                <label for="tags" class="block">Tags & Materials</label>
                                <input type="text" class="form-control tm-input" placeholder="Type a tag and press enter..." name="tags" id="tags">
                                <div class="tags-container"></div>
                            </div>
                            <div class="form-group">
                                <label for="tags">Description</label>
                                <textarea style="min-height: 100px" rows="3" class="form-control" required name="description" placeholder="Make your listing stand out by writing a thorough description.">{{ description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="title">Price</label>
                                <input type="text" id="price" class="form-control" required name="price" placeholder="$" value="{{ price }}">
                            </div>
                            <div class="form-group">
                                <label for="title">Shipping</label>
                                <input type="text" id="shipping" class="form-control" required name="shipping" placeholder="$" value="{{ shipping }}">
                                <br />
                                <a href="http://postcalc.usps.com/" target="_blank">USPS Shipping Calculator</a>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="category_top" value="" />
                                <input type="hidden" name="category_sub" value="" />

                                <label style="margin-right: 10px">Posted In</label>
                                <input type="hidden" name="category" id="category-selected" required value="{{ actual_category }}" />
                                <ul id="cat-drop" class="cat-meta-dropdown">
                                    <li id="cat-drop-toggle"><span id="category">Choose Category</span> <i class="fa fa-chevron-up"></i>
                                        <ul class="cat-meta-selects" id="drop-down">
                                            <li id="primary" class="primary">
                                                <h5>Main Category</h5>
                                                <ul>
                                                    {% for ik, category in categories %}
                                                        <?php
                                                            $class = (isset($actual_category) && $actual_category == $ik) ? ' class="active"' : '';
                                                        ?>
                                                        <li category="{{ ik }}"{{ class }}>{{ category["title"] }}</li>
                                                    {% endfor %}
                                                </ul>
                                            </li>
                                            <li id="secondary" class="secondary">
                                                <h5>Secondary Category</h5>
                                                <ul id="secondary-category-list">

                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div><button type="submit" class="btn btn-lg btn-blue btn-collapse"><i class="fa fa-cloud-upload"></i> Submit Listing</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var s = <?php echo json_encode($categories); ?>;
    var tags = false;
    <?php
        if (isset($tags))
        {
            echo sprintf('tags=["%s"]', implode('","', explode(',', $tags)));
        }
    ?>
</script>