<div class="upload-container">
    <form action="{{ url('shop/list/details') }}" method="post" id="post-upload-form">
        <input type="hidden" name="market" value="" />
        <div class="upload-panel">
            <div class="upload-panel-header">
                <h1>List an item</h1>
            </div>
            <div class="upload-panel-subheader text-center">
                Start by uploading an image of your item.
            </div>
            <div class="upload-panel-body">
                <div id="error" class="alert alert-danger" style="display: none;"></div>
                {{ content() }}
                <fieldset class="upload-image" id="dropzone">
                    <p>Drag and drop images here<br>or <a id="uploadLink" href="" onclick="return false;">upload</a> an image right from your computer.</p>
                </fieldset>
                <p class="text-center">Only image files (JPG, GIF, PNG) are allowed. The maximum file size for uploads is 5MB.</p>
                <div class="text-center"><button type="submit" class="btn btn-lg btn-blue btn-collapse"><i class="fa fa-cloud-upload"></i> Upload Image</button></div>
            </div>
        </div>
    </form>
</div>