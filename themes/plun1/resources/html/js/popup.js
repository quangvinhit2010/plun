$.fn.showLayer = function(options){		
	var defaults = {
		layerClass: '',
		overlayOpacity: 0.5,
		relVal: ''
	};
	var options = $.extend(defaults, options); 
	return this.each(function(){  
		var that = $(this);	
		that.vars = {
			overlay: null,
			layer: null
		};

		that.vars.overlay = $('#overlay');
		if(!that.vars.overlay.length){
			that.vars.overlay = $('<div id="overlay"></div>').appendTo(document.body);
		}			
		that.vars.layer = $(options.layerClass);
		if(that.vars.layer){
			that.vars.overlay.css({
				'opacity': 0,
				'display': 'block',	
				'position': 'fixed'
			}).stop(true).animate({
				'opacity': options.overlayOpacity
			}, 500);
			that.vars.layer.css({
				'position': 'absolute',
				'opacity': 0,
				'z-index': 100000,
				'top': ($(window).height() - that.vars.layer.height()) / 2,
				'left': ($(window).width() - that.vars.layer.width()) / 2						
			}).stop(true).delay(200).show().animate({						
				'opacity': 1						
			});
			that.vars.layer.find('.btn-close').unbind('click').bind('click',function(e){
				if(e) e.preventDefault();
				that.vars.layer.animate({
					'opacity': 0
				}).queue(function(){
					that.vars.layer.css({
						'top': -10000,
						'left': -10000
					});
					that.vars.layer.dequeue();
				}).hide();
				that.vars.overlay.delay(200).animate({
					'opacity': 0							
				}).queue(function(){
					that.vars.overlay.css({
						'display': 'none'
					});
					that.vars.overlay.dequeue();
				});
				$('.alert-layer').fadeOut();
			});
			/* that.vars.overlay.unbind('click').bind('click',function(e){
				that.vars.layer.find('.btn-close').trigger('click');
			}); */
		}	
		$(window).resize(function(){
			if(that.vars.layer.css('opacity') == '1'){
				that.vars.layer.css({
					'position': 'absolute',
					'top': (that.vars.layer.height() > $(window).height()) ? 15 : ($(window).height() - that.vars.layer.height()) / 2,
					'left': ($(window).width() - that.vars.layer.width()) / 2	
				});
				that.vars.overlay.css({
					'position': 'fixed'
				});
			}
		});

	});  
};