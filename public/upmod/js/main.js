$(document).ready(function() {
    $('#hamburger, .menu-toggle').click(function() {
        $('.main-dd-menu, .mainmenu').toggleClass('active');
    });
});

$(document).ready(function() {
	function rotateTestimonial(){
		$('.testimonial').each(function () {
		    if($(this).hasClass('visible')) {
		        $(this).hide(0,function () { 
		        $(this).removeClass('visible');
		        $(this).next().setVis
	        	});
                    if($(this).next().size()) {
	                $(this).next().show(0,function () {
        	    	    $(this).removeClass('hidden');
                            $(this).addClass('visible');
 		            });
		        } else {
        		    $(this).parent().children().first().show(0,function () {
	        	    	$(this).removeClass('hidden');
	                	$(this).addClass('visible');
		               });
	        	}
	             return false
		     }
		}); 
	       }
	setInterval(rotateTestimonial,10000);
	}); 
