var WhitePartyManila = {
	init: function (message, url) {
		var temp_value;
		var self = this;
		WhitePartyManila.make_notify_box(message, url);
		$('.alert_white_party .link_claim').click(function(e){
			WhitePartyManila.load_event($(this).attr('href'));
			e.preventDefault();
		});
		$('.alert_white_party .close_button').click(function(e){
			WhitePartyManila.remove_notify_box();
			e.preventDefault();
		});
		$(document).on('click', '.claim_white_party .close_button, .btn_claim .cancel', function(e){
			$('#claim-wrap').fadeOut(function(){
				$(this).remove();
			});
			e.preventDefault();
		});
		$(document).on('click', '.btn_claim .submit', function(){
			$('#white-party-manila-form').submit()
		});
		$(document).on('submit', '#white-party-manila-form', function(e){
			$('#white-party-manila-form .errorMessage').remove();
			$(this).find('> ul > li').removeClass('error');
			$('.claim_white_party .overlay').show();
			var request = $(this).serialize();
			var url = $(this).attr('action');
			$.post(url, request, function( data ) {
				$('.claim_white_party .overlay').hide();
				if(data['errors'] != '' ) {
					$.each(data['errors'], function(index, value){
						$('#WhitePartyManila_'+index).closest('li').addClass('error');
						$('#WhitePartyManila_'+index).next().after('<div class="errorMessage">'+value+'</div>');
					})
				} else {
					$('#claim-wrap .claim_white_party').fadeOut(function(){
						$('#claim-wrap > div').html('<div class="claim_white_party confirm_claim_popup"><a href="#" class="close_button"></a><p class="confirm_claim">Congratulations!, you have successfully claimed a White Ticket to White Party Manila 2014. The confirmation code is: '+data['code']+'. We\'ve also sent a copy to your PLUN inbox. You can claim it at any SM Tickets outlet nationwide with a valid ID.</p></div>');
						
						$(this).fadeIn();
						WhitePartyManila.remove_notify_box();
					});
				}
			}, 'json');
			e.preventDefault();
		});
		$(document).on('click', '#claim-wrap', function(e){
			if($(e.target).attr('id')=='claim-wrap')
				$(this).fadeOut(function(){
					$(this).remove();
				});
			e.preventDefault();
		});
		$(document).on('focus', '#white-party-manila-form input', function(){
			temp_value = $(this).val();
		});
		$(document).on('blur', '#white-party-manila-form input', function(){
			var parent = $(this).closest('li');
			if(parent.hasClass('error')) {
				if(temp_value != $(this).val()) {
					parent.removeClass('error');
					parent.find('.errorMessage').remove();
				}
			}
		});
	},
	make_notify_box: function(message, url) {
		var notify = '<div class="left alert_white_party" style="top: -45px;"><p>'+message+' <a href="'+url+'" class="link_claim">claim</a></p><a class="close_button"></a></div>';
		$('body').prepend(notify);
		$('body > .alert_white_party').animate({top: '0px'});
		$('.col-nav').animate({top: '45px'});
		$('.plun').animate({top: '45px'});
	},
	remove_notify_box: function() {
		$('body > .alert_white_party').animate({top: '-45px'});
		$('.col-nav').animate({top: '0px'});
		$('.plun').animate({top: '0px'});
	},
	load_event: function(url) {
		$('body').loading();
		$.get(url, function(html){
			$('body').loading();
			$('body').append('<div id="claim-wrap" style="display: none; position: fixed; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgba(0, 0, 0, 0.5); z-index: 999;"><div style="position: fixed; top: 50%; margin-top: -186px; left: 50%; margin-left: -295px;">'+html+'</div></div>');
			$('#claim-wrap').fadeIn();
			$('body').unloading();
		});
	}
}