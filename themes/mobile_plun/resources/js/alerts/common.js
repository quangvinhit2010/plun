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
			window.location.href = $(this).attr('rel');
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
						var html = data.action + ' (' + data.total + ')'
						comment_like.find('.inline-text').html(html);
					}
					$('body').unloading();
				},"json");
			}, 300);
			return false;
		});
		
		var timer;		
	},
	commentStatus: function () {
		
		$(".btn-comment").live("click", function(event){			
			$(this).closest(".list_status_detail").addClass("item-active");
			$(this).closest(".list_status_detail").find(".cmt-post-text").focus();
		});
		
		$(".list_status_detail .cmt-post-text").live("keydown", function(event){
			if (event.which == 13 && !event.shiftKey){
				$('body').loading();
				var string = $(".btn-comment").find('.inline-text').html();
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
				    	  $(".btn-comment").find('.inline-text').html(newString);
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
