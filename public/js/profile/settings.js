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

    $('#account-settings-form').validate({
        highlight: function (el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function (el) {
            $(el).parent('div').removeClass('has-error');
        },
        errorClass: "help-block",
    });
});