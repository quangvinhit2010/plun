$(function(){
	var timeAlertNewsFeed = 0;
	NewsFeed.commentStatus();
	NewsFeed.likeStatus();
	NewsFeed.listUserLiked_init();
	NewsFeed.getCommentsPrevious();
	NewsFeed.expandCollapse();
	NewsFeed.collapseStatusProfile();
});



var NewsFeed = {
	listUserLiked_init: function () {
		$("ul.feed-list-item li.item:last").find('.list_like').removeClass('list_like_down');
		$("ul.feed-list-item li.item:last").find('.list_like').addClass('list_like');
	},
	getCommentsPrevious: function () {
		$(document.body).on("click", '.cpagging-comment',function(){
			var link = $(this);
			objCommon.loading();
			$.post($(this).attr('rel'), { func: true }, function(data) {
				$($(data).html()).insertAfter(link.parent());
				if($(data).attr('data-prevLnk').length > 0){
					link.attr('rel', $(data).attr('data-prevLnk'));
				}else{
					link.remove();
				}
				objCommon.unloading();
			},"html");
		});
	},
	likeStatus: function () {
		var time_commentlike;
		$(document.body).on('click',".link_like a", function() {
			objCommon.loading();			
			if (time_commentlike)
				clearTimeout(time_commentlike);
			comment_like = $(this);
			time_commentlike = setTimeout(function () {
				$.get(comment_like.attr("rel"), function(data) {
					if (data != false){						
						comment_like.html(data.action);
					}
					var _ul = comment_like.closest('ol');
					if(!_ul.find('.num_like a').hasClass('active')){
						_ul.find('.num_like a').addClass('active');						
					}else{
						_ul.find('.num_like a').removeClass('active');
					}
					_ul.find('.num_like span').html(data.total);
					objCommon.unloading();
				},"json");
			}, 300);
			return false;		    
		});
		
		var timer;
		$(".like_comment").on({
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
		
		$('.nav-right .list_like').on({
		    'mouseover': function () {
		    	$(this).show();
		    },
		    'mouseout' : function () {
		    	$(this).hide();
		    }
		});
		
		$(".moreUserLike").on("click", function(e){
			var obj = $(this);
			var timer;
			objCommon.loading();
			timer = setTimeout(function () {			
				$.post(obj.attr('rel'), { func: true }, function(data) {
					$( ".popup-alert.list-userliked .frame_content" ).html(data);
					$( ".popup-alert.list-userliked" ).pdialog({
						title: tr('People Who Like This'),
					});
					objCommon.unloading();
				},"html");
			}, 500);
//			clearTimeout(timer);
			return false;
		});
	},
	commentStatus: function () {
		$(document.body).on('click', '.btn-comment', function() {
			/*$(this).closest(".item").toggleClass("item-active");
			$(this).closest(".item").find(".cmt-post-text").focus();
			
			var _ul = $(this).closest('ol');			
			if(!_ul.find('.num_comment a').hasClass('active')){
				_ul.find('.num_comment a').addClass('active');						
			}else{
				_ul.find('.num_comment a').removeClass('active');
			}*/
			/*var _this = $(this);
			if(_this.closest('li.item').hasClass('item-active')){
				_this.closest('li.item').removeClass('item-active');
			}else{
				$('.feed .content ul li.item').removeClass('item-active');
				_this.closest('li.item').addClass('item-active');	
				_this.closest('li.item').find(".cmt-post-text").focus();
			}*/
			
		});
		
		$(".feed .content").on("keydown", ".cmt-post-text", function(event){
			if (event.which == 13 && !event.shiftKey){
				objCommon.loading();
				var form = $(this).closest(".comment-form");
				var listcomment = $(this).closest(".comment_list").find("ol");    
				var itemNewsfeed = $(this).closest(".item");
				
				var string = itemNewsfeed.find('.num_comment').html();
				var new_total_comment	=	parseInt(itemNewsfeed.find('.num_comment span').text()) + 1;
				
				$.ajax({
				      type: "POST",
				      url: form.attr( 'action' ),
				      data: form.serialize(),
				      success: function( response ) {
				    	  var data = $(response);
				    	  $(listcomment).append(data);

				    	  itemNewsfeed.find('.num_comment span').text(new_total_comment);
                          $('.sticky_column').fixed_col_scroll.callbackFun();
				    	  objCommon.unloading();
				      }
				});
				event.preventDefault();
				$(this).val('');
				return false;
			}
		});
	},
	show_more_newsfeed: function (alias, is_feed) {
		objCommon.loading();
		var data = {
	        offset: parseInt($('#newsfeed_offset').val())
	    };
		var limit   =   $('#newsfeed_offset_first').val();
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/NewsFeed/ViewMore/?alias=' + alias + '&is_feed=' + is_feed,
	        success: function(data){
	            //$('.show-more').remove();
	            $('.feed .content ul').append(data);
	            
	            //process with scrollbar
	            
	            $('.item_showmore').fadeIn(500);
	            
				$('.list-preview-photo').colorbox({
		        	  slideshowAuto: false,
		        	  fixed: true,
		        	  scrolling: false,
		        	  innerHeight: true,
		        	  scalePhotos: true,
		        	     maxWidth: '100%',
		        	  maxHeight: '95%'
				});
	            objCommon.unloading();
	            var showmore_offset = parseInt($('#newsfeed_offset').val());
	            if(showmore_offset >= 0){
	                $('#newsfeed_offset').val(showmore_offset + parseInt(limit));
	            }            
	            //NewsFeed.listUserLiked_init();
	            $('.sticky_column').fixed_col_scroll.callbackFun();
	        },
	        dataType: 'html'
	    });
	},
	hide_showmore_bt: function () {
	    $('.content .pagging').remove();
	},
	MyNewsFeed_init: function () {		
		//$('.col-feed').removeClass('col-left');
		//$('.col-right .members').addClass('feed-hidden');
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
		$(".btn-hide").on("click", function(e){
			if(usercurrent){
				$.cookie(usercurrent + "_explore", 0);
			}
			$(".col-feed").removeClass("col-left");
			$(".members").addClass("feed-hidden");
		});
		$(".btn-open-feed").on("click", function(e){
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
		
		$(".profile-collapse").on("click", function(e){
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
		$(".status-collapse").on("click", function(e){
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
	editStatus: function(status_id){
		var text	=	$('.text_status_' + status_id);
		var edit	=	$('.edit_status_' + status_id);
		
		$(text).hide();
		$(edit).show();
		$('.list_func_feed').hide().removeClass('activeShowBox');
        $('.sticky_column').fixed_col_scroll.callbackFun();
	},
	closeEditStatus: function(status_id){
		var text	=	$('.text_status_' + status_id);
		var edit	=	$('.edit_status_' + status_id);		
		$(edit).hide();
		$(text).show();
		$(edit).find('textarea').val($(text).text());
        $('.sticky_column').fixed_col_scroll.callbackFun();
	},
	sendEditStatus: function(status_id){
		var text	=	$('.text_status_' + status_id);
		var edit	=	$('.edit_status_' + status_id);	
		
		var content	=	$(edit).find('textarea').val();
		if(content == ''){
        	Util.popAlertSuccess(tr('please input status'), 300);
            setTimeout(function () {
             $( ".pop-mess-succ" ).pdialog('close');
            }, 2000);
            objCommon.unloading();
            $(edit).find('textarea').val($(text).text());
            return false;			
		}
		
		$(edit).hide();
		$(text).show();
		
		if(content != $(text).text()){
			$(text).text(content)
			var data	=	{
					status_id: status_id,
					content: content
			};
		    $.ajax({
		        type: "POST",
		        data: data,
		        url: '/NewsFeed/editstatus',
		        success: function(data){
                    $('.sticky_column').fixed_col_scroll.callbackFun();
		        },
		        dataType: 'html'
		    });	
		}
	},
	delStatus: function(status_id){
		objCommon.loading();
		var data	=	{
				status_id: status_id,
		};
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/NewsFeed/deletestatus',
	        success: function(data){
	        	$('.status_row_' + status_id).hide();
                $('.sticky_column').fixed_col_scroll.callbackFun();
	        	objCommon.unloading();
	        },
	        dataType: 'html'
	    });			
	},
	delStatusConfirm: function(status_id){
		$( ".popup-alert.remove_status .frame_content" ).html(tr('Are you sure to remove this status?'));
		$( ".popup-alert.remove_status" ).pdialog({
			title: tr('Remove Status'),
			buttons: [
						{
						  text: tr("Yes"),
						  click: function() {
							  $( this ).pdialog( "close" );
							  NewsFeed.delStatus(status_id);
						  }
						},
						{
						  text: tr("No"),
						  click: function() {
							  $( this ).pdialog( "close" );
						  }
						},
					  ],
		});
	},
	showFindhim: function(){
		$('#pop-find-him').show();
	}
}
