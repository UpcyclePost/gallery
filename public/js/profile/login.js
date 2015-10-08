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
        },
        submitHandler: function(form) {
            mixpanel.track('Sign In',
                {
                    'Email': $('#email').val()
                });
	    /*
            mixpanel.identify($('#email').val());
            mixpanel.people.set(
                {
                    '$Email': $('#email').val(),
                    'Last Sign In': new Date()
                });
	    */
            form.submit();
        }
    })
});
