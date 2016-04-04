(function($){
	$.fn.popupAnimate = function(options){
		var settings = $.extend({
			positionTop: '10%',
			speed: 200,
			parentBox: '.popupBox',
			wrapBox: '.popupBox .wrap-popup',
			bgOpacity: '.bg-popup',
			btnClose: '.close-popupBox',
			title: 'Gửi tin nhắn',
			addButtonEvent: false,
			btnOk: '.btnOkPopup',
			btnCancel: '.btnCanPopup',
			btnControl: [
				{
					title: 'OK',
					clickEvent: function(){}
				}
			],
			contentAppend: null
		}, options);

		return this.each(function(){
			var _this = $(this),
				$bg = $('<div class="bg-popup fade in"></div>'),
				wW = $(window).outerWidth(),
				hW = $(window).outerHeight();
			var _this = $(this),
				id = _this.attr('data-id');
			$('body').append($bg);
			$bg.fadeIn(settings.speed, function() {
				$(this).addClass('in');
			});
			_this.find('.title-popupBox span').text(settings.title);
			if(settings.positionTop === 'center'){
				var hP = $(this).find('.wrap-popup').outerHeight(),
					wP = $(this).find('.wrap-popup').outerWidth(),
					cP = hW/2 - hP/2;
				settings.positionTop = cP;
			}
			_this.find('.wrap-popup').fadeIn(settings.speed + 200, function() {
				$(this).addClass('in').css({
					top: settings.positionTop
				});
			});
			$(""+settings.bgOpacity+", "+settings.btnClose+" ").live('click',function(e){
				e.preventDefault();
				closePopup();
			});
			if(settings.contentAppend != null){
				_this.find('.wrap-popup .wrap-container').html(settings.contentAppend);
			}
			if(settings.addButtonEvent && settings.btnControl.length > 0 && _this.find('.btn-popup').length == 0){
				var x = 0, temp;
				for(var i=0; i< settings.btnControl.length; i++){
					_this.find('.wrap-popup').append('<span class="btn-popup">'+settings.btnControl[i].title+'</span>');
					temp = i;
					_this.find('.wrap-popup .btn-popup').get(i).addEventListener('click',function(){
						console.log(temp);
						return settings.btnControl[i].clickEvent();
					});
				}
			}
			
			function closePopup(){
				$(settings.wrapBox).removeClass('in').css({top: '-50%'});
				$(settings.bgOpacity).fadeOut(400, function() {
					var _this = $(this);
					setTimeout(function(){
						_this.remove();	
					},200);
				});
			}
		});
	}
})(jQuery);