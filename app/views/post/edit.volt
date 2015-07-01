<div class="upload-container">
    <form method="post" action="{{ url('post/edit/' ~ post.ik) }}" id="post-details-form">
        <input type="hidden" name="_ref" value="{{ post.ik }}">
        <input type="hidden" name="_val" value="{{ post.id }}">
        <div class="upload-panel">
            <div class="upload-panel-header">
                <h1>Edit Your Post</h1>
            </div>
            <div class="upload-panel-subheader text-center">
                Fill in details below and then publish to save your changes.
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
                                <input type="text" id="title" class="form-control" required name="title" value="{{ post.title }}">
                            </div>
                            <div class="form-group">
                                <label for="tags" class="block">Tags</label>
                                <input type="text" class="form-control tm-input" placeholder="Type a tag and press enter..." name="tags" id="tags">
                                <div class="tags-container"></div>
                            </div>
                            <div class="form-group">
                                <label for="tags">Description</label>
                                <textarea style="min-height: 100px" rows="3" class="form-control" required name="description">{{ post.description }}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="category_top" value="" />
                                <input type="hidden" name="category_sub" value="" />

                                <label style="margin-right: 10px">Posted In</label>
                                <input type="hidden" name="category" id="category-selected" required value="{{ post.category_ik }}" />
                                <ul id="cat-drop" class="cat-meta-dropdown">
                                    <li id="cat-drop-toggle"><span id="category">{{ post.Category.title }}</span> <i class="fa fa-chevron-up"></i>
                                        <ul class="cat-meta-selects" id="drop-down">
                                            <li id="primary" class="primary">
                                                <h5>Main Category</h5>
                                                <ul>
                                                    {% for ik, category in categories %}
                                                        {% if ik == post.category_ik %}
                                                            <li category="{{ ik }}" class="active">{{ category["title"] }}</li>
                                                        {% else %}
                                                            <li category="{{ ik }}">{{ category["title"] }}</li>
                                                        {% endif %}
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
                            <div><button type="submit" class="btn btn-lg btn-blue btn-collapse"><i class="fa fa-cloud-upload"></i> Publish</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var s = <?php echo json_encode($categories); ?>;
    var _tags = '{{ post.tags }}';
</script>