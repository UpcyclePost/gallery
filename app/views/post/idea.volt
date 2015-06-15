<div class="upload-container">
    <form action="{{ url('post/details') }}" method="post" id="post-upload-form">
        <div class="upload-panel">
            <div class="upload-panel-header">
                <h1>Post an Idea</h1>
            </div>
            <div class="upload-panel-subheader text-center">
                Upload an image and inspire feedback from other upcyclers.
            </div>
            <div class="upload-panel-body">
                <div id="error" class="alert alert-danger" style="display: none;"></div>
                {{ content() }}
                <fieldset class="upload-image" id="dropzone">
                    <p>Drag and drop an image here<br />or <a id="uploadLink" href="" onclick="return false;">click here</a> to post an image from your computer.</p>
                </fieldset>
                <p class="text-center">Only JPG, GIF &amp; PNG under 5MB are up-loadable.</p>
                <div class="text-center"><button type="submit" class="btn btn-lg btn-blue btn-collapse"><i class="fa fa-cloud-upload"></i> Post My Idea</button></div>
            </div>
        </div>
    </form>
</div>