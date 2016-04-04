$(function(){
	/**
	 * Login
	 */
	$(".btnSignIn").click(function () {
//		$('body').loading();
		$('#login-form').submit();
		return false;
    });
	/**
	 * Register
	 */
	$(".buttons .signin").click(function () {
		console.log(5);
		$("#chk_tos").closest('.policy').removeClass('color_error');
		if($("#chk_tos").is(':checked') == true){
			$('#register-form').submit();
		}else{
			$("#chk_tos").closest('.policy').addClass('color_error');
		}
		return false;
	});
	/**
	 * Chose language
	 */
	$('.language_form ul li ol').hide();
	$(".language_form .choseLang .choseLangOpt").on("click", function () {
		if($(this).attr('rel')){
			window.location.href = $(this).attr('rel');
		}
		return false;
	});
	$(".select_style").on("click", function () {
		$('.language_form ul li ol').fadeToggle();
		return false;
	});
	/**
	 * Terms
	 */
	$(".terms").click(function () {
		$('.term_plun').fadeIn();
		$('.term_plun').center();
		return false;
	});
	
	$(".term_plun .close_term").click(function () {
		$('.term_plun').fadeOut();
		return false;
	});

	$(".receiveInvitationCode").click(function () {
		$("#receive-invitation-form .msg").html('');
		if($('.input_txt_email').val().length > 4){
			$.ajax({
		        type: "POST",
		        data: $('#receive-invitation-form').serialize(),
		        url: '/beta/putEmailReInvitation',
		        success: function(data) {
		        	if(data){
		        		$("#receive-invitation-form .msg").html(data.msg);
		        	}
		        },
		        dataType: 'json'
		    });
		}
		return false;
	});
	
});

$(window).resize(function() {
	$('.term_plun').center();
});

