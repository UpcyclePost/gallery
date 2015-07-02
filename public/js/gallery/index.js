(function(window, document) {
    var start = parseInt($('#more').attr('data-start'));
    var url = $('#more').attr('data-url');
    var term = $('#more').attr('data-search-term');

    var loadMore = function() {
        if($(window).scrollTop() > ($(document).height() - $(window).height() - 10)) {
            var thisStart = start;
            $.post(url, {term: term, start: start}).done(function(data) {
                if (!data || data == '') {
                    $(window).unbind('scroll', loadMore);
                } else {
                    $('#iso-container').append(data);

                    var $container = $('#iso-' + thisStart).isotope({
                        masonry: {
                            gutter: 20
                        }
                    });
                    // layout Isotope again after all images have loaded
                    $container.imagesLoaded(function () {
                        $container.isotope('layout');
                    });

                    /*window.$container.imagesLoaded(function () {
                        window.$container.isotope('layout');
                    });*/
                }
            });

            start += 50;
        }
    };

    $(window).bind('scroll', loadMore);
})(this, this.document);