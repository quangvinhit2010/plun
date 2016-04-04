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
		$(".alertViewStatus").live("click", function(e){
			$('body').loading();
			var url = $(this).attr('rel');
			$.post(url, { func: true }, function(data) {								
				$( ".popup-alert.alertDetail .frame_content" ).html(data);
				sprScroll('.popup-alert.alertDetail .frame_content .scroll_status_detail');
				$('body').unloading();
				$( ".popup-alert.alertDetail .list_status_detail" ).addClass('item-active');
				$( ".popup-alert.alertDetail" ).pdialog({
					title: tr('Feed'),
					width: 600,
				});
			},"html");
			return false;
		});
	},
	likeStatus: function () {
		var time_commentlike;	
		$(".like_comment").live("click", function(event){
			$('body').loading();
			if (time_commentlike)
				clearTimeout(time_commentlike);
			comment_like = $(this);
			time_commentlike = setTimeout(function () {
				$.get(comment_like.attr("rel"), function(data) {
					if (data != false){						
						comment_like.find('.inline-text').html(data.action);
					}
					var _ul = comment_like.closest('ol');
					if(_ul.find('li:first i').attr('class') == 'ismall ismall-like-unactive'){
						_ul.find('li:first i').attr('class', 'ismall ismall-like');						
					}else{
						_ul.find('li:first i').attr('class', 'ismall ismall-like-unactive');
					}
					_ul.find('li:first .inline-text').html(data.total);
					$('body').unloading();
				},"json");
			}, 300);
			return false;
		});
		
		var timer;
		$(".like_comment").live({
			'mouseover': function () {
				var string = $(this).find('.inline-text').html();
				if(parseInt(string.match(/\d+/)) > 0){
					var list_like = $(this).closest('li').find('.list_like');
					var list_like_data = $(this).closest('li').find('.list_like.have_data');
					if(list_like.length > 0){
						if(list_like_data.length > 0){
							list_like.show();
						}else{
							timer = setTimeout(function () {
								$.post(list_like.attr('data-url'), { func: list_like.attr('data-url') }, function(data) {
									list_like.html(data);
									list_like.addClass('have_data');
									list_like.show();
								},"html");
							}, 500);
						}
					}
				}
			},
			'mouseout' : function () {
				var list_like = $(this).closest('li').find('.list_like');
				list_like.hide();	    	
				clearTimeout(timer);
			}
		});
	},
	commentStatus: function () {
		
		$(".btn-comment").live("click", function(event){			
			$(this).closest(".list_status_detail").toggleClass("item-active");
			$(this).closest(".list_status_detail").find(".cmt-post-text").focus();
			
			var _ul = $(this).closest('ol');
			if(_ul.find('li:nth-child(2) i').attr('class') == 'ismall ismall-comment-unactive'){
				_ul.find('li:nth-child(2) i').attr('class', 'ismall ismall-comment');
			}else{
				_ul.find('li:nth-child(2) i').attr('class', 'ismall ismall-comment-unactive');
			}
		});
		
		$(".list_status_detail .cmt-post-text").live("keydown", function(event){
			if (event.which == 13 && !event.shiftKey){
				$('body').loading();
				var string = $('.nav-right ol li:nth-child(2) .inline-text').html();
				newString = string.replace(/\d+/, parseInt(string.match(/\d+/)) + 1);				
				var form = $(this).closest(".comment-form");
				var listcomment = $(this).closest(".area").find(".comment-list ul")
				$.ajax({
				      type: "POST",
				      url: form.attr( 'action' ),
				      data: form.serialize(),
				      success: function( response ) {
				    	  var data = $(response);
				    	  $(listcomment).append(data);
				    	  $('.nav-right ol li:nth-child(2) .inline-text').html(newString);
				    	  $('body').unloading();
				      }
				});
				event.preventDefault();
				$(this).val('');
				return false;
			}
		});
	},
	getCommentsPrevious: function () {
		$(".cpagging-comment").live("click", function(event){
			var link = $(this);
			$('body').loading();
			$.post($(this).attr('rel'), { func: true }, function(data) {
				$($(data).html()).insertAfter(link.parent());
				if($(data).attr('data-prevLnk').length > 0){
					link.attr('rel', $(data).attr('data-prevLnk'));
				}else{
					link.remove();
				}
				$('body').unloading();
			},"html");
		});
	}
	
}
