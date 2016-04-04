$(function(){
	Comment.init();
});

var Comment = {
	init: function(){
		Comment.list();
		Comment.showMore();
	},
	list: function(){
		$(".cmt-post-text").live("keydown", function(event){
			var content = $.trim($(this).val());
			if (event.which == 13 && !event.shiftKey && content.length > 0){
				$('body').loading();
				var form = $(this).closest(".comment-form");
				var containerComment = $(this).closest(".list_comment");    
				var listcomment = containerComment.find(".list_item");    
				var string = containerComment.find('.like_comment .comment').html();
				newString = string.replace(/\d+/, parseInt(string.match(/\d+/)) + 1);
				$.ajax({
				      type: "POST",
				      url: form.attr( 'action' ),
				      data: form.serialize(),
				      success: function( response ) {
				    	  var data = $(response);
				    	  listcomment.find('.mCSB_container').append(data);
				    	  listcomment.mCustomScrollbar("update");
				    	  listcomment.mCustomScrollbar("scrollTo","bottom");
				    	  containerComment.find('.like_comment .comment').html(newString);
				    	  $('body').unloading();
				      },
				      error: function (xhr, ajaxOptions, thrownError) {
				    	  $( ".popup_login" ).pdialog({
								title: tr('Login'),
								width: 440,
				    	  });	
				    	  $(".ui-dialog-titlebar").hide();
				    	  $('body').unloading();
				      }
				});
				event.preventDefault();
				$(this).val('');
				return false;
			}
		});
		
		$(".btnComment").live("click", function(){
			var content = $.trim($('.cmt-post-text').val());
			if (content.length > 0){
				$('body').loading();
				var form = $(this).closest(".comment-form");
				var containerComment = $(this).closest(".list_comment");    
				var listcomment = containerComment.find(".list_item");    
				var string = containerComment.find('.like_comment .comment').html();
				newString = string.replace(/\d+/, parseInt(string.match(/\d+/)) + 1);
				$.ajax({
				      type: "POST",
				      url: form.attr( 'action' ),
				      data: form.serialize(),
				      success: function( response ) {
				    	  var data = $(response);
				    	  listcomment.find('.mCSB_container').append(data);
				    	  listcomment.mCustomScrollbar("update");
				    	  listcomment.mCustomScrollbar("scrollTo","bottom");
				    	  containerComment.find('.like_comment .comment').html(newString);
				    	  $('body').unloading();
				      },
				      error: function (xhr, ajaxOptions, thrownError) {
				    	  $( ".popup_login" ).pdialog({
								title: tr('Login'),
								width: 440,
				    	  });	
				    	  $(".ui-dialog-titlebar").hide();
				    	  $('body').unloading();
				      }
				});
				$('.cmt-post-text').val('');
				return false;
			}
		});
	},
	showMore: function(){		
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
	},
};
