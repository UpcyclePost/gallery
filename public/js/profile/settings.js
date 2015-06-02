$(function() {
    $('#change-password-form').validate({
        highlight: function(el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function(el) {
            $(el).parent('div').removeClass('has-error');
        },
        errorClass: "help-block",
        rules: {
            newPassword: {
                required: true,
                minlength: 8
            },
            newPasswordConfirm: {
                equalTo: "#newPassword"
            }
        }
    });

    jQuery.validator.addMethod("validUsername", function (value, element) {
        return /^[a-zA-Z0-9]+$/.test(value);
    }, "Username may only contain numbers and letters");

    $('#account-settings-form').validate({
        highlight: function (el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function (el) {
            $(el).parent('div').removeClass('has-error');
        },
        errorClass: "help-block",

        rules: {
            userName: {
                required: true,
                validUsername: true,
                minlength:5
            }
        }
    });
});