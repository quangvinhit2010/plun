var About = {
	init: function () {
		$(document.body).on('click', '.ui-widget-overlay', function() {
			$( ".pop-mess-succ .popcont p" ).html('');
		});
	},
	slideImages: function () {
		$(document.body).on('click', '.about_gallery ul li .photos', function() {
			objCommon.loading();
			var pop = $(this).closest('li').find('.slider-container'),$sliderBoxImg;
			$('.pics_event').html(pop.html());
			$('.pics_event').find('.slider-container').show();
			$sliderBoxImg = $('.pics_event').find('.bxsliders').bxSlider({
				mode: 'fade',
				pager: false,
				captions: true,
				onSliderLoad: function(){
					objCommon.unloading();
				},
				onSlideAfter: function(){
					objCommon.unloading();
				}
			});	
			//$('body').append($cloneBox);
			//$cloneBox.find('.slider').html($cloneSlide.html());
			/*
			pop.pdialog({
				width: 790,
				dialogClass: 'dialog-about',
				open: function() {
					objCommon.unloading();
					objCommon.outSiteDialogCommon(this);
					$(this).find('.slider').show();
					$sliderBoxImg = $(this).find('.bxsliders').bxSlider({
						mode: 'fade',
						pager: false,
						captions: true
					});	
				},
				beforeClose: function(){
					$('.slider-container').find('iframe').attr('src','');
					$sliderBoxImg.destroySlider();
				}
			});
			$(".ui-dialog-titlebar").show();
			*/
		});
		$(document.body).on('click', '.about_gallery ul li .view_video', function() {
			var _this = $(this);
			var pop = $(this).closest('li').find('.slider-container'), $cloneSlide = pop.clone(),$cloneBox = $('.popSlide').clone();
			console.log(_this.attr('data-src'));
			pop.find('iframe').attr('src',_this.attr('data-src'));
			$('.pics_event').html(pop.html());
			$('.pics_event').find('.slider-container').show();
			$sliderBoxImg = $('.pics_event').find('.bxsliders').bxSlider({
				mode: 'fade',
				pager: false,
				captions: true
			});
			//$('body').append($cloneBox);
			//$cloneBox.find('.slider').html($cloneSlide.html());
			/*
			pop.pdialog({
				width: 790,
				dialogClass: 'dialog-about',
				open: function() {
					objCommon.unloading();
					$(this).show('.slider-container');
					$(this).find('iframe').attr('src',_this.attr('data-src'));
					console.log(_this.attr('data-src'));
					var txt_caption = $(this).find('iframe').attr('data-caption');
					$(this).append('<div class="bx-caption">'+txt_caption+'</div>');
				},
				beforeClose: function(){
					$('.slider-container').find('iframe').attr('src','');
					$('.bx-caption').remove();
				}
			});
			$(".ui-dialog-titlebar").show();
			*/
		});
	},
	viewVideoDetail: function (_src) {
		
		return false;
		
	},
	whatPPsay: function () {
		$(document.body).on('click', '.about_vip ul li .read_more', function() {
			var pop = $(this).closest('.your_notify').find('.popup_whatsay');
			$('<div class="popup_whatsay">' + pop.html() + '</div>').pdialog({
				open: function(event, ui) {
					$("body").css({ overflow: 'hidden' });
					objCommon.no_title(); // config trong file jquery-ui.js
					objCommon.outSiteDialogCommon(this);
					$(".ui-dialog-titlebar-close").hide();// tat nut close
					// Click outside to close popup
					jQuery('.ui-widget-overlay').bind('click',function(){
						jQuery('.popup_whatsay').dialog('close');
					})
				},
				resizable: false,
				position: 'middle',
				draggable: false,
				autoOpen: false,
				center: true,
				width: 700,
				maxHeight: 700,
				modal: true			
			});
			objCommon.sprScroll(".popup_whatsay");
		});
	},
	
}
