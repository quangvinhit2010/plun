// JavaScript Document
$(document).ready(function(){
    if($('.addAlertApp').length > 0){
        $('body').addClass('existAlertApp');
    }

	var doc_width = $(window).width();
	//alert(doc_width);
	
	//var comment_height = $(window).height() - 46;	
	//$('#block_data_center').css('height',comment_height);
	//alert(comment_height);
	
	// chieu cao trang set avatar photo
	$('.photo_detail').css('height',$(window).height() - 60);

    updateActivity();
	setTimeout(function(){
		$('.alert-box').addClass('show');
	},1500);
});
$(function() {
	//Enable swiping...
	$(".btn_close_col_left").swipe( {
		//Generic swipe handler for all directions
		swipeLeft:function(event, direction, distance, duration, fingerCount) {
			$('body').removeClass('active_panel_left');
            $('#block_data_center').css('position','static');
		},
		tap:function(event, target) {
			$('body').removeClass('active_panel_left');
            $('#block_data_center').css('position','static');
		},
		//Default is 75px, set to 0 for demo so any distance triggers swipe
		threshold:0
	});
	$(".btn_close_col_right").swipe( {
	//Generic swipe handler for all directions
	swipeRight:function(event, direction, distance, duration, fingerCount) {
		$('body').removeClass('active_panel_right');
			$('#block_data_center').css('position','static');
	},
	
		tap:function(event, target) {
			$('body').removeClass('active_panel_right');
			$('#block_data_center').css('position','static');
		},
	//Default is 75px, set to 0 for demo so any distance triggers swipe
	threshold:0
	});
	
	var Numberscroll = $(window).scrollTop();
	var lastScroll = 0;
      $(window).scroll(function(event){
          //Sets the current scroll position
          var st = $(this).scrollTop();
          //Determines up-or-down scrolling
          if (st > lastScroll){
             //Replace this with your function call for downward-scrolling
             //$("#wrapper_header").animate({
				//top: -40 
				//top: 0
			  //}, 10);
          }
          else {
             //Replace this with your function call for upward-scrolling
             //$("#wrapper_header").animate({
				//top: 0
			  //}, 10);
          }
          //Updates scroll position
          lastScroll = st;
      });
		
	$('.block_icon_search').toggle(
		function(){
			$(this).prev().fadeIn(200);
		},
		
		function(){
			$(this).prev().fadeOut(100);
		}
	);
	//nut dong trai - show menu
	$('.btn_control_col_left').click(function(){
        if($('body').hasClass('active_panel_left')){
			$('body').removeClass('active_panel_left');
        }
		else{
            $('body').addClass('active_panel_left');
			//$('#block_data_center').css('position','fixed');
		}
	});
	$('.btn_close_col_left').click(function(){
        $('body').removeClass('active_panel_left');
		//$('#block_data_center').css('position','static');
	});
	
	//nut dong phai
	/*$('.control_rightpanel').click(function(){
		if($('body').hasClass('active_panel_right')){
			$('body').removeClass('active_panel_right');
			$('#block_data_center').css('position','static');
		}
		else{
			$('body').addClass('active_panel_right');
			$('#block_data_center').css('position','fixed');		
		}
	});*/
	//nutdong content
	$('.btn_close_col_left').click(function(){
		$('body').removeClass('active_panel_left');
        //$('#block_data_center').css('position','static');
	});
	
	$('.btn_close_col_right').click(function(){
		$('body').removeClass('active_panel_right');
		//$('#block_data_center').css('position','static');
	});
	
	$('.block_btn_poster').find('a').click(
		function(){
			var idShow = $(this).attr('rel');
			$('.block_btn_poster a').removeClass('active');
			$(this).addClass('active');
			$('.block_data_phim').fadeOut(600);
			$('#'+idShow+'').fadeIn(1000);
		}
	);
	
	var heightDevice = $(window).height();
	var widthDevice = $(window).width();
	//$('.scroll_info_left').height(heightDevice - 2);
	//$('.scroll_info').height(heightDevice-47);
	$(window).resize(function(){
		var heightDevice = $(window).height();
		//$('.scroll_info_left').height(heightDevice - 2);
		//$('.scroll_info').height(heightDevice-47);
	});
	function colcenter(){
		var widthDevice = $(window).width();
		
	}
	 var heightDevice = $(window).height();
	/*
		 $('.block_scoll_menu').slimscroll({
				 wheelStep: 100,
				height : heightDevice+'px'
		  });
		   $('.block_scoll_menu2').slimscroll({
				 wheelStep: 100,
				height : heightDevice+'px'
		  });
	*/
	 loadvirtualformSettings();
});
function Expandecs(){
	if($('#swipebox-description').hasClass('expan_now')){
			$('#swipebox-description').removeClass('expan_now');
	}
	else{
		$('#swipebox-description').addClass('expan_now');
	}
}
function loadvirtualformSettings(){
	$('.virtual_form').live('change',function(){
		var class_text	=	$(this).attr('text');
		var value	=	$(this).find('option:selected').text();
		$('.' + class_text).text(value);
	});
}
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
    }, 900 * 1000)	
}