$(document).ready(function(){
	sprScroll('.news-feed .cont .feed-list-item');
	sprScroll('.main .col-right .members .list');
	sprScroll('.main .col-right .profile-photo .list-block-photo');
	sprScroll('.block-step .block-suggest');
	sprScrollHorizon('.main .col-right .block-photo .list');
	sprScroll('.col-full');
	sprScroll('.chat-list .item-wrap');
	//sprScroll('.chat-conversation .item-wrap');
	sprScroll('.photos-setting');
	sprScroll('.block-photo-more');

	sprScroll('.term_plun .content_term');
	
	sprScroll('.col-right-hotnew');
	
	sprScroll('.hotbox_detail');
	
	sprScroll('.detail_hotbox_400 .wrap_detail');	
	
	sprHide();
	sprHide_hotnew()
	sprComment();
	sprPost();	
	// hotboxDetail();
	//sprChatBox();
	//ShowPopFindHim();
	TabPopFindHim();
	// ShowUserInfo();
	UploadPhoto();
	AccordFindFriend();
	sprSlide();
	sprUser();
	//ClosePopWaiting();
	//ShowPopRegisterSuccess();
	//ShowPopRegisterUnSuccess();
	//ShowPopUnlockPrivatePhoto();
	//ShowPopAllowPrivatePhoto();
	//ShowPopDeniedPrivatePhoto();
	//ShowPopPayViewVaultPhoto();
	$(".setting-block .accordion-toggle").click(function(){
		$(".setting-block .accordion-toggle").addClass("collapsed");
	});
	$('#block-chat').initTabChat();
	$(".photo-nav").click(function(){
		$(this).closest(".photo-view").find(".photo-nav-wrap").toggleClass("active");
	});
	$(".photo-close").click(function(){
		$(this).closest(".photo-view").removeClass("active_photo");
	});
	
	// Close popup reference --------------------------------- 
	close_Area_Reference();
<<<<<<< HEAD
	//$("#fancybox_area").fancybox();
	//$("#fancybox_ref").fancybox();
=======
	$("#fancybox_area").fancybox();
	$("#fancybox_ref").fancybox();
>>>>>>> refs/remotes/origin/v1
});


function close_Area_Reference(){
	$(".btn-close-new").click( function(){
		$(".pop-cont").hide();
	});
	/*$(".btn-close-new-area").live('click', function(){
		$(".popup_area").hide();
	});*/
	$(".area").click( function(){
		$(".pop-cont").show();
	});
<<<<<<< HEAD
	$(".reference").click( function(){
		$(".pop-cont").show();
=======
	$(".area").live('click', function(){
		$.fancybox({
			'content':	$('#popup_area').html(),
			'centerOnScroll':	true,
			'autoDimensions':	true,
			'padding': 0,
			'margin':	0,
			'scrolling': false,
			'overlayShow'	:	true
		});		
	});
	$(".reference").live('click',function(){
		$(".popup_reference").show();
>>>>>>> refs/remotes/origin/v1
	});
}


function userInfoHeight(){
	var normalInfoHeight = 35 + $(".user-info .info-top .des").outerHeight();
	$(".user-info .info-top").css({
		'height' : normalInfoHeight
	});
}
function userContHeight(){
	if ($('.news-feed').hasClass("user-info")){
		var infoHeight = $('.user-info .info-wrap').outerHeight();
		$('.user-info .cont').css({
			'top': infoHeight
		});
	}
}
function userHeight(){
	userInfoHeight();
	userContHeight();
	$(".bg-control a").click(function(){
		if ($(this).hasClass("close")){
			$(".user-info .info").css({
				'height': 'auto'
			});
			userContHeight();
			$(this).removeClass("close").addClass("open");
		} else {
			userInfoHeight();
			userContHeight();
			$(this).removeClass("open").addClass("close");
		}
	});
	setTimeout(function(){
		$('.user-info .cont').addClass("active");
	}, 600);
}
function sprUser(){
	userHeight();
}
function sprSlide(){
	 $('.photo-view .item-list').bxSlider({
		nextSelector: $(".photo-view .btn-next"),
		prevSelector: $(".photo-view .btn-prev"),
		touchEnabled: true,
		mode: 'vertical',
		infiniteLoop: false,
		hideControlOnEnd: true,
		pager: false,
		minSlides: 10,
		maxSlides: 100,
		moveSlides: 3,
		adaptiveHeight: false
	 });
}
$(window).load(function(){
	if($(".pint-style").length > 0){
		$(".pint-style .list").isotope({
			itemSelector: '.item',
			animationEngine: 'best-available',
			layoutMode: 'masonry',
			masonry: {
				columnWidth: 8
			}
		});
	}
	
	
	// Vi tri chatbox 
	$cur_width_chat = $(window).width() - $(".chat-list").width() -250;
	$(".pos_chat_boxed_1").css('left',$cur_width_chat);
	$(".pos_chat_boxed_2").css('left',$cur_width_chat - 230);
	$(".pos_chat_boxed_3").css('left',$cur_width_chat - 460);
	$(".pos_chat_boxed_4").css('left',$cur_width_chat - 690);
	$(".pos_chat_boxed_5").css('left',$cur_width_chat - 920);
	$(".pos_chat_boxed_end").css('left',$cur_width_chat - 1150);
	
	if($('#slideshow').attr('id')){
		show_gal_hotbox();
	}
});

function show_gal_hotbox(){
	// Gallery hot box
	$('#slideshow').fadeSlideShow();
	
	// Xac dinh chieu rong cua gallery
	$(".gallery_hotbox").css('width',$(window).width() - 400);
	$("ul#slideshow").css('height',$(window).height());
	
	// xac dinh trong tam hinh anh chieu rong
	$a = ($(window).width() - 400)/2;
	$b = $('ul#slideshow li').width()/2;
	$("ul#slideshow li").css('left',$a - $b);
	// xac dinh trong tam hinh anh chieu cao
	$c = $(window).height()/2;
	$d = $('ul#slideshow li').height()/2;
	$("ul#slideshow li").css('top',$c - $d);
	
	$(".zoom_pics").click(function(){
		$(".popup_hotbox").show();
		$(".col-nav + .main").css("left","0");
	});
	
	$(".gallery_close").click(function(){
		$(".popup_hotbox").hide();
	});
}

function AccordFindFriend(){
	var blockSearch = $('.block-step .search');
	var btnFindFriends = blockSearch.find('.find-friend');
	btnFindFriends.each(function(index){
		var btn = $(this);
		var blockForm = btn.closest('li').find('form');
		btn.unbind('click').bind('click',function(e){
			e.preventDefault();
			btnFindFriends.css('display','block');
			blockSearch.find('form:visible').slideUp();
			btn.css('display','none');
			blockForm.slideDown();
		});
	});
}
function ClosePopWaiting(){
	if($('.pop-waiting').length > 0){
		$('.pop-waiting .btn-close').bind('click',function(e){
			if(e) e.preventDefault();
			$('.pop-waiting').closest('li').trigger('click');
		});
	}
}
function UploadPhoto(){
	if($('.frame #photo').length){
		$('.frame #photo').imgAreaSelect({
			handles: true,
			onSelectChange: preview,
			fadeSpeed: 200,
			instance: true,
			aspectRatio: '1:1',
			x1: 100,
			y1: 90,
			x2: 200,
			y2: 190
		});
	}
}
function preview(img, selection) {
	if (!selection.width || !selection.height)
		return;
	var scaleX = $('#preview').width() / selection.width;
	var scaleY = $('#preview').height() / selection.height;
	$('#preview img').css({
		width: Math.round(scaleX * $(img).outerWidth()),
		height: Math.round(scaleY *$(img).outerHeight()),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1)
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);   
	
}
function ShowUserInfo() {
	var btnControl = $('.info .control');
	var blockInfo = $('.user-info > .info');
	var blockInfoHeight = blockInfo.height();
	var userInfoCont = $('.user-info .cont');
	var userInfoContTop = parseInt(blockInfoHeight) + 18;
	userInfoCont.css('top',userInfoContTop);
	btnControl.unbind('click').bind('click',function(e){
		if(e) e.preventDefault();
		if(btnControl.hasClass('open')){
			btnControl.removeClass('open').addClass('close');
			blockInfo.animate({
				height: 160
			});
			userInfoCont.animate({
				top: 178
			});
		}
		else {
			btnControl.removeClass('close').addClass('open');
			blockInfo.animate({
				height: blockInfoHeight
			});
			userInfoCont.animate({
				top: userInfoContTop
			});
		}
	});
}
// Show Popup Register Success
function ShowPopRegisterSuccess(){
	$('body').showLayer({
		layerClass: '.pop-reg-succ',	
		overlayOpacity: 0.3
	});
}
// Show Popup Register UnSuccess
function ShowPopRegisterUnSuccess(){
	$('body').showLayer({
		layerClass: '.pop-reg-unsucc',	
		overlayOpacity: 0.3
	});
}
// Show Popup Unlock Private Photo
function ShowPopUnlockPrivatePhoto(){
	$('body').showLayer({
		layerClass: '.pop-unlock-private-photo',	
		overlayOpacity: 0.3
	});
}
// Show Popup Allow Private Photo
function ShowPopAllowPrivatePhoto(){
	$('body').showLayer({
		layerClass: '.pop-allow-private-photo',	
		overlayOpacity: 0.3
	});
}
// Show Popup Denied Private Photo
function ShowPopDeniedPrivatePhoto(){
	$('body').showLayer({
		layerClass: '.pop-denied-private-photo',	
		overlayOpacity: 0.3
	});
}
// Show Popup Pay View Vault Photo
function ShowPopPayViewVaultPhoto(){
	$('body').showLayer({
		layerClass: '.pop-payview-vault-photo',	
		overlayOpacity: 0.3
	});
}
// Show Popup Find Him
/*function ShowPopFindHim(){
	$('.members .area').live('click',function(e){
		if(e) e.preventDefault();
		$('body').showLayer({
			layerClass: '#find-him-pop',	
			overlayOpacity: 0.3
		});
	});
}*/
// Show hide tab Popup Find Him 
function TabPopFindHim(){
	var liTabs = $('.pop-tab li');
	var tabConts = $('.tab-cont');
	liTabs.each(function(index){
		var aEl = $(this).find('a');
		aEl.unbind('click').bind('click',function(e){
			if(e) e.preventDefault();
			liTabs.removeClass('active');
			tabConts.css('display','none');
			liTabs.eq(index).addClass('active');
			tabConts.eq(index).css('display','block');
		});
	});
}
// Hide news feed 
function sprHide(){
	$(".btn-hide").click(function(){
		$(".col-feed").removeClass("col-left");
		$(".members").addClass("feed-hidden");
	});
	$(".btn-open-feed").click(function(){
		$(".col-feed").addClass("col-left");
		$(".members").removeClass("feed-hidden");
	});
}

// Hide news feed show hot new
function sprHide_hotnew(){
	$('.btn-del-hotbox').click(function(){
		$(".col-feed").removeClass("col-left");
		
		// Chinh reload lai danh sach hotbox
		
		var isChrome = window.chrome;
		if(isChrome) {
		   // is chrome 
		} else { 
		   $(".col-right-hotnew").css('width','100%');
		}
		
		$(".pint-style .list").isotope('reLayout').isotope('reLayout', sprScrollTop);		
     });
}
// Calling Custom Scrollbar

function sprScrollHorizon(cls){
	$(cls).mCustomScrollbar({
		scrollInertia: "0",
		mouseWheelPixels: "auto",
		autoHideScrollbar: false,
		horizontalScroll:true,
		advanced:{
			updateOnContentResize: true,
			contentTouchScroll: true
		}
	});
}
function sprScroll(cls){
	$(cls).mCustomScrollbar({
		scrollInertia: "0",
		mouseWheelPixels: "auto",
		autoHideScrollbar: true,
		advanced:{
			updateOnContentResize: true,
			contentTouchScroll: true
		}
	});
}
function sprComment(){
	$(".btn-comment").click(function(){
		$(this).closest(".item").toggleClass("item-active");
		$(this).closest(".item").find(".cmt-post-text").focus();
	});
	$(".news-message .btn-messagemore").click(function(){
		$(this).closest(".item").toggleClass("item-active");
		var detailScroll = $(this).closest(".item").find(".message-list").hasClass("mCustomScrollbar");
		if (detailScroll == true)
		{
			// Do Nothing
		} else {
			$(this).closest(".item").find(".message-list").mCustomScrollbar({
				scrollInertia: "0",
				mouseWheelPixels: "auto",
				autoHideScrollbar: true,
				advanced:{
					updateOnContentResize: true,
					contentTouchScroll: true
				}
			});
		}
	});
	$(".news-message .btn-minimize").click(function(){
		$(this).closest(".item").toggleClass("item-active");
	});
}
function sprPost(){
	$(".btn-navpost").click(function(){
		$(".pop-status").fadeToggle();
	});
	$(".pop-status .btn-close").click(function(){
		$(".pop-status").fadeOut();
	});
}
sprScrollTop = function(){
	$(".col-full").mCustomScrollbar("scrollTo",0);
}
sprScrollBack = function(activeID) {
	$(".col-full").mCustomScrollbar("scrollTo",activeID);
}
function hotboxDetail() {
	$(".item .headtitle a").click(function(){
		var hid = $(this).attr('rel');
		//console.log(hid);
		$(".item-active").removeClass("item-active");
		$(this).closest(".item").addClass("item-active");
		if(hid){
			var url = "/hotbox/load/id/" + hid;
			$.get( url, function( res ) {
				var stateObj = { foo: 1000 + Math.random()*1001 };
				//replace url on address bar
				window.history.pushState(stateObj, '', url);
				if ($(".pint-style .item").hasClass("item-detail"))
				{
					//$(".item-detail").remove();
					$(".item-detail").closest(".item").remove();					
				} 
				$(".pint-style .list").prepend(res).isotope('reloadItems').isotope({ sortBy: 'original-order'}, sprScrollTop);
			});
		}
		
		
	});
}
function sprCloseDetail(item){
		var itemID = $(item).attr("rel");
		$(item).closest(".item").remove();
		$(".pint-style .list").isotope('reloadItems').isotope({ sortBy: 'original-order' }, sprScrollBack(itemID));
}
function sprChatBox(){
	$(".chat-box-area .btn-slide").click(function(){
		$(".chat-list").toggleClass("active");
		$(".chat-list .list").slideToggle();
	});
	$(".chat-boxed .btn-close").click(function(){
		$(this).closest(".chat-boxed-wrap").remove();
		// Vi tri chatbox 
		/*$cur_width_chat = $(window).width() - $(".chat-list").width() -250;
		$(".pos_chat_boxed_1").css('left',$cur_width_chat);
		$(".pos_chat_boxed_2").css('left',$cur_width_chat - 230);
		$(".pos_chat_boxed_3").css('left',$cur_width_chat - 460);
		$(".pos_chat_boxed_4").css('left',$cur_width_chat - 690);
		$(".pos_chat_boxed_5").css('left',$cur_width_chat - 920);
		$(".pos_chat_boxed_end").css('left',$cur_width_chat - 1150);
		*/
	});
	$(".chat-boxed .title").click(function(){
		$(this).closest(".chat-boxed-wrap").toggleClass("hide").find(".chat-conversation").slideToggle();
	});
}



(function($){
	
	
	var count = 0;
	$.fn.initTabChat = function(options){
		var defaults = {
		};
		var options = $.extend(defaults, options); 
		return this.each(function(){
			var that = $(this);
			var ItemUsers = $('.main .col-left .chat .item .feed').not(':first');
			var tabDemo = $('#tab-demo');
			ItemUsers.css('cursor','pointer');
			that.css({
				height: $(window).height() - 75
			});
			that.find('.tabs').css({
				height: $(window).height() - 127
			});
			ItemUsers.each(function(index){
				var ElUser = $(this);
				var UserName = ElUser.find('h4').text();
				ElUser.unbind('click').bind('click',function(e){
					count++;
					if(that.find('.head-tab').length == 0){
						var headTab = $('<ul class="head-tab"></ul>');
						var liTab = $('<li class="active"><a class="title-tab" href="tab-'+count+ '">' +UserName+'</a><a class="close-tab" href="#">x</a></li>');
						liTab.appendTo(headTab);
						headTab.appendTo(that);
						tabDemo.clone().appendTo(that).css({
							'display':'block',
							'height': that.height()-47,
						}).attr('id','tab-'+count).find('.block-type').css('width',that.width()-40);
						closeTab();
						activeTab()
					}
					else{
						var headTab = $('ul.head-tab');
						var headTabWidth = headTab.width() - 35;
						var listTabs = $('ul.head-tab .title-tab');
						var flag = 0;
						var tabWidth = 0;
						var countLiTab = 0;
						for(var i=0 ; i < listTabs.length ; i++){
							if(listTabs.eq(i).text() == UserName){
								headTab.find('li').removeClass('active');
								that.find('.tabs').css('display','none');
								headTab.find('li').eq(i).addClass('active');
								that.find('.tabs').eq(i).css('display','block');
								flag = 1;
							}
							if(i == listTabs.length - 1 && flag == 0){
								while(countLiTab < headTab.find('li').length){
									tabWidth += headTab.find('li').eq(countLiTab).outerWidth(true) + 5;
									countLiTab++;
								}
								if(headTabWidth - tabWidth <= 160) {
									var btnMore = $('.chat-more .more');
									var ulMore = $('.chat-more ul');
									if(!btnMore.length){
										btnMore = $('<a class="more" href="#" title="" data-toggle="dropdown">more</a>');
									}
									if(!ulMore.length){
										ulMore = $('<ul></ul>');
									}
									var liMore = $('<li><a class="title-tab" href="tab-'+(count-1)+'">'+listTabs.eq(listTabs.length-1).text()+'</a><a class="close-tab" href="#">x</a></li>');
									btnMore.insertBefore($('.setting-top'));
									liMore.appendTo(ulMore);
									ulMore.appendTo($('.setting-top-board'));
									headTab.find('li').eq(listTabs.length-1).remove();
									headTab.find('li').removeClass('active');
									that.find('.tabs').css('display','none');
									var liTab = $('<li class="active"><a class="title-tab" href="tab-'+count+ '">' +UserName+'</a><a class="close-tab" href="#">x</a></li>');
									liTab.appendTo(headTab);
									tabDemo.clone().appendTo(that).css({
										'display':'block',
										'height': that.height()-47,
									}).attr('id','tab-'+count).find('.block-type').css('width',that.width()-40);
									closeTab();
									activeTab();
								}
								else {
									headTab.find('li').removeClass('active');
									that.find('.tabs').css('display','none');
									var liTab = $('<li class="active"><a class="title-tab" href="tab-'+count+ '">' +UserName+'</a><a class="close-tab" href="#">x</a></li>');
									liTab.appendTo(headTab);
									tabDemo.clone().appendTo(that).css({
										'display':'block',
										'height': that.height()-47,
									}).attr('id','tab-'+count).find('.block-type').css('width',that.width()-40);
									closeTab();
									activeTab();
								}
							}
						}
					}
				});
			});
			closeTab();
			activeTab();
			function closeTab(){
				var headTabWidth = $('ul.head-tab').width() - 35;
				var liTabs = that.find('.head-tab > li');
				var tabConts = that.find('.tabs');
				var liChatMore = $('.chat-more ul li');
				var tabWidth = 0;
				var countLiTab = 0;
				$('.close-tab').each(function(index){
					var btnClose = $(this);
					btnClose.unbind('click').bind('click',function(e){
						e.preventDefault();
						btnClose.parent().remove();
						$('#'+btnClose.prev().attr('href')).remove();
						if(!that.find('li.active').length){
							liTabs.eq(0).addClass('active');
							tabConts.eq(0).css('display','block');
						}
						while(countLiTab < $('ul.head-tab').find('li').length){
							tabWidth += $('ul.head-tab').find('li').eq(countLiTab).outerWidth(true) + 5;
							countLiTab++;
						}
						if(headTabWidth - tabWidth > 160 & liChatMore.length > 0) {
							liChatMore.eq(liChatMore.length-1).removeClass('active').appendTo($('ul.head-tab'));
							tabConts.not(':first').css('display','none');
						}
						if($('.chat-more ul li').length == 0){
							$('.chat-more .more').remove();
						}
						if(!$('ul.head-tab').find('li').length){
							$('ul.head-tab').remove();
						}
						closeTab();
					});
					
				});
			}
			function activeTab(){
				var titleTabs = $('.title-tab');
				var tabConts = that.find('.tabs');
				titleTabs.each(function(index){
					var titleTab = $(this);
					titleTab.unbind('click').bind('click',function(e){
						e.preventDefault();
						titleTabs.parent().removeClass('active');
						titleTab.parent().addClass('active');
						tabConts.css('display','none');
						$('#'+titleTab.attr('href')).css('display','block');
					});
				});
			}
		
			$(window).resize('chat.resize',function(e){
				checkTab();
			});
			function checkTab(){
				that.css({
					height: $(window).height() - 75
				});
				that.find('.tabs').css({
					height: $(window).height() - 127
				});
				var headTab = $('ul.head-tab');
				var listTabs = $('ul.head-tab .title-tab');
				var headTabWidth = headTab.width() - 35;
				var tabWidth = 0;
				var countLiTab = 0;
				while(countLiTab < headTab.find('li').length){
					tabWidth += headTab.find('li').eq(countLiTab).outerWidth(true) + 5;
					countLiTab++;
				}
				if(tabWidth >= headTabWidth) {
					var btnMore = $('.chat-more .more');
					var ulMore = $('.chat-more ul');
					var widthRe = headTabWidth - tabWidth;
					console.log(tabWidth,headTabWidth);
					 if(!btnMore.length){
						btnMore = $('<a class="more" href="#" title="" data-toggle="dropdown">more</a>');
						btnMore.insertBefore($('.setting-top'));
					}
					if(!ulMore.length){
						ulMore = $('<ul></ul>');
					}
					ulMore.appendTo($('.setting-top-board'));
					headTab.find('li').eq(headTab.find('li').length-1).appendTo(ulMore);
					if(!headTab.find('li').length){
						$('ul.head-tab').remove();
					}
					closeTab();
					activeTab();
					checkTab();
				}
			}
		});
	};
})(jQuery);
