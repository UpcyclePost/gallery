$(function() {
    $("#payment-form").validate({
        rules: {
            "creditCardNumber": {
                required: true,
                creditcard: true
            }
        },
        submitHandler: function(form)
        {
            var $form = $(form);

            // Disable the submit button to prevent repeated clicks
            $form.find('button').prop('disabled', true);

            Stripe.card.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val(),
                name: $('.name').val(),
                address_line_1: $('.billing-address').val(),
                address_city: $('.billing-city').val(),
                address_state: $('.billing-st').val(),
                address_zip: $('.billing-zip').val()
            }, stripeResponseHandler);

            // Prevent the form from submitting with the default action
            return false;
        }
    });

    Stripe.setPublishableKey(publishableKey);
});

function stripeResponseHandler(status, response) {
    var $form = $('#payment-form');

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