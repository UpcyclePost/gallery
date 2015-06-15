var dropzone = new Dropzone('fieldset#dropzone', {
    url: '/post/upload',
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 5, // MB
    maxFiles: 1,
    acceptedFiles: "image/*",
    addRemoveLinks: false,
    init: function() {
        var _this = this;

        var selectFile = document.querySelector('#uploadLink');
        selectFile.addEventListener('click', function() {
            _this.hiddenFileInput.click();
        });

        this.on("thumbnail", function(file) {
            if (file.width < 300) {
                if (file.rejectDimensions) {
                    console.log('File not accepted due to size restrictions.');
                    file.rejectDimensions();
                }
            } else {
                if (file.acceptDimensions) {
                    file.acceptDimensions();
                }
            }
        });

        this.on("complete", function(file) {
            $('#error').hide();

            if (file.accepted) {
                var a = $.parseJSON(file.xhr.responseText);
                if (a.success) {
                    var img = new Image();
                    img.onload = function() {
                        $('#dropzone').html('<img id="upload-preview" src="' + a.data.preview + '" />');
                    };
                    img.src = a.data.preview;
                    dropzone.disable(); // Disable the dropzone on successful upload
                } else {
                    this.removeFile(file);
                }
            } else {
                $('#error').show();
                $('#error').html($('.dz-error-message span').html());
                this.removeFile(file);
            }
        });

        this.on('uploadprogress', function(file, progress) {
            $('#dropzone').html('<p>Uploading your idea, ' + Math.round(progress) + '%</p>');
        });
    },

    accept: function(file, done) {
        $('#error').hide();

        file.acceptDimensions = done;
        file.rejectDimensions = function() { done("File must be at least 560 pixels wide to upload."); };
    }
});

$('#post-upload-form').submit(function(event) {
    if (!$('#upload-preview').length) event.preventDefault();
});
