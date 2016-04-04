$(function() {
       var zIndexNumber = 1000;
       // Put your target element(s) in the selector below!
       $(".profile div").each(function() {
               $(this).css('zIndex', zIndexNumber);
               zIndexNumber -= 10;
       });
	   $("li.ppl-item").each(function() {
               $(this).css('zIndex', zIndexNumber);
			   $(this).css('position', 'relative');
               zIndexNumber -= 10;
       });
});