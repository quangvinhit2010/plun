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
		$(document.body).on('click', '.num_comment a, .link_comment a', function() {
			var _this = $(this);
			var chk = _this.closest('.item').find(".comment_list ol").html().length;
			console.log(chk);
			if(chk == 0){				
				objCommon.loadingInside(this);
				var link = $(this).attr('data-url');
				if(link){				
					$.post(link, { func: true}, function(data) {
						if(data.length > 0){
							_this.closest('.item').find(".comment_list ol").html(data);
						}
						objCommon.unloadingInside();
					},"html");
				}
			}
			
		});

		$(document.body).on("click", '.cpagging-comment',function(){
			var link = $(this);
			objCommon.loadingInside($(this).closest('.item').find('.info'));
			$.post($(this).attr('rel'), { func: true }, function(data) {
				$($(data).html()).insertAfter(link.parent());
				if($(data).attr('data-prevLnk').length > 0){
					link.attr('rel', $(data).attr('data-prevLnk'));
				}else{
					link.remove();
				}
                $('.sticky_column').fixed_col_scroll.callbackFun();
                objCommon.unloadingInside();
			},"html");
		});
	},
	likeStatus: function () {
		var time_commentlike;
		$(document.body).on('click',".link_like a", function() {
			//objCommon.loading();
            objCommon.loadingInside(this);
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
					objCommon.unloadingInside();
				},"json");
			}, 300);
			return false;		    
		});
		
		$(document.body).on('click',".num_like a", function() {
			objCommon.loadingInside(this);
			$.post($(this).attr("data-url"), { offset: $(this).attr("data-offset") }, function(data) {
				var temp = $(data);				
				temp.pdialog({
					title: tr('User liked'),                    
                    open: function(){
                        objCommon.outSiteDialogCommon(this);
                    },
                    width: 445
				});
                if($('.feedLikedUser ul li').length > 5){
                    objCommon.sprScroll('.feedLikedUser .scrollPopup');
                }else{
                    $('.feedLikedUser .scrollPopup').css('height','auto');
                }

				objCommon.unloadingInside();
			},"html");			
		});
		
		$(document.body).on('click',".feedLikedUser .moreUserLike", function() {
            var link = $(this);
			objCommon.loadingInside(this);
			$.post($(this).attr("data-url"), { offset: $(this).attr("data-offset") }, function(data) {
				link.parent().remove();
				/*
				var offset = parseInt($('.moreUserLike').attr("data-offset"));	
				offset += 5;
				$('.moreUserLike').attr("data-offset", offset);
				$(data).insertBefore(link.parent());				
		 		*/
				objCommon.sprScroll('.feedLikedUser ul');
				$('.feedLikedUser ul .mCSB_container').append(data);
				objCommon.unloadingInside();
			},"html");
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
						title: tr('People Who Like This')
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
				//objCommon.loading();
                objCommon.loadingInside(this);
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
                          objCommon.unloadingInside();
				    	  var data = $(response);
				    	  $(listcomment).append(data);

				    	  itemNewsfeed.find('.num_comment span').text(new_total_comment);
                          $('.sticky_column').fixed_col_scroll.callbackFun();
				    }
				});
				event.preventDefault();
				$(this).val('');
				return false;
			}
		});
	},
	show_more_newsfeed: function (alias, is_feed) {
		var _htmlTemp = '<li class="profile-feeds item item_showmore loading">';
			_htmlTemp += '	<div class="wrapLoadingNewFeed">';
			_htmlTemp += '		<div class="loading_new_feed">';
			_htmlTemp += '			<div class="_2iwr"></div>';
			_htmlTemp += '            <div class="_2iws"></div>';
			_htmlTemp += '            <div class="_2iwt"></div>';
			_htmlTemp += '            <div class="_2iwu"></div>';
			_htmlTemp += '            <div class="_2iwv"></div>';
			_htmlTemp += '            <div class="_2iww"></div>';
			_htmlTemp += '            <div class="_2iwx"></div>';
			_htmlTemp += '            <div class="_2iwy"></div>';
			_htmlTemp += '            <div class="_2iwz"></div>';
			_htmlTemp += '            <div class="_2iw-"></div>';
			_htmlTemp += '            <div class="_2iw_"></div>';
			_htmlTemp += '            <div class="_2ix0"></div>';
			_htmlTemp += '        </div>';
			_htmlTemp += '    </div>';
			_htmlTemp += '</li>';		
		if($.trim($('.feed .content ul').html()).length == 0){			
			$('.feed .content ul').append(_htmlTemp+_htmlTemp+_htmlTemp+_htmlTemp+_htmlTemp);
		}else{
			$('.feed .content ul').append(_htmlTemp);
		}
//        objCommon.loading();
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
	        	var result = $(data);
	        	result.find('.text').each(function(){
	        		$(this).html(chat.replaceEmoticons($(this).html()));
	        	});
	            $('.feed .content ul').append(result);
                $('.feed .content .profile-feeds.loading').remove();

                objCommon.lazyLoadCommon('.feed .content ul li .wrap_newfeed a img');
                /*objCommon.lazyLoadCommon('.feed .content ul li .avatar_feed a > img');
                objCommon.lazyLoadCommon('.feed .content ul li .info .list_photo_upload a img');*/
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
//	            objCommon.unloading();

				$('.showmore').show();
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
			$(text).html(chat.replaceEmoticons(Strophe.xmlescape(content)))
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
$(document).ready(function(){
	$('.feed').on('click', '.newfeed-emo-wrap .emoticons', function(){
		$(this).closest('.newfeed-emo-wrap').toggleClass('show-items');
	});
	$('.feed').on('click', '.newfeed-emo-wrap .emo', function(){
		var itemClass = $(this).attr('class').replace('emo ', '');
		var text = '';
		$.each(chat.emoticons, function(index, val){
			if(val == itemClass) {
				text = chat.decodeHtml(index);
				return false;
			}
		});
		
		if($(this).parent().hasClass('edit')) {
			var statusTextArea = $(this).closest('.edit_feed').find('textarea');
		} else
			var statusTextArea = $('.status');
		var startSelect = statusTextArea[0].selectionStart;
		var firstText = statusTextArea.val().slice(0, startSelect);
		var endText = statusTextArea.val().slice(startSelect, statusTextArea.val().length);
		statusTextArea.val(firstText+text+endText);
		statusTextArea.selectRange(firstText.length+text.length);
		$(this).closest('.newfeed-emo-wrap').removeClass('show-items');
	});
	$('.profile-feeds .item .text').each(function(){
		$(this).html(chat.replaceEmoticons($(this).html()));
	});
});