(function(window, document) {
    function Follower() {
        this.init();
    }

    Follower.prototype = {
        init: function() {
            $('.follow').bind('click', this.follow);
        },

        follow: function(event) {
            event.preventDefault();

            try
            {
                $.ajax($(event.target).attr('data-url')).done(function(d) {
                    var data = $.parseJSON(d);
                    console.log(data);
                    if (data.hasChanged)
                    {
                        if (data.hasOwnProperty('text'))
                        {
                            var icon = (data.subscribed) ? 'fa fa-check' : 'fa fa-plus';
                            $(event.target).html('<i class="' + icon + '"></i> ' + data.text);
                        }
                    }
                });
            }
            catch (e) { console.log(e); }
        }
    };

    window._up_Follower = new Follower();
})(this, this.document);