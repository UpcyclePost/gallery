(function(window, document) {
    function Post() {
        this.init();
    }

    Post.prototype = {
        init: function() {
            $('#up-it').bind('click', this.up);
            $('#share-form').bind('submit', this.share);
            $('#report-it').bind('click', this.report);
        },

        up: function(event) {
            try {
                var c = parseInt($('#ups').html());
                c++;

                $('#ups').html((c + '').replace(/(\d)(?=(\d{3})+$)/g, '$1,'));
                $($('#up-it').parent()[0]).html('<a><i class="fa fa-heart"></i> You like this</a>');

                $.ajax($(event.target).attr('data-url'));
            } catch (e) {

            }

            $('#up-it').unbind('click', this.up);

            event.preventDefault();
        },

        share: function(event) {
            var email = $('#shareEmail').val();
            var message = $('#shareMessage').val();

            $.post($(event.target).attr('data-url'), {
                email: email,
                message: message
            });

            $('#shareEmail').val('');
            $('#shareMessage').val('');

            $("#share").dropdown("toggle");

            event.preventDefault();
        },

        report: function(event) {
            $.ajax($(event.target).attr('data-url'));

            $($('#report-it').parent()[0]).html('<a><i class="fa fa-warning"></i> <strong>Post has been flagged</strong></a>');

            event.preventDefault();
        }
    };

    window._up_Post = new Post();
})(this, this.document);