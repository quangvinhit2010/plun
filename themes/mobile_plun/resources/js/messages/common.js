$(function(){
	Messages.msgInit();
	Messages.msgSend();
	Messages.msgNewMessage();
	Messages.msgReply();
	Messages.msgDetail();
	Messages.msgDelete();
});

var Messages = {
	msgInit: function () {
		Messages.msgSetWidth();
		var firstItem = $(".news-message .feed-list-item .item:first.item-active");
		if(firstItem.length > 0){
			if(!firstItem.hasClass('loaded')){
				Messages.msgLoadDetail(firstItem.find('a.btn-messagemore'));
				firstItem.addClass('loaded');
			}
		}
	},
	msgSetWidth: function () {
		$(".message-list-detail").css("width",$('.main').width() - 545);
		$(".title_message").css("width",$('.main').width() - 544);
		if($(".col-right .message-detail .message-list").length > 0){			
			$(".message-detail ul li .right").css("width",$('.message-list-detail').width() - 55);
		}
	},
	msgSend: function () {
		$(".profile-tool .message").click(function () {
			$( ".popup-alert.new-messages" ).pdialog({
				title: tr('Send a message'),
				width: 600,
			});
			return false;
		});		
	},
	msgNewMessage: function () {		
		$(".title_message .right a").click(function () {
			$( ".popup-alert.new-messages" ).pdialog({
				title: tr('Send a message'),
				width: 600,
			});
			return false;
		});
		$("#msg-form .btnSend").unbind('click').bind('click', function(){
			if( $('#msg-form .msgBody').val().length > 0){
				$('body').loading();
				$.ajax({
					type: "POST",
					url: $('#msg-form').attr('action'),
					data: $('#msg-form').serialize(),
					dataType: 'json',
					success: function( response ) {	
						$("#msg-form").trigger("reset");
						$( ".popup-alert.new-messages" ).pdialog('close');
						if(response.status == true){
							Util.popAlertSuccess(response.msg, response._wd);
							setTimeout(function () {
								$( ".pop-mess-succ" ).pdialog('close');
							}, 3000);
							window.location = $('.nav-msg .iconmenu_message').attr('href');

						}else if(response.status == false){
							Util.popAlertFail(response.msg, response._wd);
							setTimeout(function () {
								$( ".pop-mess-fail" ).pdialog('close');
							}, 3000);
						}
						if($('#lstUser').length > 0){
							$('#lstUser').select2('data', null)
						}					
						
						$('body').unloading();
					}
				});
			}
			return false;
		});
		
		/*$("#msg-form .btnCancel").click(function () {
			$("#msg-form").trigger("reset");
			$( ".popup-alert.new-messages" ).pdialog('close');
			if($('#lstUser').length > 0){
				$('#lstUser').select2('data', null);
			}
			return false;
		});*/
	},
	msgReply: function () {
		$(".replyMsg").unbind('keydown').bind('keydown', function(event){
			var txtMsg = $(this);
			var content = $.trim(txtMsg.val());
			if (event.which == 13 && !event.shiftKey && content.length > 0){
				event.preventDefault();
				var data = $('#replymsg-form').serialize();
				txtMsg.val('');
				$('body').loading();
				$.ajax({
				      type: "POST",
				      url: $('#replymsg-form').attr( 'action' ),
				      data: data,
				      success: function( response ) {
				    	  txtMsg.closest('.pad_left_10').find('.list_message_detail').append(response);
				    	 /* txtMsg.closest('.message-detail').find(".message-list").mCustomScrollbar("update");
				    	  txtMsg.closest('.message-detail').find(".message-list").mCustomScrollbar("scrollTo","bottom");*/
				    	  txtMsg.val('');
				    	  txtMsg.focus();
				    	  //$(".message-detail ul li .right").css("width",$('.message-list-detail').width() - 55);
				    	  $('body').unloading();
				      }
				});
				return false;
			}
		});
		
		$(".type_message_detail .btnSend").unbind('click').bind('click', function(){
			var e = jQuery.Event( 'keydown', { which: $.ui.keyCode.ENTER } );
			$('.replyMsg').trigger(e);
			return false;
		});
	},
	msgDetail: function () {
		$(".news-message .btn-comment").click(function(){
			$(this).closest(".item").toggleClass("item-active");
			$(this).closest(".item").find(".cmt-post-text").focus();
		});
		$(".news-message .btn-messagemore").unbind('click').bind('click', function(){		
			Messages.msgLoadDetail($(this));
			return false;
		});
		$(".news-message .btn-minimize").click(function(){
			$(this).closest(".item").toggleClass("item-active");
		});
	},
	msgLoadDetail: function (_this) {
		$('body').loading();
		$(".feed-list-item .item").removeClass("item-active");
		_this.closest(".item").toggleClass("item-active");
		_this.closest(".item").removeClass("unread");
		var username = _this.find(".info h4").html();
		var k = _this.attr("rel");
		var url = _this.attr("href");
		var ml = $('.message-detail').width();
		if (k)
		{
			$.ajax({
				type: "POST",
				url: url,
				data: {'k': k},
				dataType: 'html',
				success: function( response ) {
					var obj = $(response);
					$('.col-right .message-detail').html(response);
					$('.col-right .title_message .left').html(username);
					$('.col-right .message-detail .message-list').mCustomScrollbar({
						scrollInertia: "0",
						mouseWheelPixels: "auto",
						autoHideScrollbar: true,
						advanced:{
							updateOnContentResize: true,
							contentTouchScroll: true
						}
					});
					$('.col-right .message-detail .message-list').mCustomScrollbar("scrollTo","bottom");
					Messages.msgSetWidth();
					$('body').unloading();
				}
			});

		}
	},
	msgDelete: function () {
		$(".btn-delete").unbind('click').bind('click', function(){
			if(confirm(tr("Are you sure you want to delete this conversation?"))){
				var delLink = $(this).attr('rel');
				if(delLink.length > 0){
					var offset = $('#offsetMsg').val();
					var urlDel = $('#urlDel').val();
					var quotas = parseInt($('#quotasMsg').val());
					var anount_quota = parseInt($('.anount_quota b').html());
					var _this_msg = $(this).parent().parent();
					$('body').loading();
					$.ajax({
						type: "POST",
						url: urlDel,
						data: {'k': delLink, 'offset': offset},
						dataType: 'html',
						success: function( response ) {
							
							$(_this_msg).remove();
							/*$('.list_message_page .feed-list-item .item').each(function(){
								
								var k2 = $(this).find('.btn-messagemore').attr('rel');	        
								if(k2 == delLink){
									$(this).remove();
									if($(this).hasClass("item-active")){
										$('.col-right .message-detail').html('');
									}
								}
								
							});*/
							
							
							var new_offset = parseInt(offset) + 1;
							$('#offsetMsg').val(new_offset);
							$(response).insertBefore($('.feed-list-item .item:first'));
							var total = parseInt($(".feed-list-item li.user").length);
							$('.anount_quota b').html( anount_quota - 1);
							if( total < quotas){
								var percent1 = (total/quotas > 1) ? 1 : total/quotas;
								var percent = number_format((percent1)*100);
								var style = 'width:' + percent + '%;';
								$('.loading_status p b:first').html(percent + '%')
								$('.loading_percent').html('<label style="' + style + '"></label>');
								$('.anount_quota').hide();
							}
							
							$('body').unloading();
						}
					});
				}
				
			}
			return false;
		});
		
	},
}



function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/B(?=(?:d{3})+(?!d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
