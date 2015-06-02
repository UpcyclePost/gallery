$(function() {
    // initialize Isotope
    var $container = $('#iso').isotope({
        masonry: {
            gutter: 20
        }
    });

    $container.isotope('option', { animationEngine: 'css' }).addClass('transitions-disabled');

    // layout Isotope again after all images have loaded
    $container.imagesLoaded(function () {
        $container.isotope('layout');
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