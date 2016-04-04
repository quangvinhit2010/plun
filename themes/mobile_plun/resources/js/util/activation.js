$(function(){
});
var Activation = {
	isActive: false,
	show: function(){
		$( ".popup-alert.activation" ).pdialog({
			title: tr('Activation Needed'),
			buttons: [
				{
				  text: tr("OK"),
				  click: function() {
					$( this ).pdialog( "close" );
				  }
				},
				{
				  text: tr("Change Email"),
				  click: function() {
					  $( this ).dialog('destroy').remove();
					  Activation.resend();
				  }
				},
			  ],
		});		
	},
	resend: function(){
		$( ".popup-alert.resend-activation" ).pdialog({
			  title: tr('Activation Needed'),
			  buttons: [
						{
						  text: tr("Send"),
						  click: function() {
							$('body').loading();  
							var dg = $( this );
							$.post($('#emailReSend').attr('data-url'), { email: $('#emailReSend').val() }, function(data) {
								if(data.stt == true){
									dg.pdialog( "close" );
								}else{
									var err = '<div style="font-size: 10px; color: red;">' + data.msg + '</div>';
									$(err).insertAfter($('#emailReSend'));
								}
//								dg.pdialog( "close" );
								$('body').unloading();
	    					},"json");
						  }
						},
					  ],
		  });
	},
	featureMustActive: function(){
		$(".accNotActivation").unbind('click').bind('click', function(){
			Activation.show();
			return false;
		});
	},
};
