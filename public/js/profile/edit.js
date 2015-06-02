$(function() {
    var __uploading = false;

    $('#edit-form').validate({
        highlight: function(el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function(el) {
            $(el).parent('div').removeClass('has-error');
        },
        rules: {
            twitter: {
                url: true
            },
            facebook: {
                url: true
            },
            etsy: {
                url: true
            }
        },
        errorClass: "help-block",
        submitHandler: function(form) {
            $('.social-url').each(function() {
                if ($(this).val() == $(this).prop('placeholder'))
                {
                    $(this).val('');
                }
            });
            if (__uploading)
            {
                $('#profile-edit-panel').block({
                    message: '<h4>Hang on while we update your profile image...</h4>',
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .7,
                        color: '#fff',
                        width: '80%'
                    },
                    overlayCSS: { backgroundColor: '#fff' }
                });
            }

            form.submit();
        }
    })

    $('#choose-new-image').click(function() {
        $('#choose-new-image-label').hide();
        $('#background-upload').show();
    });

    $('#background').change(function() {
        __uploading = true;
    });

    $('.social-url').focus(function(e) {
        var target = $(e.target);
        if (target.val() == '' || target.val() == target.prop('placeholder'))
        {
            target.val(target.prop('placeholder'));

            setTimeout(function() {
                $(target)[0].selectionStart = target.val().length-target.attr('data-placeholder-length');
                $(target)[0].selectionEnd = target.val().length;
            })
        }
    });

    var dropzone = new Dropzone('fieldset#avatar-dropzone', {
        url: '/profile/edit/upload/avatar',
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 10, // MB
        maxFiles: 1,
        acceptedFiles: "image/*",
        addRemoveLinks: false,
        init: function() {
            var _this = this;

            var selectFile = document.querySelector('#choose-avatar');
            selectFile.addEventListener('click', function() {
                _this.hiddenFileInput.click();
            });

            this.on("thumbnail", function(file) {
                if (file.acceptDimensions) {
                    file.acceptDimensions();
                }
            });

            this.on("complete", function(file) {
                $('#avatar-error').hide();
                $('#choose-avatar').hide();

                if (file.accepted) {
                    var a = $.parseJSON(file.xhr.responseText);
                    if (a.success) {
                        var img = new Image();
                        img.onload = function() {
                            $('#avatar-dropzone').html('<img id="upload-profile-image-preview" src="' + a.data.preview + '" /><input type="hidden" name="avatar" value="'+ a.data.file+'">');
                        };
                        img.src = a.data.preview;
                        dropzone.disable(); // Disable the dropzone on successful upload
                    } else {
                        this.removeFile(file);
                    }
                } else {
                    $('#avatar-error').show();
                    $('#avatar-error').html($('.dz-error-message span').html());
                    this.removeFile(file);
                }
            });

            this.on('uploadprogress', function(file, progress) {
                $('#avatar-dropzone').html('<p>Uploading your image, ' + Math.round(progress) + '%</p>');
            });
        },

        accept: function(file, done) {
            $('#avatar-error').hide();

            file.acceptDimensions = done;
        }
    });

    var backgroundDropzone = new Dropzone('fieldset#background-dropzone', {
        url: '/profile/edit/upload/background',
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 50, // MB
        maxFiles: 1,
        acceptedFiles: "image/*",
        addRemoveLinks: false,
        init: function() {
            var _this = this;

            var selectFile = document.querySelector('#choose-background');
            selectFile.addEventListener('click', function() {
                _this.hiddenFileInput.click();
            });

            this.on("thumbnail", function(file) {
                if (file.acceptDimensions) {
                    file.acceptDimensions();
                }
            });

            this.on("complete", function(file) {
                $('#background-error').hide();
                $('#choose-background').hide();

                if (file.accepted) {
                    var a = $.parseJSON(file.xhr.responseText);
                    if (a.success) {
                        var img = new Image();
                        img.onload = function() {
                            $('#background-dropzone').html('<img id="upload-background-image-preview" src="' + a.data.preview + '" /><input type="hidden" name="background" value="'+ a.data.file+'">');
                        };
                        img.src = a.data.preview;
                        dropzone.disable(); // Disable the dropzone on successful upload
                    } else {
                        this.removeFile(file);
                    }
                } else {
                    $('#background-error').show();
                    $('#background-error').html($('.dz-error-message span').html());
                    this.removeFile(file);
                }
            });

            this.on('uploadprogress', function(file, progress) {
                $('#background-dropzone').html('<p>Uploading your background, ' + Math.round(progress) + '%</p>');
            });
        },

        accept: function(file, done) {
            $('#background-error').hide();

            file.acceptDimensions = done;
        }
    });
});