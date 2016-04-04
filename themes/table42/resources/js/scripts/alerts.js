$(function(){
	Alerts.init();
	Alerts.status();
	Alerts.likeStatus();
	Alerts.commentStatus();
	Alerts.getCommentsPrevious();
});

var Alerts = {
	init: function () {
		
	},
	status: function () {
		$(".alertViewStatus").on("click", function(e){
			objCommon.loading();
			var url = $(this).attr('rel');
			$.post(url, { func: true }, function(data) {								
				$( ".popup_general.popup_alert" ).html(data);
				objCommon.unloading();
				$( ".popup_general.popup_alert .list_status_detail" ).addClass('item-active');
				objCommon.sprScroll(".popup_alert ol.comment_list");
				$( ".popup_general.popup_alert" ).pdialog({
					open: function(event, ui) {
						$("body").css({ overflow: 'hidden' });
						objCommon.no_title(); // config trong file jquery-ui.js
						objCommon.outSiteDialogCommon(this);
					},
					resizable: false,
					position: 'middle',
					draggable: false,
					autoOpen: false,
					center: true,
					width: 400,
					height: 'auto',
					modal: true
				});
			},"html");
			return false;
		});
	},
	likeStatus: function () {
		var time_commentlike;	
		$(document.body).on('click', '.like_comment', function(event) {
			objCommon.loading();
			if (time_commentlike)
				clearTimeout(time_commentlike);
			comment_like = $(this);
			time_commentlike = setTimeout(function () {
				$.get(comment_like.attr("rel"), function(data) {
					if (data != false){						
						comment_like.html(data.action);
					}
					var num_like = $('.num_like a');
					num_like.html('<ins></ins>' + data.total);
					num_like.removeClass('active');
					if(data.like_id != 0){
						num_like.addClass('active');
					}
					objCommon.unloading();
				},"json");
			}, 300);
			return false;
		});
		
		$(document.body).on('click', '.link_comment', function(event) {
			$('ol.comment_list').toggle("fast");
			$('.num_comment a').toggleClass('active');
			return false;
		});
	},
	commentStatus: function () {
		$(document.body).on('keydown', '.cmt-post-text', function(event) {
			if (event.which == 13 && !event.shiftKey){
				objCommon.loading();
				var txtstring = $('ol.function li:nth-child(4) .inline-text').html();
				var newString = txtstring.replace(/\d+/, parseInt(txtstring.match(/\d+/)) + 1);				
				var form = $(this).closest(".comment-form");
				var listcomment = $(".comment_list");
				$.ajax({
				      type: "POST",
				      url: form.attr( 'action' ),
				      data: form.serialize(),
				      success: function( response ) {
				    	  var data = $(response);
				    	  listcomment.find('.mCSB_container').append(data);				    	  
				    	  $('ol.function li:nth-child(4) .inline-text').html(newString);
				    	  objCommon.unloading();
				      }
				});
				event.preventDefault();
				$(this).val('');
				return false;
			}
		});
	},
	getCommentsPrevious: function () {
		$(document.body).on('click', '.cpagging-comment', function() {
			var link = $(this);
			objCommon.loading();
			$.post($(this).attr('rel'), { func: true }, function(data) {
				$($(data).html()).insertAfter(link.parent());
				if($(data).attr('data-prevLnk').length > 0){
					link.attr('rel', $(data).attr('data-prevLnk'));
				}else{
					link.remove();
				}
//				objCommon.sprScroll(".popup_alert ol.comment_list");
				$('.popup_alert ol.comment_list').mCustomScrollbar("update");
				objCommon.unloading();
			},"html");
		});
	}
	
}
