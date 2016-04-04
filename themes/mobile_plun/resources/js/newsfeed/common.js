$(function(){
	var timeAlertNewsFeed = 0;
	NewsFeed.commentStatus();
	NewsFeed.likeStatus();
	NewsFeed.listUserLiked_init();
	NewsFeed.getCommentsPrevious();
	//NewsFeed.expandCollapse();
	NewsFeed.collapseStatusProfile();
});



var NewsFeed = {
	listUserLiked_init: function () {
		$("ul.feed-list-item li.item:last").find('.list_like').removeClass('list_like_down');
		$("ul.feed-list-item li.item:last").find('.list_like').addClass('list_like');
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
					if(comment_like.find('i').attr('class') == 'ismall ismall-like-unactive'){
						comment_like.find('i').attr('class', 'ismall ismall-like');
					}else{
						comment_like.find('i').attr('class', 'ismall ismall-like-unactive');
					}
					$('body').unloading();
				},"json");
			}, 300);
			return false;
		});
		
		var timer;
		$('.nav-right .list_like').live({
		    'mouseover': function () {
		    	$(this).show();
		    },
		    'mouseout' : function () {
		    	$(this).hide();
		    }
		});
		
		$(".moreUserLike").live("click", function(e){
			var obj = $(this);
			var timer;
			$('body').loading();
			timer = setTimeout(function () {			
				$.post(obj.attr('rel'), { func: true }, function(data) {
					$( ".popup-alert.list-userliked .frame_content" ).html(data);
					$( ".popup-alert.list-userliked" ).pdialog({
						title: tr('People Who Like This'),
					});
					$('body').unloading();
				},"html");
			}, 500);
//			clearTimeout(timer);
			return false;
		});
	},
	commentStatus: function () {
		$(".btn-comment").live("click", function(event){
			$(this).closest(".item").toggleClass("item-active");
			$(this).closest(".item").find(".cmt-post-text").focus();
			if($(this).find('i').attr('class') == 'ismall ismall-comment-unactive'){
				$(this).find('i').attr('class', 'ismall ismall-comment');
			}else{
				$(this).find('i').attr('class', 'ismall ismall-comment-unactive');
			}
		});
		
		$(".feed-list-item").on("keydown", ".cmt-post-text", function(event){
			if (event.which == 13 && !event.shiftKey){
				$('body').loading();
				var form = $(this).closest(".comment-form");
				var listcomment = $(this).closest(".area").find(".comment-list ul");    
				var itemNewsfeed = $(this).closest(".item");
				var string = itemNewsfeed.find(".btn-comment").find('.inline-text').html();
				newString = string.replace(/\d+/, parseInt(string.match(/\d+/)) + 1);
				$.ajax({
				      type: "POST",
				      url: form.attr( 'action' ),
				      data: form.serialize(),
				      success: function( response ) {
				    	  var data = $(response);
				    	  $(listcomment).append(data);
				    	  itemNewsfeed.find(".btn-comment").find('.inline-text').html(newString);
				    	  $('body').unloading();
				      }
				});
				event.preventDefault();
				$(this).val('');
				return false;
			}
		});
	},
	PostStatus: function (){
		$(".type_status").on("keydown", ".cmt-post-status", function(event){
			if (event.which == 13 && !event.shiftKey){
				$('body').loading();
				var form = $("#wall-status-form");
				var status = $(this).val();								
				if(status.length > 0){
					$.ajax({
						type: "POST",
						url: form.attr( 'action' ),
						data: form.serialize(),
						success: function( response ) {
							$("#wall-status-form .status").val('');
							window.location.reload();
						}
					});
					$(this).val('');
				}
				return false;
			}
		});
	},
	show_more_newsfeed: function (alias, is_feed) {
		var data = {
	        offset: parseInt($('#newsfeed_offset').val())
	    };
	    if($('#newsfeed_offset_after').attr('id')){
	        var limit   =   parseInt($('#newsfeed_offset_after').val());
	    }else{
	        var limit   =   $('#newsfeed_offset_first').val();
	    }
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/NewsFeed/ViewMore/?alias=' + alias + '&is_feed=' + is_feed,
	        success: function(data){
	            //$('.show-more').remove();
	            $('.feed-list-item').append(data);
	            
	            //process with scrollbar
	            
	            $('.item_showmore').fadeIn(500);
	            if($(".profile_setting_new").length > 0){
	            	$(".profile_setting_new").mCustomScrollbar("destroy");
	            	$('.profile_setting_new').mCustomScrollbar({
	            		scrollInertia: "0",
	            		mouseWheelPixels: "auto",
	            		autoHideScrollbar: true,
	            		advanced:{
	            			updateOnContentResize: true,
	            			contentTouchScroll: true
	            		}
	            	});
	            	$('.profile_setting_new').mCustomScrollbar("scrollTo","bottom");
	            }
	            
	            var showmore_offset = parseInt($('#newsfeed_offset').val());
	            if(showmore_offset > 0){
	                $('#newsfeed_offset').val(showmore_offset + parseInt(limit));
	            }            
	            NewsFeed.listUserLiked_init();
	        },
	        dataType: 'html'
	    });
	},
	hide_showmore_bt: function () {
	    $('.more-wrap-col2').remove();
	},
	MyNewsFeed_init: function () {		
		$('.col-feed').removeClass('col-left');
		$('.col-right .members').addClass('feed-hidden');
		/*
		if(usercurrent){
			var stt = $.cookie(usercurrent + "_explore"); 
			if( stt == 1){
				$('.col-feed').addClass('col-left');
				$('.col-right .members').removeClass('feed-hidden');
				
			}
		}	
		*/		
	},
	switchImg: function () {
		var btn = $('.members.feed-hidden .btn-top.btn-open-feed.hasNewsFeed .imed');
		if(btn.hasClass('imed-arrow-right')){
			btn.removeClass('imed-arrow-right');
			btn.addClass('imed-arrow-right-active');
		}else{
			btn.addClass('imed-arrow-right');
			btn.removeClass('imed-arrow-right-active');
		}
		return setTimeout(function() { NewsFeed.switchImg(); }, 500);
		
	},
	expandCollapse: function () {		
		$(".btn-hide").live("click", function(e){
			if(usercurrent){
				$.cookie(usercurrent + "_explore", 0);
			}
			$(".col-feed").removeClass("col-left");
			$(".members").addClass("feed-hidden");
		});
		$(".btn-open-feed").live("click", function(e){
			if(usercurrent){
				$.cookie(usercurrent + "_explore", 1);
			}
			$('.members .btn-top.btn-open-feed').removeClass('hasNewsFeed');
			$('.members .btn-top.btn-open-feed .imed').removeClass('imed-arrow-right-active');
			$('.members .btn-top.btn-open-feed .imed').addClass('imed-arrow-right');
			$(".col-feed").addClass("col-left");
			$(".members").removeClass("feed-hidden");
		});
	},
	collapseStatusProfile: function () {
		$(".profile_setting_new .feed-list-item").hide();
		$(".user-info .more-wrap-col2").hide();
		$(".status-collapse").addClass('collapsed');
		
		$(".profile-collapse").live("click", function(e){
			var infoCollapse = $(".info-wrap .info-top").is(":visible");
			var sttCollapse = $(".profile_setting_new .feed-list-item").is(":visible");
			$(".info-wrap .info-top").toggle("blind", {}, 500);
			if(infoCollapse == true){
				$(this).addClass('collapsed');
			}else{
				$(this).removeClass('collapsed');
			}
			if(sttCollapse == true){
				$(".status-collapse").addClass('collapsed');
			}else{
				$(".status-collapse").removeClass('collapsed');
			}
			$(".profile_setting_new .feed-list-item").toggle("blind", {}, 500);
			$(".user-info .more-wrap-col2").toggle("blind", {}, 500);
		});
		$(".status-collapse").live("click", function(e){
			var infoCollapse = $(".info-wrap .info-top").is(":visible");
			var sttCollapse = $(".profile_setting_new .feed-list-item").is(":visible");
			$(".profile_setting_new .feed-list-item").toggle("blind", {}, 500);
			$(".user-info .more-wrap-col2").toggle("blind", {}, 500);
			if(sttCollapse == true){
				$(this).addClass('collapsed');
			}else{
				$(this).removeClass('collapsed');
			}
			if(infoCollapse == true){
				$(".profile-collapse").addClass('collapsed');
			}else{
				$(".profile-collapse").removeClass('collapsed');
			}
			$(".info-wrap .info-top").toggle("blind", {}, 500);
		});
	},
	limitViewProfileInDay: function (msg, _wd) {
		Util.popAlertFail(msg, _wd);
		setTimeout(function () {
			$( ".pop-mess-fail" ).pdialog('close');
		}, 3000);
	},
}
