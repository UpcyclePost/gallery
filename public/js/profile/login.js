$(function() {
    $('#login-form').validate({
        highlight: function(el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function(el) {
            $(el).parent('div').removeClass('has-error');
        },
        errorClass: "help-block",
        rules: {
            password: {
                required: true,
                minlength: 8
            }
        }
    })
});