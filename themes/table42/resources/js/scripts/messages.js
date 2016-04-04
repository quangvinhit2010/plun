$(function(){
	Messages.msgInit();	
});

var Messages = {
	msgInit: function () {
		Messages.msgConversation();
		Messages.msgConversationMore();
		Messages.msgSend();
		Messages.msgDetailShowMore();
		Messages.msgNewMessage();
		Messages.msgReply();
		Messages.msgDetail();
		Messages.msgDelete();
	},	
	msgConversation: function () {
		objCommon.loading();
		$('.message_list .content .listConversation li').removeClass('active');
		$.ajax({
		      type: "GET",
		      url: $('.listConversation').attr( 'data-url' ),
		      success: function( data ) {
		    	  var obj = $('<div>'+ data +'</div>');
		    	  if(obj.find('.listConversation')){
		    		  $('.message_list .content .listConversation').html(obj.find('.listConversation').html());
		    	  }
		    	  if($('.message_list .content .pagging').length > 0){
		    		  $('.message_list .content .pagging').replaceWith(obj.find('.pagging'));
		    	  }else{
		    		  $('.message_list .content').append(obj.find('.pagging'));
		    	  }
		    	  objCommon.unloading();
              },
		      complete: function () 
		      { 
		    	  var firstItem = $(".message_list .content .listConversation li:first .message_item");
		    	  if(firstItem.length > 0){
		    		  Messages.msgLoadDetail(firstItem);
		    		  $('.message_list .content .listConversation li:first').addClass('active');
		    	  }
                  $('.sticky_column').fixed_col_scroll();
              }
		});
	},
	msgConversationMore: function () {
		$(document.body).on('click', '.pagging a', function(event) {
			objCommon.loading();
			var _next = $(this).attr('data-next');
			var _url = $(this).attr('data-url');
			$.ajax({
				type: "POST",
				url: _url,
				data: {'page': _next},
				dataType: 'html',
				success: function( response ) {					
					var obj = $('<div>'+ response +'</div>');
					$('.message_list .content .listConversation').append(obj.find('.listConversation').html());
					if(obj.find('.pagging')){
						$('.message_list .content .pagging').replaceWith(obj.find('.pagging'));
					}else{
						$('.message_list .content').remove();
					}
					objCommon.unloading();
					$('.sticky_column').fixed_col_scroll.callbackFun();
				}
			});
			return false;
		});		
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
			$( ".popup-alert.popup_message_user" ).pdialog({
				title: tr('Send a message'),
				open: function(event, ui) {
					$("body").css({ overflow: 'hidden' });
					objCommon.outSiteDialogCommon(this);
				},
				resizable: false,
				position: 'middle',
				draggable: false,
				autoOpen: false,
				center: true,
				width: 570,
				modal: true
			});
			return false;
		});		
	},
	msgDetailShowMore: function () {
		$(document.body).on('click', '.showmore a', function(event) {
			objCommon.loading();
			var _next = $(this).attr('data-next');
			var _url = $(this).attr('data-url');
			var _k = $(this).attr('data-key');
			$.ajax({
				type: "POST",
				url: _url,
				data: {'k': _k,'page': _next},
				dataType: 'html',
				success: function( response ) {					
					var obj = $('<div>' + response + '</div>');
					$('.message_send .content .list_message').prepend(obj.find('.list_message').html());
					if(obj.find('.showmore')){
						$('.message_send .content .showmore').replaceWith(obj.find('.showmore'));
					}else{
						$('.message_send .content .showmore').remove();
					}					
					objCommon.unloading();
					$('.sticky_column').fixed_col_scroll.callbackFun();
				}
			});
			return false;
		});		
	},
	msgNewMessage: function () {		
		$(".title .post_link").click(function (e) {
			$( ".popup-alert.popup_message_user" ).pdialog({
				title: tr('Send a message'),
				open: function(event, ui) {
					$("body").css({ overflow: 'hidden' });
					objCommon.outSiteDialogCommon(this);
					$(".ui-dialog-titlebar").show();
				},
				resizable: false,
				position: 'middle',
				draggable: false,
				autoOpen: false,
				center: true,
				width: 570,
				modal: true,
				buttons: {				
					"Cancel": function() {
						text: tr('Cancel'),
						$( this ).dialog( "close" );
					},
					"Submit": {
						text: tr('Send'),
						class: 'active',
						click: function() {
							objCommon.loading();
							$.ajax({
								type: "POST",
								url: $('#msg-form').attr('action'),
								data: $('#msg-form').serialize(),
								dataType: 'json',
								success: function( response ) {	
									$("#msg-form").trigger("reset");
									$( ".popup-alert.popup_message_user" ).pdialog('close');
									if(response.status == true){
										Util.popAlertSuccess(response.msg, response._wd);
										setTimeout(function () {
											$( ".pop-mess-succ" ).pdialog('close');
											Messages.msgConversation();
										}, 3000);
									}else if(response.status == false){
										Util.popAlertFail(response.msg, response._wd);
										setTimeout(function () {
											$( ".pop-mess-fail" ).pdialog('close');
											Messages.msgConversation();
										}, 3000);
									}
									if($('#lstUser').length > 0){
										$('#lstUser').select2('data', null)
									}				
									objCommon.unloading();
								}
							});
		                },
					}
				},
			});
			return false;
		});

	},
	msgReply: function () {
		$(document.body).on('keydown', '.replyMsg', function(event) {
			var txtMsg = $(this);
			var content = $.trim(txtMsg.val());
			if (event.which == 13 && !event.shiftKey){
				if(content.length > 0){
					objCommon.loading();
					$.ajax({
						type: "POST",
						url: $('#replymsg-form').attr( 'action' ),
						data: $('#replymsg-form').serialize(),
						success: function( response ) {
							$('.message_send .content ul').append(response);
							objCommon.unloading();
//							Messages.msgConversation();
						}
					});
					event.preventDefault();
					txtMsg.val('');
				}
				return false;
			}
		});
	},
	msgDetail: function () {
		$(document.body).on('click', '.listConversation li .message_item', function(event) {
			Messages.msgLoadDetail($(this));
			return false;
		});
	},
	msgLoadDetail: function (_this) {
		objCommon.loading();
		$(".listConversation li").removeClass("active");
		_this.parent().toggleClass("active");
		_this.parent().removeClass("unread");
		var username = _this.find(".nickname a").html();
		var k = _this.attr("data-key");
		var url = _this.attr("data-url");
		if (k)
		{
			$.ajax({
				type: "POST",
				url: url,
				data: {'k': k},
				dataType: 'html',
				success: function( response ) {
					var obj = $(response);
					$('.message_send .title .left').html(username);
					$('.message_send .content').html(response);
					objCommon.unloading();
					$('.sticky_column').fixed_col_scroll.callbackFun();
				}
			});

		}
	},
	msgDelete: function () {
		$(document.body).on('click', '.listConversation .del', function(event) {
			var chkHasActive = $(this).parent().hasClass("active");						
			var key = $(this).attr('data-key');
			var urlDel = $(this).attr('data-url');
			var parent = $(this).parent();
			$( ".popup-alert.deleteConversation .frame_content" ).html(tr('Are you sure you want to delete this conversation?'));
			$( ".popup-alert.deleteConversation" ).pdialog({
				title: tr('Message'),
				buttons: [
							{
							  text: tr("OK"),
							  click: function() {
								  var _dialog = $( this );
								  if(chkHasActive){
									  $('.message_send .content').html('');
								  }
								  if(key.length > 0){
										objCommon.loading();
										$.ajax({
											type: "POST",
											url: urlDel,
											data: {'k': key, 'offset': $(".listConversation li").length},
											dataType: 'html',
											success: function( response ) {
												parent.remove();
												objCommon.unloading();
												_dialog.pdialog( "close" );
												Messages.msgConversation();
											}
										});
									}
							  }
							},
							{
							  text: tr("Cancel"),
							  click: function() {
								  $( this ).pdialog( "close" );
							  }
							},
						  ],
			});
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
