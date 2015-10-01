(function(window, document) {
    var start = parseInt($('#more').attr('data-start'));
    var url = $('#more').attr('data-url') + '?more';
    var term = $('#more').attr('data-search-term');

    var loadMore = function() {
        if($(window).scrollTop() > ($(document).height() - $(window).height() - 10)) {
            var thisStart = start;
            $.post(url, {term: term, start: start}).done(function(data) {
                if (!data || data == '') {
                    $(window).unbind('scroll', loadMore);
                } else {
	                $('#items-display-container').append(data);
                }
            });

            start += 50;
        }
    };

    $(window).bind('scroll', loadMore);
})(this, this.document);