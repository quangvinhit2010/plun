var WhitePartyManila = {
	init: function (message, url) {
		var self = this;
		WhitePartyManila.make_notify_box(message, url);
		$('.alert-box .btn-common').click(function(e){
			WhitePartyManila.load_event($(this).attr('href'));
			e.preventDefault();
		});
		$('.alert-box .btn-close-alert').click(function(e){
			WhitePartyManila.remove_notify_box();
			e.preventDefault();
		});
		$(document).on('click', '#claim-wrap .icon-back, #claim-wrap .btn-cancel', function(e){
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
			$('#white-party-manila-form .submit-wait').show();
			var request = $(this).serialize();
			var url = $(this).attr('action');
			$.post(url, request, function( data ) {
				$('#white-party-manila-form .submit-wait').hide();
				if(data['errors'] != '' ) {
					$.each(data['errors'], function(index, value){
						$('#WhitePartyManila_'+index).after('<div class="errorMessage">'+value+'</div>');
					})
				} else {
					$('#claim-wrap .content-in').fadeOut(function(){
						$('#claim-wrap .content-in').html('<div class="success-txt"><p>Congratulations!, you have successfully claimed a White Ticket to White Party Manila 2014. The confirmation code is: <span>'+data['code']+'</span>.</p><p> We\'ve also sent a copy to your PLUN inbox. You can claim it at any SM Tickets outlet nationwide with a valid ID.</p></div>');
						$(this).fadeIn();
						WhitePartyManila.remove_notify_box();
					});
				}
			}, 'json');
			e.preventDefault();
		});
	},
	make_notify_box: function(message, url) {
		var notify = '<div class="alert-box"><div class="content-alert"><a class="right btn-common" href="'+url+'">claim</a><p>'+message+'</p><span class="btn-close-alert icon-common">&nbsp;</span></div></div>';
		$('.main_page_site').prepend(notify);
	},
	remove_notify_box: function() {
		//$('.main_page_site > .alert-box').animate({marginTop: '-50px'});
		$('.main_page_site > .alert-box').removeClass('show');
	},
	load_event: function(url) {
		$('body').loading();
		$.get(url, function(html){
			$('body').loading();
			$('#page').append('<div id="claim-wrap" style="display: none; position: fixed; width: 100%; height: 100%; top: 0px; left: 0px;">'+html+'</div>');
			$('#claim-wrap').fadeIn();
			$('body').unloading();
		});
	}
}