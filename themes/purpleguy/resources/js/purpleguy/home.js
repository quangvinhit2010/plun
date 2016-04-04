$(function(){
});

var Home = {
	joinPurpleguy: function(url){
		$('.login-link').live('click', function(){
			$( ".popup_login" ).pdialog({
				title: tr('Login'),
				width: 440,
			});	
			$(".ui-dialog-titlebar").hide();
			return false;
		});
	},
};
