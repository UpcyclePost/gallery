{{ content() }}

<div class="account-settings-container">
    <div class="login-panel" id="profile-edit-panel">
        <div class="login-panel-header">
            <h1>Edit Your Profile</h1>
        </div>
        <form class="form-horizontal" role="form" method="post" id="edit-form" enctype="multipart/form-data">
        <input type="hidden" name="flow" value="{{ flow }}">
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
                    <label class="col-xs-12 col-sm-2 control-label">Profile Image</label>
                    <div class="col-xs-6 col-sm-5">
                        <div id="avatar-error" style="display: none;"></div>
                        <div id="avatar-upload">
                            <fieldset class="upload-image upload-profile-image" id="avatar-dropzone">
                                {% if profile.avatar %}
                                    <img id="upload-profile-image-preview" src="{{ profile.avatarUrl() }}" />
                                {% endif %}
                            </fieldset>
                            <br />
                            <button class="btn btn-blue" type="button" id="choose-avatar">Select File</button>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-5">
                        Pick a great shot of yourself, your pet, or a favorite character and upload it.
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Background</label>
                    <div class="col-xs-6 col-sm-5">
                        <div id="background-error" style="display: none;"></div>
                        <div id="background-upload">
                            <fieldset class="upload-image upload-background-image" id="background-dropzone">
                                {% if profile.custom_background %}
                                    <img id="upload-background-image-preview" src="{{ profile.backgroundThumbUrl() }}" />
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
                        <input type="text" class="form-control" id="location" name="location" value="{{ profile.location }}" maxlength="35">
                    </div>
                </div>

                {% for index, website in websites %}
                    <div class="form-group social-item">
                        <label for="username" class="col-sm-2 control-label">{% if loop.first %}Find me on{% endif %}</label>
                        <div class="col-sm-10 input-group">
                            <font class="input-group-addon" style="width: 125px;">
                                <div class="btn-group find-me-on" style="width: 100%;">
                                    <input type="hidden" name="website[]" value="{{ website['type'] }}">
                                    <button class="btn btn-sm dropdown-toggle find-me-dropdown" disabled="disabled" data-toggle="dropdown" style="padding: 4px; background-color: inherit; width:100%;">
                                      {% if website['type'] == 'twitter' %}
                                        <i class="fa fa-fw fa-twitter"></i> Twitter
                                      {% elseif website['type'] == 'facebook' %}
                                        <i class="fa fa-fw fa-facebook"></i> Facebook
                                      {% elseif website['type'] == 'pinterest' %}
                                        <i class="fa fa-fw fa-pinterest-p"></i> Pinterest
                                      {% elseif website['type'] == 'website' %}
                                        <i class="fa fa-fw fa-external-link"></i> My Website
                                      {% else %}
                                        Select
                                      {% endif %}
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu text-left">
                                      <li><a data-website-type="twitter" href="" data-placeholder-length="11" data-placeholder="https://twitter.com/youraccount"><i class="fa fa-fw fa-twitter"></i> Twitter</a></li>
                                      <li><a data-website-type="facebook" href="" data-placeholder-length="11" data-placeholder="https://www.facebook.com/youraccount"><i class="fa fa-fw fa-facebook"></i> Facebook</a></li>
                                      <li><a data-website-type="pinterest" href=""><i class="fa fa-fw fa-pinterest-p"></i> Pinterest</a></li>
                                      <li><a data-website-type="website" href=""><i class="fa fa-fw fa-external-link"></i> My Website</a></li>
                                    </ul>
                                  </div>
                            </font>
                            <input type="text" class="form-control social-url" name="website_url[]" value="{{ website['url'] }}">
                            <font class="input-group-addon" style="background-color:white;border:none;">
                                <a class="link-remove" disabled="disabled" href=""><i class="fa fa-fw fa-times" style="color: red;"></i></a>
                            </font>
                        </div>
                    </div>
                {% else %}
                    <div class="form-group social-item">
                        <label for="username" class="col-sm-2 control-label">Find me on</label>
                        <div class="col-sm-10 input-group">
                            <font class="input-group-addon" style="width: 125px;">
                                <div class="btn-group find-me-on" style="width: 100%;">
                                    <input type="hidden" name="website[]" value>
                                    <button class="btn btn-sm dropdown-toggle find-me-dropdown" disabled="disabled" data-toggle="dropdown" style="padding: 4px; background-color: inherit; width: 100%;">
                                      Select
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu text-left">
                                      <li><a data-website-type="twitter" href="" data-placeholder-length="11" data-placeholder="https://twitter.com/youraccount"><i class="fa fa-fw fa-twitter"></i> Twitter</a></li>
                                      <li><a data-website-type="facebook" href="" data-placeholder-length="11" data-placeholder="https://www.facebook.com/youraccount"><i class="fa fa-fw fa-facebook"></i> Facebook</a></li>
                                      <li><a data-website-type="pinterest" href=""><i class="fa fa-fw fa-pinterest-p"></i> Pinterest</a></li>
                                      <li><a data-website-type="website" href=""><i class="fa fa-fw fa-external-link"></i> My Website</a></li>
                                    </ul>
                                  </div>
                            </font>
                            <input type="text" class="form-control social-url" name="website_url[]" value="">
                            <font class="input-group-addon" style="background-color:white;border:none;">
                                <a class="link-remove" disabled="disabled" href=""><i class="fa fa-fw fa-times" style="color: red;"></i></a>
                            </font>
                        </div>
                    </div>
                {% endfor %}

                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <a id="add-website" href=""><i class="fa fa-plus"></i> Add another place</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        Some links may be restricted by the system and won't appear on your profile.
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">About me</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="about" required maxlength="4096">{{ profile.about }}</textarea>
                    </div>
                </div>
        </div>
        <div class="login-panel-footer text-right">
            <button type="reset" class="btn btn-gray">Reset</button> <button type="submit" class="btn btn-green">{% if flow is 'shop' %}<i class="fa fa-angle-double-right"></i> Continue{% else %}<i class="fa fa-check"></i> Save Changes{% endif %}</button>
        </div>
        </form>
    </div>
</div>