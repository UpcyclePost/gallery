$(function () {

    jQuery.validator.addMethod("validEmail", function (value, element) {
        if (value == '')
            return true;
        var temp1;
        temp1 = true;
        var ind = value.indexOf('@');
        var str2 = value.substr(ind + 1);
        var str3 = str2.substr(0, str2.indexOf('.'));
        if (str3.lastIndexOf('-') == (str3.length - 1) || (str3.indexOf('-') != str3.lastIndexOf('-')))
            return false;
        var str1 = value.substr(0, ind);
        if ((str1.lastIndexOf('_') == (str1.length - 1)) || (str1.lastIndexOf('.') == (str1.length - 1)) || (str1.lastIndexOf('-') == (str1.length - 1)))
            return false;
        str = /(^[a-zA-Z0-9]+[\._-]{0,1})+([a-zA-Z0-9]+[_]{0,1})*@([a-zA-Z0-9]+[-]{0,1})+(\.[a-zA-Z0-9]+)*(\.[a-zA-Z]{2,3})$/;
        temp1 = str.test(value);
        return temp1;
    }, "Please enter a valid email address.");

    jQuery.validator.addMethod("validUsername", function (value, element) {
        return /^[a-zA-Z0-9]+$/.test(value);
    }, "Username may only contain numbers and letters");

    $('#register-form').validate({
        highlight: function (el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function (el) {
            $(el).parent('div').removeClass('has-error');
        },
        errorClass: "help-block",
        rules: {
            password: {
                required: true,
                minlength: 8
            },
            passwordConfirm: {
                equalTo: "#password"
            },
            email: {
                required: true,
                validEmail: true
            },
            userName: {
                required: true,
                validUsername: true,
                minlength:5
            }
        },
        submitHandler: function(form) {
            mixpanel.track('Sign Up',
                {
                    'Member Name': $('#user-name').val(),
                    'Email': $('#email').val()
                });
	    /*
            mixpanel.alias($('#email').val());
            mixpanel.people.set(
                {
                    'Member Name': $('#user-name').val(),
                    '$Email': $('#email').val(),
                    'Sign up Date': new Date()
                });
	    */
            form.submit();
        }
    });
});
