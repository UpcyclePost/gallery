var __items = [
	{ img: "img/p/Banner1.PNG", caption: "Take a Look!", link:"http://www.upcyclepost.com/shops/boris"},
	{ img: "img/p/Banner2.PNG", caption: "Click Here!", link:"http://www.upcyclepost.com/shops/allan-young"},
	{ img: "img/p/Banner3.PNG", caption: "Discover!", link:"http://www.upcyclepost.com/shops/ancagray"},
	{ img: "img/p/Banner4.PNG", caption: "Take a Look!", link:"http://www.upcyclepost.com/shops/jay-lana"},
	{ img: "img/p/Banner5.PNG", caption: "Click Here!", link:"http://www.upcyclepost.com/shops/shawnduboisart"},
    { img: "img/p/BANNER RODNEY TRICE.PNG", caption: "Discover!", link:"http://www.upcyclepost.com/shops/rodneytrice"}
]

$("#bg-slide").backstretch($.map(__items, function(i) { return i.img; }), {duration: 5000, fade: 750});

$(window).on("backstretch.show", function(e, instance) {
	var newCaption = __items[instance.index].caption;
	$(".caption").text( newCaption );
	$(".slideshow-wrapper").on("click", '*', function () {document.location.href = __items[instance.index].link});
});

$(window).on("backstretch.before", function(e, instance) {
	var newCaption = __items[instance.index].caption;
	$(".caption").text( newCaption );
	$(".slideshow-wrapper").on("click", '*', function () {document.location.href = __items[instance.index].link});
});

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