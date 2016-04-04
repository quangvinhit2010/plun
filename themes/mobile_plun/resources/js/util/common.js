$(function(){
	comingSoon();
	uiloading();
});
var cTotalFrames=18;
var cFrameWidth=80;
var cIndex=0;
var cXpos=0;
var cImageSrc='/themes/mobile_plun/resources/css/images/graphics/sprites_2.png';
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
		html += '<span style="display:none;" id="loading2" class="loading">';
		html += '<div id="loaderImage"></div>';
		html += '</span>';
		if($('.loading').length > 0){
			$('.loading').fadeIn('fast');
		}else{
			$('body').append(html);
            $('.loading').fadeIn('fast');
		}
		changeBackgroundLoading();
	};
	$.fn.unloading  = function(options) {
		clearTimeout(cPreloaderTimeout);
		$('.loading').remove();
        if($('.list_user').length > 0){
            $('#menu_footer').css({
                position: 'relative',
                'z-index': 9999,
                float: 'left',
                width: '100%'
            });
        }
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
		}, 1000);
	});
	
	$(".not-available").click(function () {
		Util.popAlertFail(tr('This feature is not available for this device at the moment!'), 250);
		setTimeout(function () {
			$( ".pop-mess-fail" ).dialog('close');
		}, 1000);
	});
}

jQuery.fn.center = function () {
	this.css("position","absolute");
	this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
	this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
	return this;
}

$(".ui-widget-overlay").live("click", function (){
	$("div:ui-dialog:visible").pdialog("close");
});

jQuery.fn.pdialog = function (options) {
	switch (options) {
		case 'close':
			this.dialog('close');
			break;
		default:
			var settings = jQuery.extend({
				autoResize: false,
				minHeight: false,
				minWidth: 250,
				width: false,
				position: "absolute",
				autoOpen: false,
				modal: true,
				draggable: false,
				resizable: false,
				overlay: { backgroundColor: "#000000", opacity: 0.5 }
			}, options);
			this.dialog(settings);
			this.dialog('open');
			$(".ui-dialog-titlebar").show();
			break;
	}
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
        $( ".pop-mess-succ .popcont p" ).html(content);
		$( ".pop-mess-succ" ).pdialog({
			width: _wd
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
	isSupportUpload: function(){
		if (navigator.userAgent.match(/(Android (1.0|1.1|1.5|1.6|2.0|2.1))|(Windows Phone (OS 7|8.0))|(XBLWP)|(ZuneWP)|(w(eb)?OSBrowser)|(webOS)|(Kindle\/(1.0|2.0|2.5|3.0))/)) {
			   return false;
		}
		return true;
	},
	imgSize: function(img) {
		img.attr('style', '');
		var ww = $(window).width() - 0;
	    var wh = $(window).height() - 0;
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
	}
};

/*
$(window).resize(function() {
	Util.footerPosition();
});*/
$(document).ready(function () {
    $('#replymsg-form .replyMsg,.comment-form .cmt-post-text').focus(function(e){
        $('#menu_footer').addClass('unfixed');
    });
    $('#replymsg-form .replyMsg,.comment-form .cmt-post-text').blur(function(e){
        $('#menu_footer').removeClass('unfixed');
    });

    //height body and fix footer bottom page
    var $block = $('#block_data_center'),
        hHeader_hFooter = 66,
        hDocument = $(document).height(),
        hWindow = $(window).height();
    if($block.height() < hDocument - hHeader_hFooter){
        $block.css({
            'min-height': hDocument - hHeader_hFooter
        });
    }

    var iOS = parseFloat(
        ('' + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent) || [0,''])[1])
            .replace('undefined', '3_2').replace('_', '.').replace('_', '')
    ) || false;


    if (iOS < 5 && iOS) {
        $('head').append('<link rel="stylesheet" href="'+link_url+'/resources/css/mobile_iphone_ios41.css" type="text/css" />');
    }
    if(isiPad()){
        $('.setting_basic_info input.input-type-3,.other_info input.input-type-3').css({
            visibility: 'visible',
            'appearance': '1px',
            '-webkit-appearance':'1px',
            'border':'1px solid #555',
            'border-radius':'0px',
            'background': '#fff',
            'width': 'auto',
            'float': 'left'
        });
        $('.squaredCheck label').css('display','none');
        $('.squaredCheck label.mar_left_24').css({
            display:'block',
            margin: '0px',
            'line-height': '10px'
        });
    }
});
function isiPad(){
    return (navigator.platform.indexOf("iPad") != -1);
}
(function($) {

    jQuery.fn.extend({
        slimScroll: function(options) {
            var defaults = {

                // width in pixels of the visible scroll area
                width : 'auto',

                // height in pixels of the visible scroll area
                height : '250px',

                // width in pixels of the scrollbar and rail
                size : '7px',

                // scrollbar color, accepts any hex/color value
                color: '#000',

                // scrollbar position - left/right
                position : 'right',

                // distance in pixels between the side edge and the scrollbar
                distance : '1px',

                // default scroll position on load - top / bottom / $('selector')
                start : 'top',

                // sets scrollbar opacity
                opacity : .4,

                // enables always-on mode for the scrollbar
                alwaysVisible : false,

                // check if we should hide the scrollbar when user is hovering over
                disableFadeOut : false,

                // sets visibility of the rail
                railVisible : false,

                // sets rail color
                railColor : '#333',

                // sets rail opacity
                railOpacity : .2,

                // whether  we should use jQuery UI Draggable to enable bar dragging
                railDraggable : true,

                // defautlt CSS class of the slimscroll rail
                railClass : 'slimScrollRail',

                // defautlt CSS class of the slimscroll bar
                barClass : 'slimScrollBar',

                // defautlt CSS class of the slimscroll wrapper
                wrapperClass : 'slimScrollDiv',

                // check if mousewheel should scroll the window if we reach top/bottom
                allowPageScroll : false,

                // scroll amount applied to each mouse wheel step
                wheelStep : 20,

                // scroll amount applied when user is using gestures
                touchScrollStep : 200,

                // sets border radius
                borderRadius: '7px',

                // sets border radius of the rail
                railBorderRadius : '7px'
            };

            var o = $.extend(defaults, options);

            // do it for every element that matches selector
            this.each(function(){

                var isOverPanel, isOverBar, isDragg, queueHide, touchDif,
                    barHeight, percentScroll, lastScroll,
                    divS = '<div></div>',
                    minBarHeight = 30,
                    releaseScroll = false;

                // used in event handlers and for better minification
                var me = $(this);

                // ensure we are not binding it again
                if (me.parent().hasClass(o.wrapperClass))
                {
                    // start from last bar position
                    var offset = me.scrollTop();

                    // find bar and rail
                    bar = me.parent().find('.' + o.barClass);
                    rail = me.parent().find('.' + o.railClass);

                    getBarHeight();

                    // check if we should scroll existing instance
                    if ($.isPlainObject(options))
                    {
                        // Pass height: auto to an existing slimscroll object to force a resize after contents have changed
                        if ( 'height' in options && options.height == 'auto' ) {
                            me.parent().css('height', 'auto');
                            me.css('height', 'auto');
                            var height = me.parent().parent().height();
                            me.parent().css('height', height);
                            me.css('height', height);
                        }

                        if ('scrollTo' in options)
                        {
                            // jump to a static point
                            offset = parseInt(o.scrollTo);
                        }
                        else if ('scrollBy' in options)
                        {
                            // jump by value pixels
                            offset += parseInt(o.scrollBy);
                        }
                        else if ('destroy' in options)
                        {
                            // remove slimscroll elements
                            bar.remove();
                            rail.remove();
                            me.unwrap();
                            return;
                        }

                        // scroll content by the given offset
                        scrollContent(offset, false, true);
                    }

                    return;
                }

                // optionally set height to the parent's height
                o.height = (o.height == 'auto') ? me.parent().height() : o.height;

                // wrap content
                var wrapper = $(divS)
                    .addClass(o.wrapperClass)
                    .css({
                        position: 'relative',
                        overflow: 'hidden',
                        width: o.width,
                        height: o.height
                    });

                // update style for the div
                me.css({
                    overflow: 'hidden',
                    width: o.width,
                    height: o.height
                });

                // create scrollbar rail
                var rail = $(divS)
                    .addClass(o.railClass)
                    .css({
                        width: o.size,
                        height: '100%',
                        position: 'absolute',
                        top: 0,
                        display: (o.alwaysVisible && o.railVisible) ? 'block' : 'none',
                        'border-radius': o.railBorderRadius,
                        background: o.railColor,
                        opacity: o.railOpacity,
                        zIndex: 90
                    });

                // create scrollbar
                var bar = $(divS)
                    .addClass(o.barClass)
                    .css({
                        background: o.color,
                        width: o.size,
                        position: 'absolute',
                        top: 0,
                        opacity: o.opacity,
                        display: o.alwaysVisible ? 'block' : 'none',
                        'border-radius' : o.borderRadius,
                        BorderRadius: o.borderRadius,
                        MozBorderRadius: o.borderRadius,
                        WebkitBorderRadius: o.borderRadius,
                        zIndex: 99
                    });

                // set position
                var posCss = (o.position == 'right') ? { right: o.distance } : { left: o.distance };
                rail.css(posCss);
                bar.css(posCss);

                // wrap it
                me.wrap(wrapper);

                // append to parent div
                me.parent().append(bar);
                me.parent().append(rail);

                // make it draggable and no longer dependent on the jqueryUI
                if (o.railDraggable){
                    bar.bind("mousedown", function(e) {
                        var $doc = $(document);
                        isDragg = true;
                        t = parseFloat(bar.css('top'));
                        pageY = e.pageY;

                        $doc.bind("mousemove.slimscroll", function(e){
                            currTop = t + e.pageY - pageY;
                            bar.css('top', currTop);
                            scrollContent(0, bar.position().top, false);// scroll content
                        });

                        $doc.bind("mouseup.slimscroll", function(e) {
                            isDragg = false;hideBar();
                            $doc.unbind('.slimscroll');
                        });
                        return false;
                    }).bind("selectstart.slimscroll", function(e){
                            e.stopPropagation();
                            e.preventDefault();
                            return false;
                        });
                }

                // on rail over
                rail.hover(function(){
                    showBar();
                }, function(){
                    hideBar();
                });

                // on bar over
                bar.hover(function(){
                    isOverBar = true;
                }, function(){
                    isOverBar = false;
                });

                // show on parent mouseover
                me.hover(function(){
                    isOverPanel = true;
                    showBar();
                    hideBar();
                }, function(){
                    isOverPanel = false;
                    hideBar();
                });

                // support for mobile
                me.bind('touchstart', function(e,b){
                    if (e.originalEvent.touches.length)
                    {
                        // record where touch started
                        touchDif = e.originalEvent.touches[0].pageY;
                    }
                });

                me.bind('touchmove', function(e){
                    // prevent scrolling the page if necessary
                    if(!releaseScroll)
                    {
                        e.originalEvent.preventDefault();
                    }
                    if (e.originalEvent.touches.length)
                    {
                        // see how far user swiped
                        var diff = (touchDif - e.originalEvent.touches[0].pageY) / o.touchScrollStep;
                        // scroll content
                        scrollContent(diff, true);
                        touchDif = e.originalEvent.touches[0].pageY;
                    }
                });

                // set up initial height
                getBarHeight();

                // check start position
                if (o.start === 'bottom')
                {
                    // scroll content to bottom
                    bar.css({ top: me.outerHeight() - bar.outerHeight() });
                    scrollContent(0, true);
                }
                else if (o.start !== 'top')
                {
                    // assume jQuery selector
                    scrollContent($(o.start).position().top, null, true);

                    // make sure bar stays hidden
                    if (!o.alwaysVisible) { bar.hide(); }
                }

                // attach scroll events
                attachWheel();

                function _onWheel(e)
                {
                    // use mouse wheel only when mouse is over
                    if (!isOverPanel) { return; }

                    var e = e || window.event;

                    var delta = 0;
                    if (e.wheelDelta) { delta = -e.wheelDelta/120; }
                    if (e.detail) { delta = e.detail / 3; }

                    var target = e.target || e.srcTarget || e.srcElement;
                    if ($(target).closest('.' + o.wrapperClass).is(me.parent())) {
                        // scroll content
                        scrollContent(delta, true);
                    }

                    // stop window scroll
                    if (e.preventDefault && !releaseScroll) { e.preventDefault(); }
                    if (!releaseScroll) { e.returnValue = false; }
                }

                function scrollContent(y, isWheel, isJump)
                {
                    releaseScroll = false;
                    var delta = y;
                    var maxTop = me.outerHeight() - bar.outerHeight();

                    if (isWheel)
                    {
                        // move bar with mouse wheel
                        delta = parseInt(bar.css('top')) + y * parseInt(o.wheelStep) / 100 * bar.outerHeight();

                        // move bar, make sure it doesn't go out
                        delta = Math.min(Math.max(delta, 0), maxTop);

                        // if scrolling down, make sure a fractional change to the
                        // scroll position isn't rounded away when the scrollbar's CSS is set
                        // this flooring of delta would happened automatically when
                        // bar.css is set below, but we floor here for clarity
                        delta = (y > 0) ? Math.ceil(delta) : Math.floor(delta);

                        // scroll the scrollbar
                        bar.css({ top: delta + 'px' });
                    }

                    // calculate actual scroll amount
                    percentScroll = parseInt(bar.css('top')) / (me.outerHeight() - bar.outerHeight());
                    delta = percentScroll * (me[0].scrollHeight - me.outerHeight());

                    if (isJump)
                    {
                        delta = y;
                        var offsetTop = delta / me[0].scrollHeight * me.outerHeight();
                        offsetTop = Math.min(Math.max(offsetTop, 0), maxTop);
                        bar.css({ top: offsetTop + 'px' });
                    }

                    // scroll content
                    me.scrollTop(delta);

                    // fire scrolling event
                    me.trigger('slimscrolling', ~~delta);

                    // ensure bar is visible
                    showBar();

                    // trigger hide when scroll is stopped
                    hideBar();
                }

                function attachWheel()
                {
                    if (window.addEventListener)
                    {
                        this.addEventListener('DOMMouseScroll', _onWheel, false );
                        this.addEventListener('mousewheel', _onWheel, false );
                        this.addEventListener('MozMousePixelScroll', _onWheel, false );
                    }
                    else
                    {
                        document.attachEvent("onmousewheel", _onWheel)
                    }
                }

                function getBarHeight()
                {
                    // calculate scrollbar height and make sure it is not too small
                    barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
                    bar.css({ height: barHeight + 'px' });

                    // hide scrollbar if content is not long enough
                    var display = barHeight == me.outerHeight() ? 'none' : 'block';
                    bar.css({ display: display });
                }

                function showBar()
                {
                    // recalculate bar height
                    getBarHeight();
                    clearTimeout(queueHide);

                    // when bar reached top or bottom
                    if (percentScroll == ~~percentScroll)
                    {
                        //release wheel
                        releaseScroll = o.allowPageScroll;

                        // publish approporiate event
                        if (lastScroll != percentScroll)
                        {
                            var msg = (~~percentScroll == 0) ? 'top' : 'bottom';
                            me.trigger('slimscroll', msg);
                        }
                    }
                    else
                    {
                        releaseScroll = false;
                    }
                    lastScroll = percentScroll;

                    // show only when required
                    if(barHeight >= me.outerHeight()) {
                        //allow window scroll
                        releaseScroll = true;
                        return;
                    }
                    bar.stop(true,true).fadeIn('fast');
                    if (o.railVisible) { rail.stop(true,true).fadeIn('fast'); }
                }

                function hideBar()
                {
                    // only hide when options allow it
                    if (!o.alwaysVisible)
                    {
                        queueHide = setTimeout(function(){
                            if (!(o.disableFadeOut && isOverPanel) && !isOverBar && !isDragg)
                            {
                                bar.fadeOut('slow');
                                rail.fadeOut('slow');
                            }
                        }, 1000);
                    }
                }

            });

            // maintain chainability
            return this;
        }
    });

    jQuery.fn.extend({
        slimscroll: jQuery.fn.slimScroll
    });

})(jQuery);


