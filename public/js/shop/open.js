$(function() {
    $.validator.addMethod("urlRegex", function(value, element) {
        return this.optional(element) || /^[A-Za-z][a-z0-9\-]+$/i.test(value);
    }, "Address must contain only letters, numbers, or dashes.");

    $("#open-shop-form").validate({
        rules: {
            "shopUrl": {
                required: true,
                urlRegex: true,
            }
        },
        messages: {
            "shopUrl": {
                required: "You must enter a shop address",
                loginRegex: "Your shop address can only contain letters, numbers, and dashes."
            }
        }
    });
});