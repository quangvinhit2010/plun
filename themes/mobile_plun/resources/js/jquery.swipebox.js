/*---------------------------------------------------------------------------------------------

 @author       Constantin Saguin - @brutaldesign
 @link            http://bsign.co
 @github        http://github.com/brutaldesign/swipebox
 @version     1.1.2
 @license      MIT License

 ----------------------------------------------------------------------------------------------*/

$(function (window, document, $, undefined) {

    $.swipebox = function (elem, options) {
        var defaults = {
                useCSS: true,
                hideBarsDelay: 0
            },
            plugin = this,
            $elem = $(elem),
            elem = elem,
            selector = elem.selector,
            $selector = $(selector),
            isTouch = document.createTouch !== undefined || ('ontouchstart' in window) || ('onmsgesturechange' in window) || navigator.msMaxTouchPoints,
            supportSVG = !!(window.SVGSVGElement),
            html = '<div id="swipebox-overlay">\
						<div id="swipebox-slider"></div>\
						<div id="swipebox-caption"></div>\
						<a id="swipebox-close"></a>\
						<a id="swipebox-prev"></a>\
						<a id="swipebox-next"></a>\
						<div id="swipebox-description"><a href="#" id="icon_expan"></a><a href="http://vnexpress.net/" class="icon_totalcomment"><span></span></a><a href="http://vnexpress.net/" class="icon_backdetail">&nbsp;</a></div>\
					</div>';
        plugin.settings = {}

        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);
            $(document).ready(function (e) {
                index = 0;
                ui.target = $(e.target);
                ui.init(index);
            });
            $selector.click(function (e) {
                e.preventDefault();
                e.stopPropagation();
                index = $elem.index($(this));
                ui.target = $(e.target);
                ui.init(index);
            });
        }

        var ui = {
            init: function (index) {
                this.target.trigger('swipebox-start');
                this.build();
                this.openSlide(index);
                this.openImg(index);
                this.preloadImg(index + 1);
                this.preloadImg(index - 1);
            },

            build: function () {
                var $this = this;

                $('body').append(html);

                if ($this.doCssTrans()) {
                    $('#swipebox-slider').css({
                        '-webkit-transition': 'left 0.4s ease',
                        '-moz-transition': 'left 0.4s ease',
                        '-o-transition': 'left 0.4s ease',
                        '-khtml-transition': 'left 0.4s ease',
                        'transition': 'left 0.4s ease'
                    });
                    $('#swipebox-overlay').css({
                        '-webkit-transition': 'opacity 1s ease',
                        '-moz-transition': 'opacity 1s ease',
                        '-o-transition': 'opacity 1s ease',
                        '-khtml-transition': 'opacity 1s ease',
                        'transition': 'opacity 1s ease'
                    });
                    $('#swipebox-caption,#swipebox-description,#swipebox-prev,#swipebox-next,#swipebox-close').css({
                        '-webkit-transition': '0.5s',
                        '-moz-transition': '0.5s',
                        '-o-transition': '0.5s',
                        '-khtml-transition': '0.5s',
                        'transition': '0.5s'
                    });
                }
                $elem.each(function () {
                    $('#swipebox-slider').append('<div class="slide"></div>');
                });

                $this.setDim();
                $this.actions();
                $this.keyboard();
                $this.gesture();
                $this.animBars();

                $(window).resize(function () {
                    $this.setDim();
                }).resize();
            },

            setDim: function () {
                var sliderCss = {
                    width: $(window).width(),
                    height: window.innerHeight ? window.innerHeight : $(window).height() // fix IOS bug
                }

                $('#swipebox-overlay').css(sliderCss);

            },

            supportTransition: function () {
                var prefixes = 'transition WebkitTransition MozTransition OTransition msTransition KhtmlTransition'.split(' ');
                for (var i = 0; i < prefixes.length; i++) {
                    if (document.createElement('div').style[prefixes[i]] !== undefined) {
                        return prefixes[i];
                    }
                }
                return false;
            },

            doCssTrans: function () {
                if (plugin.settings.useCSS && this.supportTransition()) {
                    return true;
                }
            },

            gesture: function () {
                if (isTouch) {
                    var $this = this,
                        distance = null,
                        swipMinDistance = 10,
                        startCoords = {},
                        endCoords = {};
                    var b = $('#swipebox-caption,#swipebox-description,#swipebox-prev,#swipebox-next,#swipebox-close');

                    b.addClass('visible-bars');
                    $this.setTimeout();
                    $('#swipebox-slider').bind('touchstart',function (e) {
                        $(this).addClass('touching');

                        endCoords = e.originalEvent.targetTouches[0];
                        startCoords.pageX = e.originalEvent.targetTouches[0].pageX;

                        $('.touching').bind('touchmove', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            endCoords = e.originalEvent.targetTouches[0];

                        });

                        return false;

                    }).bind('touchend', function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                            distance = endCoords.pageX - startCoords.pageX;

                            if (distance >= swipMinDistance) {
                                // swipeLeft
                                $this.getPrev();
                            }

                            else if (distance <= -swipMinDistance) {
                                // swipeRight
                                $this.getNext();

                            } else {
                                // tap
                                if (!b.hasClass('visible-bars')) {
                                    //$this.showBars();
                                    $this.setTimeout();
                                } else {
                                    $this.clearTimeout();
                                    //$this.hideBars();
                                }

                            }

                            $('.touching').off('touchmove').removeClass('touching');

                        });

                }
            },

            setTimeout: function () {
                if (plugin.settings.hideBarsDelay > 0) {
                    var $this = this;
                    $this.clearTimeout();
                    $this.timeout = window.setTimeout(function () {
                            $this.hideBars()
                        },
                        plugin.settings.hideBarsDelay
                    );
                }
            },

            clearTimeout: function () {
                window.clearTimeout(this.timeout);
                this.timeout = null;
            },

            showBars: function () {
                var b = $('#swipebox-caption,#swipebox-prev,#swipebox-next,#swipebox-close,#swipebox-description,#icon_expan');
                if (this.doCssTrans()) {
                    b.addClass('visible-bars');
                } else {
                    b.fadeIn(1000);
                    setTimeout(function () {
                        b.addClass('visible-bars');
                    }, 1000);
                }
            },

            hideBars: function () {
                var b = $('#swipebox-caption,#swipebox-prev,#swipebox-next,#swipebox-close, #swipebox-description,');
                if (this.doCssTrans()) {
                    b.removeClass('visible-bars');
                } else {
                    b.fadeOut(1000);
                    setTimeout(function () {
                        b.removeClass('visible-bars');
                    }, 1000);
                }
            },

            animBars: function () {
                var $this = this;
                var b = $('#swipebox-caption, #swipebox-description,#swipebox-prev,#swipebox-next,#swipebox-close,#icon_expan');

                b.addClass('visible-bars');
                $this.setTimeout();

                $('#swipebox-slider').click(function (e) {
                    if (!b.hasClass('visible-bars')) {
                        $this.showBars();
                        $this.setTimeout();
                    }
                });
            },

            keyboard: function () {
                var $this = this;
                $(window).bind('keyup', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (e.keyCode == 37) {
                        $this.getPrev();
                    }
                    else if (e.keyCode == 39) {
                        $this.getNext();
                    }
                    else if (e.keyCode == 27) {
                        $this.closeSlide();
                        window.location = window.location.href.substring(0, (window.location.href.length - 16));
                    }
                });
            },

            actions: function () {
                var $this = this;

                if ($elem.length < 2) {
                    $('#swipebox-prev, #swipebox-next').hide();
                } else {
                    $('#swipebox-prev').bind('touchend', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $this.getPrev();
                        $this.setTimeout();
                    });

                    $('#swipebox-next').bind('touchend', function (e) {
                        //e.preventDefault();
                        e.stopPropagation();
                        $this.getNext();
                        $this.setTimeout();
						
                    });
                }

                $('#icon_expan').bind('touchend', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if ($('#swipebox-description').hasClass('expan_now')) {
                        $('#swipebox-description').removeClass('expan_now');
                        $(this).removeClass('expan_common');
                        $('.icon_totalcomment').removeClass('expan_common');
                        $('.icon_backdetail').removeClass('expan_common');
                    }
                    else {
                        $('#swipebox-description').addClass('expan_now');
                        $(this).addClass('expan_common');
                        $('.icon_totalcomment').addClass('expan_common');
                        $('.icon_backdetail').addClass('expan_common');
                    }
                });
                $('#swipebox-close').bind('click touchend', function (e) {
                    $this.closeSlide();
                    window.location = window.location.href.substring(0, (window.location.href.length - 16));
                });
            },

            setSlide: function (index, isFirst) {
                isFirst = isFirst || false;

                var slider = $('#swipebox-slider');

                if (this.doCssTrans()) {
                    slider.css({ left: (-index * 100) + '%' });
                } else {
                    slider.animate({ left: (-index * 100) + '%' });
                }

                $('#swipebox-slider .slide').removeClass('current');
                $('#swipebox-slider .slide').eq(index).addClass('current');
                this.setTitle(index);
                this.setDescription(index);

                if (isFirst) {
                    slider.fadeIn();
                }

                $('#swipebox-prev, #swipebox-next').removeClass('disabled');
                if (index == 0) {
                    $('#swipebox-prev').addClass('disabled');
                } else if (index == $elem.length - 1) {
                    $('#swipebox-next').addClass('disabled');
                }
            },

            openSlide: function (index) {

                $('html').addClass('swipebox');
                $(window).trigger('resize'); // fix scroll bar visibility on desktop
                this.setSlide(index, true);
            },

            preloadImg: function (index) {
                var $this = this;
                setTimeout(function () {
                    $this.openImg(index);
                }, 1000);
            },

            openImg: function (index) {
                var $this = this;
                if (index < 0 || index >= $elem.length) {
                    return false;
                }

                $this.loadImg($elem.eq(index).attr('href'), function () {
                    $('#swipebox-slider .slide').eq(index).html(this);
                });
            },

            setTitle: function (index, isFirst) {
                $('#swipebox-caption').empty();

                if ($elem.eq(index).attr('title')) {
                    $('#swipebox-caption').append($elem.eq(index).attr('title'));
                }
            },

            setDescription: function (index, isFirst) {
                $('#swipebox-description .content_desc').empty();
                $('#swipebox-description').append('<div class="decs_cript"><div class="main_decs_content"></div></div>');
                if ($elem.eq(index).attr('rel')) {
                    $('#swipebox-description .decs_cript .main_decs_content').append($elem.eq(index).attr('rel'));
                }
            },

            loadImg: function (src, callback) {
                var img = $('<img>').on('load', function () {
                    callback.call(img);
                });

                img.attr('src', src);
            },

            getNext: function () {
                var $this = this;
                $('#swipebox-description').find('.decs_cript').remove();
                index = $('#swipebox-slider .slide').index($('#swipebox-slider .slide.current'));
                if (index + 1 < $elem.length) {
                    index++;
                    $this.setSlide(index);
                    $this.preloadImg(index + 1);
                }
                else {

                    $('#swipebox-slider').addClass('rightSpring');
                    setTimeout(function () {
                        $('#swipebox-slider').removeClass('rightSpring');
                    }, 500);
                    $this.closeSlide();
                    window.location = window.location.href.substring(0, (window.location.href.length - 16));
                }
            },

            getPrev: function () {
                var $this = this;
                $('#swipebox-description').find('.decs_cript').remove();
                index = $('#swipebox-slider .slide').index($('#swipebox-slider .slide.current'));
                if (index > 0) {
                    index--;
                    $this.setSlide(index);
                    $this.preloadImg(index - 1);
                }
                else {

                    $('#swipebox-slider').addClass('leftSpring');
                    setTimeout(function () {
                        $('#swipebox-slider').removeClass('leftSpring');
                    }, 500);
                    $this.closeSlide();
                    window.location = window.location.href.substring(0, (window.location.href.length - 16));
                }
            },

            closeSlide: function () {
                var $this = this;
                $(window).trigger('resize');
                $('html').removeClass('swipebox');
                $this.destroy();
            },

            destroy: function () {
                var $this = this;
                $(window).unbind('keyup');
                $('body').unbind('touchstart');
                $('body').unbind('touchmove');
                $('body').unbind('touchend');
                $('#swipebox-slider').unbind();
                $('#swipebox-overlay').remove();
                $elem.removeData('_swipebox');
                $this.target.trigger('swipebox-destroy');
            }

        }

        plugin.init();

    }

    $.fn.swipebox = function (options) {
        if (!$.data(this, "_swipebox")) {
            var swipebox = new $.swipebox(this, options);
            this.data('_swipebox', swipebox);
        }
    }

}(window, document, jQuery));
