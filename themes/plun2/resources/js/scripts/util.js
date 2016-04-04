jQuery.fn.center = function () {
	this.css("position","absolute");
	this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
	this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
	return this;
}
var Util = {
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
		$( ".pop-mess-succ .popcont p label" ).html(content);
		$( ".pop-mess-succ" ).pdialog({
            open: function(){
                objCommon.no_title(this);
            },
			width: _wd
		});
	},
	popAlertFail: function(content, _wd){
		$( ".pop-mess-fail .popcont p label" ).html(content);
		$( ".pop-mess-fail" ).pdialog({
            open: function(){
                objCommon.no_title(this);
            },
			width: _wd
		});
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
