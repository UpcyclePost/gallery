$(function() {
    $("#get-paid-form").validate({
        rules: {
            "creditCardNumber": {
                required: true,
                creditcard: true
            },
            bankAccountNumber: {
                required: true,
                digits: true,
                minlength: 7,
                maxlength: 18
            },
            bankRoutingNumber: {
                required: true,
                digits: true,
                minlength: 9,
                maxlength: 9
            },
            confirmRoutingNumber: {
                equalTo: "#bank-routing-number"
            }
        },
        submitHandler: function(form)
        {
            var $form = $(form);

            // Disable the submit button to prevent repeated clicks
            $form.find('button').prop('disabled', true);

            // Create Credit Card Token
            Stripe.card.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);

            // Prevent the form from submitting with the default action
            return false;
        }
    });

    Stripe.setPublishableKey(publishableKey);
});

function stripeResponseHandler(status, response) {
    var $form = $('#get-paid-form');

    if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
    } else {
        // response contains id and card, which contains additional card details
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // and submit
        $form.get(0).submit();
    }
}