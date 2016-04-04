$(function(){
});

var Rate = {	
	Init: function () {
		Rate.showList();
		Rate.rateHim();
	},
	showList: function () {
		$(document.body).on('click',".showlist_danhhieu", function() {	
			var _this = $(this);				
			objCommon.loadingInside($('.danhhieu'));
			$('.loadingInside').exists(function(){				
				$('.loadingInside').css({"position":"absolute", "top":_this.position().top, "left":_this.position().left});
			});
			var url = _this.attr('data-url');
			$.ajax({
				type: "POST",
				url: url,
				data: {'type': 1},
				dataType: 'json',
				success: function( response ) {
					if(response.error == 0){
						$( response.html).exists(function(){							
							$(response.html).pdialog({
								open: function(){		
									objCommon.no_title(this);
									objCommon.outSiteDialogCommon(this);									
								},
								width: 540
							});						
							objCommon.unloadingInside();
						});
					}
				}
			});
		});
	},
	rateHim: function () {
		$(document.body).on('click',".rateHim", function() {	
			var _this = $(this);				
			objCommon.loadingInside($('.list_function'));
			$('.loadingInside').exists(function(){				
				$('.loadingInside').css({"position":"absolute", "top":_this.position().top, "left":_this.position().left});
			});
			var url = _this.attr('data-url');
			$.ajax({
				type: "POST",
				url: url,
				data: {'type': 1},
				dataType: 'json',
				success: function( response ) {
					if(response.error == 0){
						$( response.html).exists(function(){							
							$(response.html).pdialog({
								open: function(){
									objCommon.no_title(this);
									objCommon.outSiteDialogCommon(this);									
								},
								width: 570
							});						
							objCommon.unloadingInside();
							objCommon.sprScroll(".popup_chondanhhieu .content");
						});
					}
				}
			});
		});
	},
	
}
