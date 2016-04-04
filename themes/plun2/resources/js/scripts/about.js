$(function(){
	About.contact();	
});
var About = {
	init: function () {
	},
	contact: function () {		
		$(document.body).on('click', '#contact-form .but_submit', function() {
			var form = $(this).closest("#contact-form");
			$.ajax({
			      type: "POST",
			      url: form.attr( 'data-url' ),
			      data: form.serialize(),
			      success: function( response ) {
			    	  Util.popAlertSuccess(response.msg, 300);
			    	  setTimeout(function () {
			    		  $( ".pop-mess-succ" ).pdialog('close');			    			  
			    	  }, 3000);
			    	  if(response.status == true){		
			    		  form.trigger('reset');
			    	  }
			      }
			});			
			return false;
		});
	},
	
}
