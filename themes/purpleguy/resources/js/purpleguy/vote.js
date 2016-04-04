var Vote = {
	quickPopup: function(message){
		var style = 'position: absolute; top: 50%; left: 50%; background: #ffffff; padding: 20px; z-index: 99999; opacity: 1; box-shadow: 2px 2px 10px 3px rgba(0, 0, 0, 0.3); text-align: center; font-weight: bold; margin-top: -20px';
		var quick_popup = $('<div class="quick-popup" style="'+style+'">'+message+'</div>');
		$('body').append(quick_popup);
		$('.quick-popup').css('margin-left', '-'+quick_popup.outerWidth()/2+'px');
		setTimeout(function(){
			$('.quick-popup').remove();
		}, 2000);
	},
	init: function(){
		
		$('.search input').focus(function(){
			if($(this).val()=='Tìm theo tên hoặc mã số thí sinh')
				$(this).val('');
		}).blur(function(){
			if($(this).val()=='')
				$(this).val('Tìm theo tên hoặc mã số thí sinh');
		});
		$(document).click(function(){
			$('.quick-popup').remove();
		});
		$(document).on('click', 'a.vote_link', function(e){
			var self = $(this);
			$('body').loading();
			var url = self.attr('href');
			$.get(url, function(response){
				$('body').unloading();
				if(response.message=='Bình chọn thành công.') {
					if(self.hasClass('vote')) {
						self.removeClass('vote_link').addClass('vote_active').attr('href', 'javascript:;');
						var like = self.closest('li').find('.like');
						var like_count = Number(like.text())+1;
						like.text(like_count);
					} else {
						self.removeClass('vote_link').addClass('active').attr('href', 'javascript:;').find('span').text('Đã bình chọn');
						var like = $('#'+self.data('id')).find('.like');
						var like_count = Number($('.ui-dialog .voting').text())+1;
						$('#'+self.data('id')).find('.vote').removeClass('vote_link').addClass('vote_active').attr('href', 'javascript:;');
						like.text(like_count);
						$('.ui-dialog .voting').text(like_count);
					}
				}
				
				Vote.quickPopup(response.message);
			}, 'json').fail(function() {
				$( ".popup_login" ).pdialog({
					title: tr('Login'),
					width: 440,
				});	
				$(".ui-dialog-titlebar").hide();
				$('body').unloading();
			});
			e.preventDefault();
		});
		$('.dropdown-box .txt_select').on('click',function(e){
			e.stopPropagation();
			var _this_txt = $(this);
			if(_this_txt.hasClass('active')){
				_this_txt.removeClass('active');
				_this_txt.parent('.dropdown-box').find('ul').slideUp('fast');	
			}else{
				_this_txt.addClass('active');
				_this_txt.parent('.dropdown-box').find('ul').slideDown('fast');
			}
			_this_txt.parent('.dropdown-box').find('ul li').bind('click',function(){
				var _this = $(this),
					val_click = _this.text(),
					url = _this.data('value');
				$('body').loading();
				location.href = url;
				_this_txt.text(val_click);
			});
			Vote.clickOutItemsHide();
		});
		/*$('.sort_item').change(function(){
			$('.country_register_text').text($(this).children(':selected').text());
			$('body').loading();
			var url = $(this).val();
			location.href = url;
		});*/
		$('.list').on('click', '.ava', function(e){
			var url = $(this).attr('data-url');
			var curUrl = $(this).attr('href');
			$('body').loading();
			$.get(url, function(html){
				$('body').unloading();
				$(".popup_vongloai").find('.content').html($(html).html());
				$('.bxslider').bxSlider({
					mode: 'fade',
					captions: true
				});
				$(".popup_vongloai").pdialog({
					width: 840,
				});
				$(".ui-dialog-titlebar").hide();
				sprScroll('.list_comment ul.list_item');
				Util.changeUrlAjax(curUrl);
			}).fail(function() {
				$('body').unloading();
			});
			e.preventDefault();
		});
		$('.list').on('click', '.showmore', function(e){
			$('body').loading();
			var url = $(this).attr('href');
			$.get(url, {ajax: 1}, function(html){
				$('body').unloading();
				var temp = $('<div id="temp">'+html+"</div>");
				
				var list_wrap = $('.list ul');
				var top_vote_li = list_wrap.find('.top_vote_li').removeClass('top_vote_li');
				var top_vote = top_vote_li.find('.top_vote').removeClass('top_vote');
				top_vote.find('> p').remove();
				
				/*
				var li = temp.find('li');
				var img = li.find('a > img');
				img.width(top_vote_li.find('img').eq(0).width());
				img.height(top_vote_li.find('img').eq(0).height());
				*/
				
				var data_top = temp.find('ul').data('top') + '';
				var top_item = data_top.split(",");
				
				var count = top_item.length;
				for(i=0; i<count; i++) {
					var item = $('#user-'+top_item[i]).addClass('top_vote_li');
					var item_inside = item.find('.name').addClass('top_vote');
					item_inside.prepend('<p><img src="/themes/purpleguy/resources/html/css/images/icon_top_vote.png" align="absmiddle"></p>');
				}
				
				list_wrap.append(temp.find('li'));

				$(window).trigger('resize');
				
				list_wrap.attr('data-top', data_top);
				
				var more_link = temp.find('.more-wrap');
				if(more_link.length > 0) {
					$('.more-wrap > a').attr('href', more_link.find('a').attr('href'));
				} else {
					$('.more-wrap').remove();
				}
			});
			e.preventDefault();
		});
	},
	clickOutItemsHide: function(){
		$('html,body').on('click',function(e){
			e.stopPropagation();
			if($('.dropdown-box').length > 0){
				$('.dropdown-box ul').hide();
				$('.dropdown-box .txt_select').removeClass('active');
			}
		});
	}
};
$(document).ready(function(){
	var hash = window.location.hash.substr(1);
	if(!isNaN(hash) && hash != '') {
		$('body').loading();
		$.get('vote/LoadDetail', {user_id: hash}, function(html){
			$('body').unloading();
			$(".popup_vongloai").find('.content').html($(html).html());
			$('.bxslider').bxSlider({
				mode: 'fade',
				captions: true
			});
			$(".popup_vongloai").pdialog({
				width: 840,
			});
			$(".ui-dialog-titlebar").hide();
			sprScroll('.list_comment ul.list_item');
		});
	}
});
