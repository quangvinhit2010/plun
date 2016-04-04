$(function(){
	Util.init();
	comingSoon();
	uiloading();
});
var cTotalFrames=18;
var cFrameWidth=80;
var cIndex=0;
var cXpos=0;
var cImageSrc='/themes/plun1/resources/html/css/images/sprites_2.png';
var cPreloaderTimeout=false;



function uiloading(){
	if($('.loading #loaderImage').length > 0){
		$('.loading').remove();
	}
	$.fn.loading  = function(options) {
		clearTimeout(cPreloaderTimeout);
		$('.loading').remove();
		var rand = Math.floor((Math.random()*100)+1);
		html = '';
		html += '<span id="loading2" class="loading">';
		html += '<div id="loaderImage"></div>';
		html += '</span>';
		if($('.loading').length > 0){
			$('.loading').show();
		}else{
			$('body').append(html);
		}
		changeBackgroundLoading();
	};
	$.fn.unloading  = function(options) {
		clearTimeout(cPreloaderTimeout);
		$('.loading').remove();
	};
}

function changeBackgroundLoading() {
	var myimage = $( '#loaderImage' );
	myimage.attr('style', "background:url('" +cImageSrc+ "'); width: 80px;  height: 80px; ");
	cXpos += cFrameWidth;
	cIndex += 1;
	if (cIndex >= cTotalFrames) {
		cXpos =0;
		cIndex=0;
	}
	myimage.css('background-position', (-cXpos)+'px');
	cPreloaderTimeout = setTimeout( function () {
		changeBackgroundLoading();
	}, 50);
}

function comingSoon(){
	$(".coming-soon").click(function () {
		Util.popAlertSuccess(tr('Coming soon'), 250);
		setTimeout(function () {
			$( ".pop-mess-succ" ).pdialog('close');
		}, 1500);
	});
}

jQuery.fn.center = function () {
	this.css("position","absolute");
	this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
	this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
	return this;
}

/*$(".ui_overlay").live("click", function (){
	$("div:ui-dialog").pdialog("close");
});*/
var idPopup = [];
$(".ui_overlay").live("click", function (){
	idPopup[idPopup.length - 1].pdialog("close");
	//idPopup.splice(idPopup.length - 1,1);
});
var countPopup = 0;
jQuery.fn.pdialog = function (options) {
	var _this = this;
	
	switch (options) {
		case 'close':
			var countOverlay = $('.ui_overlay').length;
			_this.parent().removeClass('in');
			$('.ui_overlay').eq(countOverlay-1).fadeOut(500);
			setTimeout(function(){
				_this.dialog('close');
				$('.ui_overlay').eq(countOverlay-1).remove();
				_this.dialog('destroy');
			},500);
			idPopup.splice(idPopup.length - 1,1);
			break;
		default:
			var $ui_overlay = $('<div class="ui_overlay" id="popup_'+countPopup+'">&nbsp;</div>');
			var settings = jQuery.extend({
				autoResize: false,
				position: "absolute",
				autoOpen: false,
				modal: true,
				draggable: false,
				resizable: false,
				closeOnEscape: false,
				overlay: { backgroundColor: "#000000", opacity: 0.8},
				beforeClose: function(){
					$(this).parent('.ui-widget-content').removeClass('in');
					var x = $('.ui-widget-overlay').length;
					$(_this).parent('.ui-widget-content').fadeOut('fast');
					
					return false;
				}
			}, options);
			this.dialog(settings);
			$(this).parent('.ui-widget-content').addClass('popupBox fade');
			$(this).parent('.ui-widget-content').fadeIn(400, function() {
				var hW = $(window).outerHeight(),
					hBox = $(this).outerHeight(),
					cBox = hW/2 - hBox;
				$(this).addClass('in');
				var check = true;
				if(check == true){
					$(this).css('margin-top','-'+hBox/2+'px');
				}
			});
			this.dialog('open');
			$('body').append($ui_overlay);
			$('#popup_' + countPopup + '').css({
				'z-index': (this.parent().css('z-index')-1)
			});
			countPopup += 1;
			$(".ui-widget-overlay").remove();
			$(".ui-dialog-titlebar").show();
			idPopup.push(_this);
			break;
	}
	
}


var Util = {
	init: function(){
		$('.popup_general .but_close').live('click', function(){
			var cls = $(this).parent().attr('class').split(' ')[0];
			$('.'+cls).pdialog('close');
			return false;
		});
	},
	changeUrlAjax: function(url){
		if(url){
			var stateObj = { foo: 1000 + Math.random()*1001 };
			//replace url on address bar
			window.history.pushState(stateObj, '', url);
		} else {
			console.log("The url can't empty");
			
		}
		
	},	
	popAlertSuccess: function(content, _wd){
		$( ".pop-mess-succ .popcont p" ).html(content);
		$( ".pop-mess-succ" ).pdialog({
			width: _wd,
		});
		$(".ui-dialog-titlebar").hide();
	},
	popAlertFail: function(content, _wd){
		$( ".pop-mess-fail .popcont p" ).html(content);
		$( ".pop-mess-fail" ).pdialog({
			width: _wd,
		});
		$(".ui-dialog-titlebar").hide();
	},
	imgSize: function(img) {
		img.attr('style', '');
		var ww = $(window).width() - 50;
	    var wh = $(window).height() - 50;
	    var imgWidth = img.width();
	    var imgHeight = img.height();
	    var rate = imgWidth / imgHeight;
		imgHeight = imgWidth / rate;
	    if(rate){
	    	if( imgWidth > ww){
	    		imgWidth = ww;
	    		imgHeight = imgWidth / rate;
	    	}
	    	if( imgHeight > wh){
	    		imgHeight = wh;
	    		imgWidth = imgHeight * rate;
	    	}
	    }
	    img.css("width", imgWidth);    
	    img.css("height", imgHeight); 
	    img.css("padding-top", (wh - imgHeight) / 2); 
	    img.show();
	},
	uiTextLoading: function () {
    	return '<span class="txtLoading">Loading...</span>';
    },		
};
