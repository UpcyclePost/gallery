$(function() {
    $(window).load(function() {
        // initialize Isotope
        var $container = $('#iso').isotope({
            masonry: {
                gutter: 20
            }
        });

        $container.isotope('option', { animationEngine: 'css', 'transitionDuration': 0 }).addClass('transitions-disabled');

        // layout Isotope again after all images have loaded
        $container.imagesLoaded(function () {
            if ($(window).width() <= 1024)
            {
                setTimeout(function() {
                    $container.isotope('layout');
                    setTimeout(function() {
                        $container.isotope('layout');
                    }, 1000);
                }, 750);
            }
            else
            {
                $container.isotope('layout');
            }
        });
    });

    if ($('#shop-iso').length)
    {
        $shopContainer = $('#shop-iso').isotope({
            masonry: {
                gutter: 20
            }
        });

        $shopContainer.isotope('option', { animationEngine: 'css' }).addClass('transitions-disabled');

        // layout Isotope again after all images have loaded
        $shopContainer.imagesLoaded(function () {
            $shopContainer.isotope('layout');
        });
    }
});