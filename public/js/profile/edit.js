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
    });

    $('.find-me-on button').prop('disabled', false);
    $('.link-remove').prop('disabled', false);

    $('#edit-form').on('click', '.find-me-on li', function(event) {
        event.preventDefault();

        if ($(event.target).is('a'))
        {
            var val = $(event.target).html();
            var type = $(event.target).data('website-type');

            var btn = $(event.target).closest('div').children('button');
            btn.html(val + ' <span class="caret"></span>');
            var hidden = $(event.target).closest('div').children('input[type=hidden]');
            hidden.val(type);

            $(event.target).parent().parent().parent().parent().parent().children('input[type=text]').prop('placeholder', $(event.target).data('placeholder'));
            $(event.target).parent().parent().parent().parent().parent().children('input[type=text]').attr('data-placeholder-length', $(event.target).data('placeholder-length'));
        }
    });

    $('#edit-form').on('click', '.link-remove', function(event) {
        event.preventDefault();

        if ($('.social-item').length == 1)
        {
            var group = $('.social-item')[0];

            $(group).find('input[type=text]').val('').prop('placeholder', '').attr('data-placeholder-length', 0);
            $(group).find('button').html('Select <span class="caret"></span>').val('');
            $(group).find('input[type=hidden]').val('');
        }
        else
        {
            $(this).parent().parent().parent().remove();
        }

        return false;
    });

    $('#add-website').click(function(event) {
        event.preventDefault();
        var group = $($(this).parent().parent().siblings().find('div.input-group')[0]).clone();

        $(group).find('input[type=text]').val('').prop('placeholder', '').attr('data-placeholder-length', 0);
        $(group).find('button').html('Select <span class="caret"></span>').val('');
        $(group).find('input[type=hidden]').val('');


        var div = document.createElement('div');
        $(div).addClass('form-group').addClass('social-item');
        var spacer = document.createElement('label');
        $(spacer).addClass('col-sm-2').addClass('control-label');
        $(div).append(spacer);
        $(div).append(group);

        $(this).parent().parent().before(div);
    })

    $('#choose-new-image').click(function() {
        $('#choose-new-image-label').hide();
        $('#background-upload').show();
    });

    $('#background').change(function() {
        __uploading = true;
    });

    $('#edit-form').on('focus', '.social-url', function(e) {
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