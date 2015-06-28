$(function() {
    var dropzone = new Dropzone('fieldset#logo-dropzone', {
        url: '/shops/my/customize/upload/logo',
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 10, // MB
        maxFiles: 1,
        acceptedFiles: "image/*",
        addRemoveLinks: false,
        init: function() {
            var _this = this;

            var selectFile = document.querySelector('#choose-logo');
            selectFile.addEventListener('click', function() {
                _this.hiddenFileInput.click();
            });

            this.on("thumbnail", function(file) {
                if (file.acceptDimensions) {
                    file.acceptDimensions();
                }
            });

            this.on("complete", function(file) {
                $('#logo-error').hide();
                $('#choose-logo').hide();

                if (file.accepted) {
                    var a = $.parseJSON(file.xhr.responseText);
                    if (a.success) {
                        var img = new Image();
                        img.onload = function() {
                            $('#logo-dropzone').html('<img id="upload-profile-image-preview" src="' + a.data.preview + '" width="244" /><input type="hidden" name="logo" value="'+ a.data.file+'">');
                        };
                        img.src = a.data.preview;
                        dropzone.disable(); // Disable the dropzone on successful upload
                    } else {
                        this.removeFile(file);
                    }
                } else {
                    $('#logo-error').show();
                    $('#logo-error').html($('.dz-error-message span').html());
                    this.removeFile(file);
                }
            });

            this.on('uploadprogress', function(file, progress) {
                $('#logo-dropzone').html('<p>Uploading your logo, ' + Math.round(progress) + '%</p>');
            });
        },

        accept: function(file, done) {
            $('#logo-error').hide();

            file.acceptDimensions = done;
        }
    });

    var backgroundDropzone = new Dropzone('fieldset#background-dropzone', {
        url: '/shops/my/customize/upload/background',
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