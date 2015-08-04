var __items = [
	{ img: "img/p/AMY COUSIN.PNG", caption: "Take a Look!", link:"http://www.upcyclepost.com"},
    { img: "img/p/ART BY HEART STUDIO 2.png", caption: "Take a Look!", link:"http://www.upcyclepost.com"},
    { img: "img/p/ATOMICROCKET POP LAB.png", caption: "Take a Look!", link:"http://www.upcyclepost.com"},
    { img: "img/p/ARTZWEAR.png", caption: "Take a Look!", link:"http://www.upcyclepost.com"},
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