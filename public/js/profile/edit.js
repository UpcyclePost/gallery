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
});