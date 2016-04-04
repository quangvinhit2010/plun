function detectShowItems(hItems, numCol){
	var hWrap = $('.suggest-user-settings').outerHeight(),
		hSeeMore = $('.more-wrap').outerHeight(),
		hWrapItems = hWrap - hSeeMore,
		numMarginBottom = 10,
		numRow = Math.round(hWrap/(hItems+numMarginBottom)),
		numItemsShow = numRow*numCol;

	//alert(numItemsShow);
	return numItemsShow;
}
function boxScale(wRealBox){
	var $Parent = $('.main .col-right .members .list ul'),
		$boxImg = $Parent.find('> li'),
		wParent = $('.suggest-user-settings').outerWidth(),
		numMargin = 8,
		numCol = Math.floor(wParent/(wRealBox+numMargin));
	if(wParent - (wRealBox+numMargin)*numCol > 10){
		var numaddCol = wRealBox + numMargin - (wParent - (wRealBox+numMargin)*numCol),
			numAddWidth = Math.round(numaddCol/numCol);
		wRealBox = wRealBox - numAddWidth;
	}
	
	$boxImg.css({
		width: wRealBox+'px',
		height: wRealBox+'px'
	});
	detectShowItems(wRealBox, numCol);
	setTimeout(function(){
		$boxImg.css('visibility','visible').hide().fadeIn(300);
		$('.more-wrap').css('visibility','visible').hide().fadeIn(300);
	},50);
	
}
var test = setInterval(function(){
	if($('.main .col-right .members .list ul li').length > 0){
		var wRealBox = 162;
		boxScale(wRealBox);	
		//$(".col-nav + .main").css("right",170);
		clearInterval(test);
	}
}, 100);
$(document).ready(function(){
	var wRealBox = 162;
	$(window).resize(function(){
		$('.main .col-right .members .list ul li').hide().css('visibility','hidden');
		boxScale(wRealBox);
	});
	// Chieu cao bg white banner trang home
	//load_banner_home();
		
	//vi tri popup create HOTBOX
	$(".form-isu").css("padding-top",($(window).height() - 510) / 2);	
	
	// Xac dinh chieu cao Slide photo cua user 
	//slide_photo();
		
	//(".scroll_avatar_home").css('height',$(window).height());

	//sprScroll('.news-feed .cont .feed-list-item'); 
	
	//$(".scroll_avatar_home").mCustomScrollbar();
	
	sprScroll('.plun_about');
	
	sprScroll('.update_profile_step4');
	
	sprScroll('.cont_photo_setting');
	
	sprScroll('.list_photo_setting');		
	
	sprScroll('.wrap_list_feed');	
	
	sprScroll('.main .col-right .members .list');
	sprScroll('.main .col-right .profile-photo .list-block-photo');
	sprScroll('.block-step .block-suggest');	
	
	sprScroll('.profile-photo #public-photos');
	sprScroll('.profile-photo #private-photos');
	sprScroll('.profile-photo #vault-photos');
	
	sprScroll('.photos-upload-list .item-list-1');
	
	/*if($( "#public-photos" ).find( ".style_alert" ).lenght == undefined){
		sprScrollHorizon('.main .col-right .block-photo #public-photos');
	}
	if($( "#private-photos" ).find( ".style_alert" ).lenght == 0){
		sprScrollHorizon('.main .col-right .block-photo .list');
	}*/
	
	//sprScroll('.col-full');
	
	sprScroll('.chat-list .item-wrap');
	
	//sprScroll('.col-nav .user .info');
	
	sprScroll('.photos-setting');
	sprScroll('.block-photo-more');

	sprScroll('.term_plun .content_term');
	
	sprScroll('.col-right-hotnew');
	
	sprScroll('.hotbox_detail');
	
	sprScroll('.detail_hotbox_400 .wrap_detail');	
	
	sprScroll('.content_invite');
	sprScroll('.found_friend ul');
		
	sprScroll('.popup-alert .form-contain .content ul.userlike');
	sprScroll('.list_user_like ul.userlike');
	
	sprScroll('.popup-alert .list_search_user ul.search_message_user');

	sprScroll('.scroll_status_detail');
	
	
	sprScroll('.alert_detail .content'); // trang alert detail
 
 	sprScroll('.setting_col1'); // trang setting profile 1
	sprScroll('.setting_col2'); // trang setting profile 2
 	
	
	sprScroll('.profile_setting_new');
	
	
	
	sprHide(wRealBox);
	//sprHide_hotnew()
//	sprComment();
	sprPost();	
	// hotboxDetail();
	//sprChatBox();
	//ShowPopFindHim();
	TabPopFindHim();
	// ShowUserInfo();
	UploadPhoto();
	//AccordFindFriend();
	sprSlide();
	sprUser();
	
	// Trang support
	menu_language();
	traloi_support();

	//ClosePopWaiting();
	//ShowPopRegisterSuccess();
	//ShowPopRegisterUnSuccess();
	//ShowPopUnlockPrivatePhoto();
	//ShowPopAllowPrivatePhoto();
	//ShowPopDeniedPrivatePhoto();
	//ShowPopPayViewVaultPhoto();
	//ShowPopMessageSuccess();
	//ShowPopMessageFail();
	
	//for setting pages
	if($(".setting-block").attr('class')){
		sprScroll('#setting-profile');
		$(".setting-block").click(function(){
			$(".setting-block .accordion-toggle").addClass("collapsed");
			$(".setting-block .accordion-body").addClass('collapse');
			
			$(this).find('.accordion-toggle').removeClass('collapsed');
			$(this).find('.accordion-body').removeClass('collapse');
		});
	}
	//$('#block-chat').initTabChat();
	
	$(".photo-nav").click(function(){
		$(this).closest(".photo-view").find(".photo-nav-wrap").toggleClass("active");
	});
	$(".photo-close").click(function(){
		$(this).closest(".photo-view").removeClass("active_photo");
	});
	
	// Close popup reference --------------------------------- 
	if($('.members h3 .area').attr('class')){
		close_Area_Reference();
	}
	
	
    $(window).resize(function () {
		// tinh lai gia tri chieu rong cho slide
		$("#supersized").css('width',$(window).width() - 400);
    });
	
	
	// resize hinh bg nguoi mau - trang home new - hinh ben trai
	  $("#triquibackimg").load(function(){
		   resize_bg();
	  })
	  $(window).resize(function(){
		   resize_bg();
	  })
	  
	  function resize_bg(){ // resize hinh bg nguoi mau - trang home new - hinh ben trai
	  
		  $("#triquibackimg").hide();
		   $("#triquibackimg").css("left","0");
		   var doc_width = $(window).width() / 2;
		   var doc_height = $(window).height();  
		   var image_width = $("#triquibackimg").width();
		   var image_height = $("#triquibackimg").height();
		   var image_ratio = image_width/image_height;      
		   var new_width = doc_width;
		   var new_height = Math.round(new_width/image_ratio);
		   if(new_height<doc_height){
				new_height = doc_height;
				new_width = Math.round(new_height*image_ratio);
				var width_offset = Math.round((new_width-doc_width)/2);
				$("#triquibackimg").css("left","-"+width_offset+"px");
		   }
		   $("#triquibackimg").width(new_width);
		   $("#triquibackimg").height(new_height);
		   
		   $("#triquibackimg").show();
		   //Trang home - danh sach avatar - goi lan 2 cho truong hop resize page
		   var doc_height_home = $(window).height() - 64; // chieu cao menu
		   $('.home-right .wrap ul li').css("width",doc_height_home / 5 );
		   $('.home-right').css("width",doc_height_home + 10);
	  }
	  // End - resize hinh trang home new
	
	
	//Trang home - danh sach avatar - goi lan dau load page
	var doc_height_home = $(window).outerHeight() - 64,
		doc_width_home = $(window).outerWidth(),
		wHomeLeft = $('.home-left #triquiback').outerWidth(),
		convert_width_percent_px = doc_width_home*(wHomeLeft/100);
	$('.home-right .wrap ul li').css("width",doc_height_home / 5 );
	$('.home-right').css("width",doc_height_home + 10);
	
	
	
	show_gal_hotbox();
	
	// profile setting - an thanh scroll khi khoi tao
	$(".extra-userlocation .mCSB_scrollTools").css("display","none");
	
	// Show tooltip khi click vao cac textarea...
	tooltip_textarea();
	//for settings suggest
	if($('#setting-main').attr('id')){
		loadLanguages();
		loadLookingfor();
		loadAttributes();
		loadMyTypes();
		loadStuff();
	}
	
	//for listbox looking for online
	$('.user_looking_online select').change(function(){
		$('.user_looking_textselect span').text($('.user_looking_online select :selected').text());
	});
	//for listbox check-in form
	$('.check-in-country select').live('change', function(){
		$('.check-in-country .txt_select span').text($('.check-in-country select :selected').text());		
	});
	$('.check-in-state select').live('change', function(){
		$('.check-in-state .txt_select span').text($('.check-in-state select :selected').text());		
	});	
	$('.check-in-city select').live('change', function(){
		$('.check-in-city .txt_select span').text($('.check-in-city select :selected').text());		
	});	
	$('.check-in-district select').live('change', function(){
		$('.check-in-district .txt_select span').text($('.check-in-district select :selected').text());		
	});	
	$('.check-in-lookingfor select').live('change', function(){
		$('.check-in-lookingfor .txt_select span').text($('.check-in-lookingfor select :selected').text());		
	});	
	if($('#frm-quicksearch').attr('id')){
		loadQuickSearchSuggest();
	}
	
	
});


(function(jQuery) {
	jQuery.fn.clickoutside = function(callback) {
		var outside = 1, self = $(this);
		self.cb = callback;
		this.click(function() { 
			outside = 0; 
		}); 
		$(document).click(function(event){
			if(event.button == 0)
			{
				outside && self.cb();
				outside = 1;
			}
		});
		return $(this);
	}
})(jQuery);

// Menu dropdown language
function menu_language(){
	$(".language ul li:first-child").click(function(){
		$(".language ul li").toggle();
	});
	$(".language ul li:first-child").clickoutside(function(){
		$(".language ul li").hide();
	});
}
// Toggle cau tra loi
function traloi_support(){
	$(".right_support .cauhoi_thuonggap ul li").click(function(){
		$(this).next(".right_support .cauhoi_thuonggap ul li.traloi").toggle();
		return (false);
	});
}

// Chieu cao bg white banner trang home
function load_banner_home(){
	$(".banner_home").css("height",$(window).height() - 36);
	$(".col-nav + .main").css("right",170);
}
	
//load suggest quick search user
function loadQuickSearchSuggest(){
	$('.quicksearch_suggestuser .select2-input').live('keypress', function(event){
		 if ( event.which == 13 ) {
			 $('#frm-quicksearch').submit();
		 }		
	});
}
//load stuff auto complete for profile setting
function loadStuff(){
	$('.input-tags-stuff').selectize({
		plugins: ['remove_button'],
	    valueField: 'id',
	    labelField: 'title',
	    searchField: 'title',
	    options: stuff,		
		delimiter: ',',
		persist: false,
		render: {
			item: function(data, escape) {
		    	return '<div>' + escape(data.title) + '</div>';
		    }
		},
		create: false
	});
}
//load my types auto complete for profile setting
function loadMyTypes(){
	$('.input-tags-types').selectize({
		plugins: ['remove_button'],
	    valueField: 'id',
	    labelField: 'title',
	    searchField: 'title',
	    options: my_types,		
		delimiter: ',',
		persist: false,
		render: {
			item: function(data, escape) {
		    	return '<div>' + escape(data.title) + '</div>';
		    }
		},
		create: false
	});
}
//load my attributes auto complete for profile setting
function loadAttributes(){
	$('.input-tags-attributes').selectize({
		plugins: ['remove_button'],
	    valueField: 'id',
	    labelField: 'title',
	    searchField: 'title',
	    options: my_attributes,		
		delimiter: ',',
		persist: false,
		render: {
			item: function(data, escape) {
		    	return '<div>' + escape(data.title) + '</div>';
		    }
		},
		create: false
	});
}
//load my looking for auto complete for profile setting
function loadLookingfor(){
	$('.input-tags-lookfor').selectize({
		plugins: ['remove_button'],
	    valueField: 'id',
	    labelField: 'title',
	    searchField: 'title',
	    options: looking_for,		
		delimiter: ',',
		persist: false,
		render: {
			item: function(data, escape) {
		    	return '<div>' + escape(data.title) + '</div>';
		    }
		},
		create: false
	});
}
//load my languages for auto complete for profile setting
function loadLanguages(){
	$('.input-tags').selectize({
		plugins: ['remove_button'],
	    valueField: 'id',
	    labelField: 'title',
	    searchField: 'title',
	    options: languages,		
		delimiter: ',',
		persist: false,
		render: {
			item: function(data, escape) {
		    	return '<div>' + escape(data.title) + '</div>';
		    }
		},
		create: false
	});
}

// Vi tri chatbox 
function vitri_chatbox(){	
	$cur_width_chat = $(window).width() - $(".chat-list").width() - 240; 
	$(".pos_chat_boxed_1").css('left',$cur_width_chat);
	$(".pos_chat_boxed_2").css('left',$cur_width_chat - 230);
	$(".pos_chat_boxed_3").css('left',$cur_width_chat - 460);
	$(".pos_chat_boxed_4").css('left',$cur_width_chat - 690);
	$(".pos_chat_boxed_5").css('left',$cur_width_chat - 920);
	$(".pos_chat_boxed_end").css('left',$cur_width_chat - 1150);
}



$(window).load(function(){
	
	onstart();
	
	
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
	vitri_chatbox();
	
	
	//if($('#slideshow').attr('id')){
		//	
	//}
	//alert($('.main').width());
	//$(".width-message-detail").css("width",$('.main').width() - 545);
	$(".message-list-detail").css("width",$('.main').width() - 545); // trang message detail
	$(".message-detail ul li .right").css("width",$('.message-list-detail').width() - 55);
	
	
	$(".alert_detail").css("width",$('.main').width() - 190);	// trang alert detail
	$(".alert_detail .content").css("width",$('.main').width() - 200);
	
	$(".title_message").css("width",$('.main').width() - 544);
	
	//$(".setting_col2 table").css("width",$('.main').width() - 856);	// trang profile setting 2
	//$(".profile_setting_page").css("width",$('.main').width() - 200);
	
	// canh hinh hotbox center chieu rong
	$(".gallery_hotbox").css('width',$(window).width() - 400);
	//$("ul#slideshow li").css('width',$(window).width() - 400);
	//alert($('.gallery_hotbox').width());
	if( $("#supersized li.activeslide img").width() > $(".gallery_hotbox").width()){
		//$(this).width() = $(".gallery_hotbox").width();
		//alert("aaaaaaaaaa");
	}
	
	// canh hinh hotbox center chieu cao
	//$hinh_center = $(window).height() - $("ul#slideshow li img").height() / 2 ;
	//alert($hinh_center);
	//$("ul#slideshow li img").css('margin-top',$("ul#slideshow li img").height() / 2 );
	
	$(".news-feed .cont .item .nav ul li").mouseover(function(){
		$(this).find(".list_like").show();
	});
	$(".news-feed .cont .item .nav ul li").mouseout(function(){
		$(this).find(".list_like").hide();
	});
	
	$(".user-info .cont .item .nav ul li").mouseover(function(){
		$(this).find(".list_like").show(); 
	});
	$(".news-feed .cont .item .nav ul li").mouseout(function(){
		$(this).find(".list_like").hide();
	});
	
	//load limit input text for about us
	if($('.textearea_counter_input').attr('class')){
		display_limit_text(this);
		$('.textearea_counter_input').keyup(function(){
			input_limit_text(this);
		});
	}
	//load virtual form
	loadvirtualformSettings();
	//menu_setting();
	
	updateActivity();
	
	
});
function updateActivity(){
    $.ajax({
        type: "POST",
        url: '/site/UpdateLastActivity',
        success: function(data) {
        },
        dataType: 'html'
    });

    setTimeout(function(){
    	updateActivity();
    }, update_activity_time * 1000)	
}
function loadvirtualformSettings(){
	if($('.virtual_form').length > 0){
		$('.virtual_form').live('change',function(){
			var class_text	=	$(this).attr('text');
			var value	=	$(this).find('option:selected').text();
			$('.' + class_text).text(value);
		});
	}
}

function menu_setting(){	
	$(".menu_setting ul li a").click(function(){
		$(".sub_main").hide();
		$(this).next(".sub_main").show();
	});	
}

//textearea limit text
function input_limit_text(input){
	var limit = $(input).attr('limit');
	var content	=	$(input).val();
	if(content.length >= limit){
		content	=	content.substr(0, limit);
		$(input).val(content);
	}
	display_limit_text();
}
function display_limit_text(){
	var limit = $('.textearea_counter_input').attr('limit');
	var content	=	$('.textearea_counter_input').val();	
	var limit_display	=	content.length + '/' + limit;
	$('.textearea_result_display').text(limit_display);
}

function tooltip_textarea(){
	if($('#Notes_title').length > 0 || $('#Notes_desc').length > 0){
		$('#Notes_title').poshytip({
			className: 'tip-yellowsimple',
			showOn: 'focus',
			alignTo: 'target',
			alignX: 'inner-left',
			alignY: 'bottom',
			offsetX: 0,
			offsetY: 5,
			showTimeout: 100
		});
		$('#Notes_desc').poshytip({
			className: 'tip-yellowsimple',
			showOn: 'focus',
			alignTo: 'target',
			alignX: 'inner-left',
			alignY: 'bottom',
			offsetX: 0,
			offsetY: 5,
			showTimeout: 100
		});
	}
	
}

function onstart(){
	$(".popup_hotbox").hide();
	$("#supersized").hide();		
}

function close_Area_Reference(){
	$(".btn-close-new").click( function(){
		$(".find-him-pop").toggle();
	});
	$(".main .col-right .members h3 .area").live('click', function(){
		$('.find-him-pop').toggle();
	});
	$(".reference").live('click', function(){
		$('.find-him-pop').toggle();
	});
}


function userInfoHeight(){
	var normalInfoHeight = 110 + $(".user-info .info-top .des").outerHeight();
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
			
	        $('#description').perfectScrollbar({
	            wheelSpeed: 20,
	            wheelPropagation: false
	          });
	        
			$(this).removeClass("close").addClass("open");
			$('.main .col-left .user-info .info-wrap .info').css("height",264);
			$(".extra-userlocation .mCustomScrollBox").css("height",264);
			$(".extra-userlocation .mCSB_scrollTools").css("display","block");
			userContHeight();
			
		} else {
			userInfoHeight();
			$(this).removeClass("open").addClass("close");
			$('.main .col-left .user-info .info-wrap .info').css("height",154);
			$(".extra-userlocation .mCustomScrollBox").css("height",350);
			$(".extra-userlocation .mCSB_container").css("top",0);
			$(".extra-userlocation .mCSB_scrollTools").css("display","none");
			userContHeight();
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

function show_gal_hotbox(){
	
	// Gallery hot box
	/*
	$('#slideshow').fadeSlideShow({
		 persist: false
	});
	*/
	
	// Xac dinh chieu rong cua gallery
	$(".gallery_hotbox").css('width',$(window).width() - 400);
	$("ul#slideshow").css('height',$(window).height());
	
	$("#supersized").css('width',$(window).width() - 400);
	//$("#supersized li").css('width',$(window).width() - 400);
	
	// xac dinh trong tam hinh anh chieu rong
	$a = ($(window).width() - 400)/2;
	$b = $('ul#slideshow li').width()/2;
	$("ul#slideshow li").css('left',$a - $b);
	// xac dinh trong tam hinh anh chieu cao
	$c = $(window).height()/2;
	$d = $('ul#slideshow li').height()/2;
	$("ul#slideshow li").css('top',$c - $d);
	
	
	/*$cuaso = ($(window).width() - 400)/2;
	  $hinhanh = $('#supersized li img').width();
	  if($hinhanh > $cuaso){
		$('#supersized li img').css("width","100%");
	  }
	  else
	  { $('#supersized li img').css("width","auto");}
	  */
		
	$(".zoom_pics").click(function(){
	  $(".main .col-right").hide();
	  $(".page-filter").hide();
	  $(".popup_hotbox").show();
	  $("#supersized").show();
	  $("#supersized").css("display","block");
	  $("#supersized li img").css("top","0");
	  $(".col-nav + .main").addClass("left_0"); 
	  $(".col-right-hotnew .pint-style").css("position","absolute");
	  
	  /*$cuaso = ($(window).width() - 400)/2;
	  $hinhanh = $('#supersized li img').width();
	  if($hinhanh > $cuaso){
	  	$('#supersized li img').css("width","100%");
	  }
	  else
	  { $('#supersized li img').css("width","auto");}
	  */
	});
	
	$(".gallery_close").click(function(){
	  $(".main .col-right").hide();	
	  $(".page-filter").show();
	  $(".popup_hotbox").hide();
	  $("#supersized").hide();  
	  $(".col-nav + .main").removeClass("left_0");
	  $(".col-right-hotnew .pint-style").css("position","relative");
	});
	
	$cuaso = ($(window).width() - 400)/2;
	 $hinhanh = $('#supersized li img').width();
	 $('#supersized li img').css("top",$cuaso - $hinhanh / 2);
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
// Show Send message Success
function ShowPopMessageSuccess(){
	$('body').showLayer({
		layerClass: '.pop-mess-succ',	
		overlayOpacity: 0.3
	});
}
// Show Send message Success
function ShowPopMessageFail(){
	$('body').showLayer({
		layerClass: '.pop-mess-fail',	
		overlayOpacity: 0.3
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
function ShowPopFindHim(){
	$('.members .area').live('click',function(e){
		if(e) e.preventDefault();
		$('body').showLayer({
			layerClass: '#find-him-pop',	
			overlayOpacity: 0.3
		});
	});
}
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
function sprHide(wRealBox){
	$(".btn-hide").click(function(e){
		//e.stopPropagation();
		$(".col-feed").removeClass("col-left");
		$(".members").addClass("feed-hidden");
		$('.main .col-right .members .list ul li,.more-wrap').hide().css('visibility','hidden');
		setTimeout(function(){
			boxScale(wRealBox);
		},320);
		
	});
	$(".btn-open-feed").click(function(e){
		e.stopPropagation();
		$(".col-feed").addClass("col-left");
		$(".members").removeClass("feed-hidden");
		$('.main .col-right .members .list ul li,.more-wrap').hide().css('visibility','hidden');
		setTimeout(function(){
			boxScale(wRealBox);
		},320);
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
			contentTouchScroll: true,
			autoScrollOnFocus:false,
		}
	});
}
function sprScroll(cls){
	$(cls).mCustomScrollbar({
		scrollInertia: "0",
		mouseWheelPixels: "auto",
		autoHideScrollbar: false,
		advanced:{
			updateOnContentResize: true,
			contentTouchScroll: true,
			autoScrollOnFocus:false,
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
		$(".pop-status .status").focus();
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
		
		//doi icon
		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-top","none");
		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-bottom","6px solid #333333");
		//thay class
		$(this).addClass("btn-slide-active");
		$(this).removeClass("btn-slide");
	});
	$(".chat-box-area .btn-slide-active").click(function(){
		//doi icon
		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-top","none");
		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-bottom","6px solid #333333");
		//thay class
		$(this).addClass("btn-slide");
		$(this).removeClass("btn-slide-active");
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
		$(".pos_chat_boxed_end").css('left',$cur_width_chat - 1150);*/
		
		vitri_chatbox();
		
	});
	$(".chat-boxed .title").click(function(){
		$(this).closest(".chat-boxed-wrap").toggleClass("hide").find(".chat-conversation").slideToggle();
	});
}

