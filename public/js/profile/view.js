(function(window, document) {
    function Profile()
    {
        this.init();
    }

    Profile.prototype = {
        init: function() {
            $('.deletePost').bind('click', this.remove);
        },

        remove: function(event) {
            $.post($(event.target).attr('data-url'), { }, function(data) {
                var result = $.parseJSON(data);
                if (result.success)
                {
                    $(event.target).closest('article').hide();
                    $(event.target).parent().html('Deleted');

                    $('#iso').isotope('layout');
                }
            });
        }
    }

    window._up_Profile = new Profile();
})(this, this.document);