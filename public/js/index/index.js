$("#bg-slide").backstretch([
    "/img/slider/2015/mar/1.jpg",
    "/img/slider/2015/mar/2.jpg",
    "/img/slider/2015/mar/3.jpg",
    "/img/slider/2015/mar/4.jpg",
    "/img/slider/2015/mar/5.jpg"
], {duration: 3000, fade: 750});

$(window).load(function(){
    if ($('#myModal').length) {
        setTimeout(function() {
            $('#myModal').modal('show');

            $('#newsletter-subscribe-form').ajaxChimp({
                url: 'http://upcyclepost.us8.list-manage.com/subscribe/post?u=30f90ae9c8a620f475f37c82b&id=71699905b3',
                callback: function(resp) {
                    $.post('/newsletter/subscribe', { email: $('#mc-email').val() });
                    $('#myModal').modal('hide');
                }
            });
        }, 30000);
    }
});