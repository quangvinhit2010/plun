$(function(){
	Landing.mustLogin();
});

var Landing = {	
	mustLogin: function(){
		$(document.body).on("click", '.wrapper_container a, .wrapper_main_menu a',function(){
	        $( "#popupRegisLogin" ).pdialog({
	            open: function(event, ui) {
	                objCommon.no_title(); // config trong file jquery-ui.js
	                objCommon.outSiteDialogCommon(this);
	            },
	            width: 345
	        });
        });
	}
}
