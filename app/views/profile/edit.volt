{{ content() }}

<div class="account-settings-container">
    <div class="login-panel" id="profile-edit-panel">
        <div class="login-panel-header">
            <h1>Edit Your Profile</h1>
        </div>
        <form class="form-horizontal" role="form" method="post" id="edit-form" enctype="multipart/form-data">
        <div class="login-panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">First Name</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="firstName" class="form-control" id="first-name" value="{{ profile.first_name }}" required minLength="3">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Last Name</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="lastName" class="form-control" id="last-name" value="{{ profile.last_name }}" required minLength="2">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Background</label>
                    <div class="col-xs-6 col-sm-5">
                        {% if profile.custom_background %}
                        <label class="control-label" id="choose-new-image-label">
                        <a id="choose-new-image">Click here to choose a new background image</a>
                        </label>
                        {% endif %}
                        <div id="background-upload"{% if profile.custom_background %} style="display: none;"{% endif %}>
                            <input type="file" name="background" class="form-control file-input" id="background">
                            For optimal display image should be at least 1200 pixels wide by 800 pixels tall
                        </div>
                    </div>
                    {% if profile.custom_background %}
                    <div class="col-xs-6 col-sm-5" style="text-align: center;">
                        <img src="//i.upcyclepost.com/profile/{{ profile.custom_background }}" height="100">
                    </div>
                    {% endif %}
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Gender</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="gender-m" value="Male"{% if profile.gender is 'Male' %} checked{%endif%}> Male
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="gender-f" value="Female"{% if profile.gender is 'Female' %} checked{%endif%}> Female
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="gender-u" value="Unspecified"{% if profile.gender is 'Unspecified' %} checked{%endif%}> Unspecified
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Location</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="location" name="location" value="{{ profile.location }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <strong>Follow Me</strong><br />
                        Copy and paste the links to your Etsy and social media profiles.
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Etsy</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control social-url" id="etsy" name="etsy" value="{{ profile.etsy }}" data-placeholder-length="13" placeholder="https://www.etsy.com/shop/yourstorename">
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Twitter</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control social-url" id="twitter" name="twitter" data-placeholder-length="11" value="{{ profile.twitter }}" placeholder="https://twitter.com/youraccount">
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Facebook</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control social-url" id="facebook" name="facebook" data-placeholder-length="11" value="{{ profile.facebook }}" placeholder="https://www.facebook.com/youraccount">
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">About You</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="about">{{ profile.about }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <input type="checkbox"{% if profile.contact_for_marketplace is 1 %} checked{% endif %} name="marketplace" id="marketplace"> <label for="marketplace" style="padding-left: 2px;">Contact me regarding selling products when the Marketplace launches shortly</label>
                    </div>
                </div>
        </div>
        <div class="login-panel-footer text-right">
            <button type="reset" class="btn btn-gray">Reset</button> <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Save Changes</button>
        </div>
        </form>
    </div>
</div>