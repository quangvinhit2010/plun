var getWWin = $(window).outerWidth(),
    getHWin = $(window).outerHeight();
var posiTopFirstMenu;

var parallaxPlun = {
    getCenterPosition: function(itemCenter){
        var wWin = getWWin,
            hWin = getHWin,
            wItem = $(itemCenter).outerWidth(),
            hItem = $(itemCenter).outerHeight(),
            posiItem = {
                cenTop: (hWin - hItem)/2,
                cenLeft: (wWin - wItem)/2
            };

        return posiItem;
    },
    loadingPage: function(){
        var getPosition = this.getCenterPosition('.loadingFirst');
        $('.loadingFirst').css({
            top: getPosition.cenTop,
            left: getPosition.cenLeft,
            visibility: 'visible'
        });
        $('.loadingFirst').animate({
            opacity: 1
        },500);

        this.loadOurTeam();
        this.imgImport('.parallaxPlun .wrapper_container .imgLoading img[data-original]');
        this.processLoading('.parallaxPlun .wrapper_container .imgLoading img[data-original]');
    },
    processLoading: function(imgs){
        var _this = this,
            lengImg = $(imgs).length,
            countImg = 0;
        $('body').css('overflow','hidden');
        if(lengImg > 0){
            $(imgs).on('load',function(){
                countImg += 1;
                _this.loadngBgPage(this);
                if(countImg == lengImg){
                    _this.effectLoadedFirst();
                }
            });
        }else{
            _this.effectLoadedFirst();
        }
    },
    effectLoadedFirst: function(){
        var _this = this;
        $('.loadingFirst').fadeOut(200, function(){
            $('body').css('overflow','inherit');

            $(this).remove();

            _this.addItem();

            $('.page_parallax,.list_menu, .page_1').css({
                visibility: 'visible'
            }).hide().fadeIn();

            var hrefFirst = window.location.hash.substring(1),
                numAddScroll = hrefFirst == 'about' ? 0 : 70;

            if($('#'+hrefFirst).length > 0){
                $('#'+hrefFirst).pdialog({
                    open: function(){
                        objCommon.outSiteDialogCommon(this);
                    },
                    dialogClass: 'closeCommon',
                    width: 976,
                    height: 800
                });
            }else{
                var itemScroll = $('#anchor-'+hrefFirst),
                    posiTopItemScroll = itemScroll.offset().top;
                setTimeout(function(){
                    $('html,body').animate({
                            scrollTop: posiTopItemScroll - numAddScroll
                        },{
                            queue: false,
                            easing: 'easeInOutExpo',
                            duration: 1500
                        }
                    );
                },1000);

            }
        });
    },
    getWHPage: function(){
        return {
            width: getWWin - 65,
            height: getHWin - 44
        };
    },
    loadngBgPage: function(el){
        var classEl = $(el).attr('class'),
            srBg = $(el).attr('src');
        $('.bgInsert[data-bg-full="'+classEl+'"]').css({
            'background-image': 'url('+srBg+')'
        });
    },
    imgImport: function(el){
        $(el).each(function(){
            var $this = $(this),
                dataSrc = $this.data('original');
            $this.attr('src',dataSrc);
        });
    },
    loadOurTeam: function(){
        var _this = this;
        $.getJSON(urlInfoTeam, function(json) {
            var items = json.ourTeam,
                lengItem = items.length,
                wrapList = $('.wrapListPer ul'),
                changeLang = '';
            if(lengItem > 0){
                for(var i=0; i<lengItem; i++){
                    if(current_lang == 'vi'){
                        changeLang = items[i].discrip_vi;
                    }else{
                        changeLang = items[i].discrip_en;
                    }
                    var item = $('<li class="wrap_item_img"><div class="img_team"> <a href="#" class="wrap_img"><img data-original="'+items[i].image+'" /></a> <a href="#"> <span class="name_per">'+items[i].name+'</span> <span class="posi_per">'+items[i].position+'</span> </a><div class="wrapIntro">'+changeLang+'</div></div> <div class="show_more_infor"> <div class="centerBox"> <h4>'+items[i].name+'</h4> <p><b>'+items[i].position+'</b></p> <div class="line_bottom"></div><div class="wrap_inforEach">'+changeLang+'</div></div> </div> </li>');
                    wrapList.append(item);
                }
                objCommon.sprScroll('.wrap_inforEach');
            }
        });
    },
    addItem: function(){
        var _this = this;
        $('.page_bgFull').each(function(){
            $(this).css({
                width: getWWin,
                height: getHWin,
                position: 'relative'
            });
        });
        /*var hItemPercent = _this.hPercentWidth('.list_gallery_about > ul > li', 370, 230);
        $('.list_gallery_about > ul > li').css({
            height: hItemPercent
        });*/
        $('.heightDemo').css('height', $('.wrapScrollEffect').outerHeight());

        $('.wrap_slideshow').bxSlider({
            pager: false,
            slideWidth: 970
        });

        posiTopFirstMenu = $('.list_menu').position().top;

    },
    centerBoxListItem: function(items, parentCenter, marginLeft){
        var item = $(items),
            wItem = item.outerWidth(),
            colShow = Math.floor(getWWin/(wItem + marginLeft));
        $(parentCenter).css({
            width: colShow*(wItem+marginLeft) - marginLeft
        });
    },
    loadAppendShow: function(item, itemShow, numAdd, marginItem, boxCenter){
        var posiItem = $(item).position().top + numAdd,
            hItem = $(item).outerHeight(),
            posiShow = posiItem - getHWin,
            hItemShow = $(itemShow).outerHeight(),
            flag = true;
        if($(itemShow).css("visibility") == 'visible' || posiItem >= getHWin)
            return;

        if(Math.abs($(item).position().top) >= hItem){
            flag = false;
        }
        if(posiShow <= 0 && Math.abs(posiShow) >= hItemShow && flag && $(itemShow).css("visibility") == 'hidden'){
            this.imgImport($(itemShow).find('.wrap_img img'));
            if(boxCenter)
                this.centerBoxListItem(itemShow,$(item).find('.parentCenter'),marginItem);

            $(item).find('.wrap_img img').load(function(){
                $(this).closest('.wrap_item_img').css({
                    visibility: 'visible'
                }).addClass('active');
            }).error(function(){
                    $(this).closest('.wrap_item_img').css({
                        visibility: 'visible'
                    }).addClass('active');
                });
        }
    },
    hPercentWidth: function(item, wRealImg, hRealImg){
        var wItem = $(item).outerWidth(),
            hItem = (hRealImg*wItem)/wRealImg;
        return hItem;
    }
};

function l(x){
    console.log(x);
}

function Move(animate, from){
    var wPos = $(window).scrollTop(); //position of the scrollbar
    targetPosition = -wPos;

    if(animate){
        if(scrollAnimId == 0){
            scrollAnimId = setInterval("updateScroll()", 33);
        }
    }else{
        divTop = targetPosition;
        $fixed_content_inner.css("margin-top", divTop);
        updateParallaxPos();
    }
}

var scrollAnimId = 0;
var targetPosition = 0;
var divTop = 0;
var $fixed_content_inner;
var $contentBlocks;
$fixed_content_inner = $(".wrapScrollEffect");
$contentBlocks = $(".page_intro");
var flagFixed = false;

function updateScroll(){
    var delta = (targetPosition-divTop)/5;
    divTop = divTop + delta;
    if(Math.abs(delta) < 0.05){
        clearInterval(scrollAnimId);
        scrollAnimId = 0;
        divTop = targetPosition;

    }
    parallaxPlun.loadAppendShow('.page_2','.wrapListPer ul li', 130, 20, true);
    parallaxPlun.loadAppendShow('.page_3','.list_gallery_about > ul > li', 111, 0, false);
    $fixed_content_inner.css("margin-top", divTop);
    updateParallaxPos();
}

var setHashDelayId;
function updateParallaxPos(){
    $contentBlocks.each(function(index){
        var thisH = $(this).height();
        var thisAbsPos = $(this).position().top;
        var thisRelPos = $(this).offset().top;
        var wImgBg = $('.imgIntro').outerWidth();
        var hImgBg = $('.imgIntro').outerHeight();
        var ratioImg = hImgBg/wImgBg;
        var ratioWin = getHWin/getWWin;
        var thisRatio = -(thisAbsPos - getHWin)/(thisH + getWWin);

        if(thisRatio >= 0 && thisRatio <= 1){
            $(this).css("background-position", "50% " + (90 - thisRatio*400) + "px");
        }
    });
    $('.page_parallax').each(function(index){
        var thisAbsPos = $(this).position().top;
        var thisRelPos = $(this).offset().top;
        var thisH = $(this).height();
        var x = $(this).find('.parallax-anchor'),
            hid = getId(x.attr('id')),
            numTopPage = hid == 'about' ? 44 : 114;

        if((-divTop + getHWin - numTopPage) > thisRelPos && (-divTop + getHWin - numTopPage) < thisRelPos + thisH){
            if(index != selectedMenuIndex){
                selectedMenuIndex = index;
                clearTimeout(setHashDelayId);
                $(".list_menu li a").removeClass("active");
                $('.list_menu li a[href="#'+hid+'"]').addClass('active');
                setHashDelayId = setTimeout(function(){
                    setHash(hid);
                }, 200);
            }
        }
    });

    var posiTopMenu = $('.list_menu').position().top,
        posiPage1 = $('.page_1').position().top;

    if(posiTopMenu <= 0 && !($('.list_menu').hasClass('pageFixed'))){
        $('.list_menu').addClass('pageFixed');
        $('.page_1 .parallax-anchor').css({
            'padding-bottom': 70
        });
    }
    if(posiPage1 >= 0 && $('.list_menu').hasClass('pageFixed')){
        $('.list_menu').removeClass('pageFixed');
        $('.page_1 .parallax-anchor').css({
            'padding-bottom': 0
        });
    }
}
function getId(string_id){
    return string_id.split("-").pop();
}
function getHash(){
    return window.location.hash.substring(1);
}
var setHashDelayId;
function setHash(value){
    if(value == getHash()) return;
    var $el = $("#"+value).attr("id", "temp");
    if(history.pushState) {
        history.pushState(null, null, '#'+value);
    }
    else {
        location.hash = '#'+value;
    }
    $el.attr("id", value);
}

var selectedMenuIndex = 0;
window.onload = function(){//window loaded

    setTimeout(function(){
        parallaxPlun.loadingPage();
    },100);





    var timeOutShow;
    $(document).on({
        mouseenter: function () {
            var _this = $(this);
            timeOutShow = setTimeout(function(){
                _this.find('.show_more_infor').animate({
                    left: 0
                },{
                    queue: false,
                    easing: 'easeInOutExpo',
                    duration: 300
                });
            },600);
        },
        mouseleave: function () {
            var _this = $(this);
            _this.find('.show_more_infor').animate({
                left: -268
            },{
                queue: false,
                easing: 'easeInOutExpo',
                duration: 300
            });
            clearTimeout(timeOutShow);
        }
    }, '.wrapListPer ul li');

    $(".list_menu li a.scroll_page, .scroll_next").click(function(e){
        e.preventDefault();

        var path = $(this).attr("href").substr(1),
            parallaxAnchor = $("#anchor-"+path),
            numTopPage = 70,
            scrollTop;

        if(path == 'about' && $('.list_menu').hasClass('pageFixed')){
            scrollTop = posiTopFirstMenu;
        }else{
            scrollTop = parallaxAnchor.offset().top - numTopPage;
        }
        $('html,body').animate({
                scrollTop: scrollTop
            },{
                queue: false,
                easing: 'easeInOutExpo',
                duration: 1500
            }
        );
    });

    $(".groupGallery").colorbox({
        rel: "groupGallery",
        scrolling: false,
        fixed: true,
        onClosed: function() {
            var indexItem = $.colorbox.element().parent().index(),
                srcCurrent = $.colorbox.element().attr('href');

            $.colorbox.element().closest('.gallerySlideShow').find('.wrap_img').attr('href',srcCurrent);
        },
        innerHeight: true,
        scalePhotos: true,
        maxWidth: '100%',
        maxHeight: '95%'
    });
    $('.list_gallery_about ul li.video_gallery a').colorbox({
        iframe:true,
        innerWidth:700,
        innerHeight:500,
        scrolling: false,
        fixed: true,
        maxWidth: '100%',
        maxHeight: '95%'
    });

    $('.privacy_popup').click(function(){
        $('#popup_privacy').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            dialogClass: 'closeCommon',
            width: 976,
            height: 800
        });
        return false;
    });
    objCommon.scrollBar("#popup_privacy .wrap_content_scroll");
    $('.agreenment_popup').click(function(){
        $('#popup_Users_Agreement').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            dialogClass: 'closeCommon',
            width: 976,
            height: 800
        });
        return false;
    });
    objCommon.scrollBar("#popup_Users_Agreement .wrap_content_scroll");
    $('.community_popup').click(function(){
        $('#popup_community').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
            dialogClass: 'closeCommon',
            width: 976,
            height: 800
        });
        return false;
    });
    objCommon.scrollBar("#popup_community .wrap_content_scroll");

    $(document).on('click','.wrap_slideshow ul li a', function(){
    	var url = $(this).attr('data-url');
        objCommon.loading();
        $.ajax({
            type: 'GET',
            url: url,
            success: function(res){
                objCommon.unloading();
                $('#popup_news').html('');
                $('#popup_news').append(res);
                $('#popup_news').pdialog({
                    open: function(){
                        objCommon.outSiteDialogCommon(this);
                    },
                    dialogClass: 'closeCommon',
                    width: 976,
                    height: 800
                });
                objCommon.scrollBar("#popup_news .wrap_content_scroll");
            },
            dataType: 'html'
        });

        return false;
    });
}

$(window).on('scroll',function(){
    Move(true, "scroll");
});
$(window).on('resize',function(){
    getWWin = $(window).outerWidth();
    getHWin = $(window).outerHeight();

    parallaxPlun.addItem();
    parallaxPlun.centerBoxListItem();
});