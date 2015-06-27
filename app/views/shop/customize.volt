{{ content() }}

<div class="account-settings-container">
    <div class="login-panel" id="profile-edit-panel">
        <div class="login-panel-header">
            <h1>Customize Your Shop</h1>
        </div>
        <form class="form-horizontal" role="form" method="post" id="edit-form" enctype="multipart/form-data">
        <div class="login-panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 control-label">Logo</label>
                <div class="col-xs-6 col-sm-5">
                    <div id="logo-error" style="display: none;"></div>
                    <div id="logo-upload">
                        <fieldset class="upload-image upload-profile-image" id="logo-dropzone">
                            {% if profile.Shop.logo %}
                                <img id="upload-profile-image-preview" src="{{ profile.Shop.logoUrl() }}" width="100" height="100" />
                            {% endif %}
                        </fieldset>
                        <br />
                        <button class="btn btn-blue" type="button" id="choose-logo">Select File</button>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-5">
                    Your shop logo will be resized and cropped to 100x100.
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 control-label">Background</label>
                <div class="col-xs-6 col-sm-5">
                    <div id="background-error" style="display: none;"></div>
                    <div id="background-upload">
                        <fieldset class="upload-image upload-background-image" id="background-dropzone">
                            {% if profile.Shop.background %}
                                <img id="upload-background-image-preview" src="{{ profile.Shop.backgroundThumbUrl() }}" />
                            {% endif %}
                        </fieldset>
                        <br />
                        <button class="btn btn-blue" type="button" id="choose-background">Select File</button>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-5">
                    This high resolution image will fill the entire screen and should be a landscape, not a portrait, image if possible. Pick something that reflects what you love about upcycling, how you work, what you like to collect, etc.
                </div>
            </div>
        </div>
        <div class="login-panel-footer text-right">
            <a class="btn btn-gray" href="{{ url('shops/my/customize') }}">Cancel</a> <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Save Changes</button>
        </div>
        </form>
    </div>
</div>