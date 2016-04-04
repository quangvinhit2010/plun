var About = {
	init: function () {
		$(".ui-widget-overlay").live("click", function (){
			$( ".pop-mess-succ .popcont p" ).html('');
		});
	},
	slideImages: function () {
		$(".aboutus_new ul li .photos").live("click", function (){
			$('body').loading();
			var pop = $(this).closest('li').find('.slider-container'),$sliderBoxImg;
			//$('body').append($cloneBox);
			//$cloneBox.find('.slider').html($cloneSlide.html());
			pop.pdialog({
				width: 790,
				dialogClass: 'dialog-about',
				open: function() {
					$('body').unloading();
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
		});
		$(".aboutus_new ul li .view_video").live("click", function (){
			var _this = $(this);
			$('body').loading();
			var pop = $(this).closest('li').find('.slider-container'), $cloneSlide = pop.clone(),$cloneBox = $('.popSlide').clone();
			//$('body').append($cloneBox);
			//$cloneBox.find('.slider').html($cloneSlide.html());
			pop.pdialog({
				width: 790,
				dialogClass: 'dialog-about',
				open: function() {
					$('body').unloading();
					$(this).show('.slider-container');
					$(this).find('iframe').attr('src',_this.attr('data-src'));
					var txt_caption = $(this).find('iframe').attr('data-caption');
					$(this).append('<div class="bx-caption">'+txt_caption+'</div>');
				},
				beforeClose: function(){
					$('.slider-container').find('iframe').attr('src','');
					$('.bx-caption').remove();
				}
			});
			$(".ui-dialog-titlebar").show();
		});
	},
	viewVideoDetail: function (_src) {
		
		return false;
		
	},
	whatPPsay: function (_link) {
		$(".content ul li .read_more").live("click", function (){
			var pop = $(this).closest('.your_notify').find('.readmore_popup');
			Util.popAlertSuccess('<div class="readmore_detail">' + pop.html() + '</div>', 770);
			var height = $(window).height() - 100;
			if(height >= pop.height()){
				height = pop.height();
			}
			$('.readmore_detail').attr('style', 'left: 0px; top: 0px; overflow: auto; height: ' + height + 'px; margin: 10px 10px;');
			sprScroll('.readmore_detail');
			$('.icon-check').hide();
			$('.popcont').attr('style', 'padding: 10px 10px;');
		});
	},
	
}
