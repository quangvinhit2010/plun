var cPreloaderTimeout = false;
$(document).ready(function(){
    objCommon.lazyLoadCommon('.list_explore .list_user ul li .wrap-img a img.lazy');
    if($('.page_profiles').length > 0){
        objCommon.lazyLoadCommon('img.lazy');
    }
    objCommon.loadvirtualformSettings();
    objCommon.hw_common();
//	objCommon.updateActivity();
    objCommon.list_event();
    objCommon.coming_soon();
    objCommon.shadowTop('.shadow_top');

    $(document).on('click','#blog_slide .btn_slide',function(){
        var _this = $(this),
            boxShow = _this.closest('#blog_slide');
        if(_this.hasClass('active_open')){
            boxShow.animate({
                right: -375
            },300);
            _this.removeClass('active_open');
        }else{
            boxShow.animate({
                right: 0
            },300);
            _this.addClass('active_open');
        }
        return false;
    });

    $('.tabs ul li').each(function(){
        if($(this).hasClass('active')){
            $($(this).find('a').attr('rel')).show();
        }
    });
    $('.tabs ul li a').on('click',function(){
        if($(this).parent('li').hasClass('active')) return;
        else{
            $('.tabs ul li').removeClass('active');
            $('.tabsCommon').hide();
            $(this).parent().addClass('active');
            $($(this).attr('rel')).fadeIn();
        }
        return false;
    });

    $('.main_menu ul li.main_menu_location a').click(function(){
        $('#popup_checkIn').pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
                objCommon.no_title(this);
            },
            width: 330
        });

    });

    $('.footer_checkIn div.left input').on('focus blur',function(e){
        if(e.type == 'focus'){
            $(this).parent().addClass('focusLocalInput');
        }else{
            $(this).parent().removeClass('focusLocalInput');
        }
    });

    $(document).on('keyup','.footer_checkIn div.left input',function(e){
        if($(this).val() != ''){
            $('.footer_checkIn div.left span.icon_dle').show();
        }else{
            $('.footer_checkIn div.left span.icon_dle').hide();
        }
        $.ajax({
            type: 'POST',
            url: '/venues/Suggest?q='+$(this).val()+'',
            dataType: 'html',
            success: function(res){
                if($(res).length > 0){
                    $('.list_suggers_location ul').html('');
                    $('.list_suggers_location ul').append(res);
                    if($(res).length >= 5){
                        objCommon.sprScroll('.list_suggers_location');
                    }else{
                        $('.list_suggers_location').css('height','auto');
                    }
                    $('.list_suggers_location').fadeIn();
                }else{
                    $('.list_suggers_location').hide();
                    $('.list_suggers_location ul').html('');
                }
            }
        });
    });

    $(document).on('click','.list_suggers_location ul li', function(){
        $('.footer_checkIn div.left input').val('');
        var txtLocationName = $(this).find('p span').text(),
            valID = $(this).find('p span').data('id'),
            valTxt = $(this).find('p span').data('text');
        $('#suggest_id_venue').val(valID);
        $('.footer_checkIn div.left input').val(txtLocationName);
        $(this).closest('.list_suggers_location').hide();
    });

    $(document).on('click','.footer_checkIn div.left span.icon_dle',function(){
        var _this = $(this);
        $('.footer_checkIn div.left input').val('');
        $('.list_suggers_location').hide();
        $('.list_suggers_location ul').html('');
        setTimeout(function(){
            _this.hide();
        },100);
    });
});
$(window).resize(function(){
    objCommon.hw_common();
});

var objCommon = {
    popupSearchSuggestUser: function(options){
        $('body').css('overflow','hidden');
        var settings = {
            item: '',
            width: 'auto',
            height: 'auto',
            bgOpacity: $('<div class="bgPopupSearchSuggest"></div>')
        };
        settings = $.extend(settings,  options);

        settings.bgOpacity.css({
            position: 'fixed',
            height: '100%',
            width: '100%',
            top: 0,
            left: 0,
            background: 'rgba(0, 0, 0, .4)',
            'z-index': 100
        });
        $('body').append(settings.bgOpacity);

        var cHBox = ($(window).outerHeight() - settings.height)/ 2,
            cWBox = ($(window).outerWidth() - settings.width)/ 2;

        $(settings.item).css({
            top: cHBox,
            left: cWBox,
            width: settings.width,
            height: settings.height
        });

        $(settings.item).fadeIn();

        $('.wrapPopupSuggestSearch .closePopupSuggest, .bgPopupSearchSuggest').on('click',function(){
            $(settings.item).hide();
            settings.bgOpacity.hide();
            $('body').css('overflow','inherit');
        });
    },
    lazyLoadCommon: function(item){
        if($(item).length > 0){
            $(item).parent().addClass('addIconLoading');
            $(item).lazyload({
                effect : "fadeIn"
            });
        }

    },
    coming_soon: function(){
        $('.coming-soon').click(function(){
            Util.popAlertSuccess('Coming Soon', 400);
            setTimeout(function () {
                $( ".pop-mess-succ" ).pdialog('close');
            }, 2000);
        });
    },
    rISU: function(){
        var wBox = 210,
            marBox = 15,
            wWrap = $('#isu_block').css('width','auto').outerWidth(),
            numCol = Math.floor(wWrap/(wBox+marBox));

        $('#isu_block').css({
            width: numCol*(wBox+marBox)+'px'
        });

        objCommon.getImgSrc('#isu_block');

        $( ".opener_isu" ).on('click',function() {
            objCommon.loading();
            $.ajax({
                type: 'GET',
                url: 'https://dl.dropboxusercontent.com/u/43486987/isu_detail.html',
                success: function(res){
                    $( ".popup_isu_detail" ).html(res);
                    if($('.pics_isu img').length > 0){
                        $('.pics_isu img').load(function(){
                            $( ".popup_isu_detail" ).pdialog({
                                open: function(){
                                    objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
                                    objCommon.outSiteDialogCommon(this);

                                    $(document).on('click','#opener_forward',function() {
                                        $( ".popup_isu_forward" ).pdialog({
                                            open: function(){
                                                objCommon.outSiteDialogCommon(this);
                                            },
                                            width: 600,
                                            height: 280,
                                            buttons: {
                                                Cancel: function() {
                                                    $( this ).pdialog( "close" );
                                                },
                                                Send: function() {
                                                    $( this ).pdialog( "close" );
                                                }
                                            }
                                        });
                                        objCommon.no_title(this);

                                        return false;
                                    });
                                },
                                width: 700
                            });
                            objCommon.no_title(this);
                        });
                    }else{
                        $( ".popup_isu_detail" ).pdialog({
                            open: function(){
                                objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
                                objCommon.outSiteDialogCommon(this);
                            },
                            width: 700
                        });
                        objCommon.no_title(this);
                    }
                    objCommon.unloading();
                }
            });

            return false;
        });

    },
    rHotBox: function(){
        var marBox = 15,
            wWrap = $('.list_hotbox').outerWidth(),
            $childBox = $('#hotbox_block').find('>li'), wBox = 0, numCol = 0;

        $childBox.each(function(i){
            var _this = $(this);
            if(wBox >= wWrap){
                wBox -= _this.outerWidth() + marBox
                return false;
            }
            wBox += _this.outerWidth() + marBox;
        });

        objCommon.getImgSrc('#hotbox_block');

        $('#hotbox_block').css('width',wBox+'px');

        $( ".popup_detail_hotbox" ).exists(function(){
            $(".popup_isu_forward").pdialog({
                width: 600,
                height: 280
            });
        });
    },
    insertHotBox_ISU: function(res,id){
        var el = $(res);
        $(id).append(el).masonry( 'appended', el, true );
        objCommon.getImgSrc(id);
    },
    sortBox: function(HWRealBoxFirst, numCol, numRow){
        var wWindow = $(window).outerWidth(),
            hWindow = $(window).outerHeight(),
            wColLogin = 0.25*wWindow,
            wColImgRight = 0.75*wWindow,
            totalWItem = numCol*HWRealBoxFirst,
            totalHItem = numRow*HWRealBoxFirst,
            wRestColRight = wColImgRight - totalWItem,
            hRestColRight = hWindow - totalHItem,
            wAdd = wRestColRight/numCol,
            hAdd = hRestColRight/numRow;

        HWRealBoxFirst += parseInt(hAdd, 10);

        var leftBox = 0, numLast = Math.floor((wWindow - wColLogin)/HWRealBoxFirst), wLast = HWRealBoxFirst*numLast;
        leftBox = wWindow - wLast;

        $('.home_hot_pics').css({
            width: (wWindow - wColLogin)+'px',
            overflow: 'hidden',
            left: leftBox+'px'
        });
        $('.home_login').css({
            width: leftBox+'px'
        });
        $('.home_hot_pics ul li').each(function(i){
            var _this = $(this),
                colValue = _this.data('col'),
                rowValue = _this.data('row');
            _this.css({
                height: rowValue*HWRealBoxFirst,
                width: colValue*HWRealBoxFirst
            });
        });

        objCommon.getImgSrc('#masonry_box');

        $("#masonry_box").isotope({
            layoutMode: 'masonry',
            masonry: {
                columnWidth: HWRealBoxFirst
            },
            itemSelector: '.item'
        });
    },
    hw_home: function(){
        var wW = $(window).outerWidth(),
            hW = $(window).outerHeight(),
            hLogo = $('.logo_homepage').outerHeight(),
            hFooter = $('.footer_homepage').outerHeight(),
            wLogin = 0.25*wW,
            wPicRight = 0.75*wW;
        $(".form_login_homepage").css("height",(hW - hLogo - hFooter)+'px');
        var HWRealBoxFirst = 245, numCol = 6,numRow = 4;

        if($('.hero-masonry').length > 0){
            objCommon.sortBox(HWRealBoxFirst, numCol, numRow);
        }
    },
    blur_image: function(){
        $('.gaussian_blur .blurImage').css({opacity: 0});
        if ($('.gaussian_blur').length > 0)
        {
            $('.gaussian_blur').gaussianBlur({
                deviation: 3,
                imageClass: 'blurImage'
            });
        }
    },
    hw_common: function(){
        objCommon.checkMenuBar();
        $(".wrapper_container").exists(function(){
            var wMainMenu = $(".wrapper_main_menu").is(':visible') ? $(".wrapper_main_menu").outerWidth() : 0;
            //$(".wrapper_container").css("width", $(window).width() - wMainMenu);
        });

        /*$(".popup_general").exists(function(){
         $(".popup_general").css("height",$(window).outerHeight() / 2);
         $(".popup_general").css("width",$(window).outerWidth() / 2);
         $(".popup_general").css("max-height",$(window).outerHeight() - 0);
         $(".popup_general").css("max-width",$(window).outerWidth() - 0);
         });*/


        $(".explore, .list_explore").exists(function(){
            var wContainer = $('.container').width(),
                wFeed = $('.wrap-feed').outerWidth(),
                wEx = wContainer - wFeed,
                wBanner = $('.banner_home').length > 0 ? $('.banner_home').outerWidth() : 0;
        });

        $(".message_send .content ul li .info, .textarea_wrap textarea").exists(function(){
            //$(".message_send .content ul li .info").css("width",$(".list_explore").width() - 50);
            $(".textarea_wrap textarea").css("width",$(".list_explore").width() - 20);
        });
    },
    list_event: function(){
        //objCommon.showHideBox('.main_menu ul li.main_menu_location > a','.check_in');

        $('.feed .post_status .input-wrap textarea').exists(function(){
            $(".status").limit({
                limit: 500,
                id_result: "chars"
            });
        });
        $('.btn-wrap-cancel button').click(function(){
            $('.feed .post_status').hide().removeClass('activeShowBox');
        });

        $('.but_search_hotbox').on('focus',function(e){
            $(this).parents('.container_search').toggleClass('slideTrue');
            $('.txt_search_hotbox').focus();
        });
        $('.txt_search_hotbox').on('blur',function(e){
            $(this).parents('.container_search').toggleClass('slideTrue');
        });

        objCommon.showBoxComment();

        $('.home_hot_pics ul li').hover(function(){
            var _this = $(this),
                $img = _this.find('img'),
                $bg = _this.find('span');

            if(!($img.hasClass('hover_img'))){
                $img.addClass('hover_img')
            }
            if(!($bg.hasClass('hover_bg'))){
                $bg.addClass('hover_bg');
            }
        },function(){
            var _this = $(this),
                $img = _this.find('img'),
                $bg = _this.find('span');

            if($img.hasClass('hover_img')){
                $img.removeClass('hover_img')
            }
            if($bg.hasClass('hover_bg')){
                $bg.removeClass('hover_bg');
            }
        });

        $(document.body).on('mouseover',".list_explore .list_user ul li",function(e){
            e.preventDefault();
            $(".list_explore .list_user ul li .info").removeClass('show_info');
            var _this = $(this);
            _this.find(".info").addClass('show_info');
            $(this).find(".icons_status").show();
        }).on('mouseout',".list_explore .list_user ul li",function(e){
                e.preventDefault();
                $(".list_explore .list_user ul li .info").removeClass('show_info');
                $(this).find(".icons_status").hide();
            });

        objCommon.showHideBox('.feed .content ul li .icon_list_func_feed','.list_func_feed');

        objCommon.showHideBox('.list_explore .online_num a','.find_him_pop');

        $(".btn-close-new").click(function(e){
            $(".find_him_pop").hide();
        });

        objCommon.showHideBox('a.post_link','.post_status');

        objCommon.showHideBox('.search_setting ul li.setting_list','.list_setting');

        // Popup Users Agreement ------------------------
        $( "#opener_agreement" ).click(function() {
            $( ".popup_agreement" ).dialog( "open" );
        });
        $( ".popup_agreement").exists(function(){
            $( ".popup_agreement").dialog({
                open: function(event, ui) {
                    objCommon.no_title(this); // config trong file common.js
                },
                close: function(event, ui) {
                    objCommon.have_title(); // config trong file common.js
                },
                autoOpen: false,
                width: 600,
                height: 460,
                resizable: false,
                modal: true
            });
        });
    },
    updateActivity: function (){
        if(typeof update_activity_time === 'undefined'){
            return false;
        }
        $.ajax({
            type: "POST",
            url: '/site/UpdateLastActivity',
            success: function(data) {

            },
            dataType: 'html'
        });

        setTimeout(function(){
            objCommon.updateActivity();
        }, update_activity_time * 1000)
    },
    vitri_chatbox: function(){
        $(".chat-list").exists(function(){
            $cur_width_chat = $(window).width() - $(".chat-list").width() - 275;
            $(".pos_chat_boxed_1").css('left',$cur_width_chat);
            $(".pos_chat_boxed_2").css('left',$cur_width_chat - 270);
            $(".pos_chat_boxed_3").css('left',$cur_width_chat - 540);
            $(".pos_chat_boxed_4").css('left',$cur_width_chat - 810);
            $(".pos_chat_boxed_5").css('left',$cur_width_chat - 1080);
            $(".pos_chat_boxed_end").css('left',$cur_width_chat - 1350);
        });

    },
    sprChatBox: function(){
        $(".chat-box-area .btn-slide").click(function(){
            $(".chat-list").toggleClass("active");
            $(".chat-list .list").slideToggle();
        });
        $(".chat-box-area .btn-slide-active").click(function(){

        });
        $(".chat-boxed .btn-close").click(function(){
            $(this).closest(".chat-boxed-wrap").remove();
            objCommon.vitri_chatbox();
        });
        $(".chat-boxed .title").click(function(){
            $(this).closest(".chat-boxed-wrap").toggleClass("hide").find(".chat-conversation").slideToggle();
        });
    },
    no_title: function(item){
        $(item).parent().find("#ui-dialog-title-dialog").hide();
        $(item).parent().find(".ui-dialog-titlebar").removeClass('ui-widget-header');
        $(item).parent().find(".ui-dialog-title").css("display","none");
        $(item).parent().find(".ui-dialog-titlebar-close").css("top",14);
        $(item).parent().find(".ui-dialog-titlebar-close").css("z-index",1);
        $(item).parent().find(".ui-dialog-titlebar").css("padding",0);
    },
    have_title: function(item){
        $(item).parent().find("#ui-dialog-title-dialog").show();
        $(item).parent().find(".ui-dialog-titlebar").addClass('ui-widget-header');
        $(item).parent().find(".ui-dialog-title").css("display","table");
        $(item).parent().find(".ui-dialog-titlebar-close").css("top","50%");
        $(item).parent().find(".ui-dialog-titlebar-close").css("z-index","auto");
        $(item).parent().find(".ui-dialog-titlebar").css("padding","0.4em 1em");
    },
    scrollBar: function(item){
        objCommon.sprScroll(item);
    },
    loadFristElement: function(item, callback){
        var getItemsCommonFirst = setInterval(function(){
            if($(item).length > 0){
                callback();
                clearInterval(getItemsCommonFirst);
            }
        },0);
    },
    sprScroll: function(cls){
        $(cls).mCustomScrollbar({
            scrollInertia: 0,
            mouseWheelPixels: "auto",
            autoHideScrollbar: true,
            advanced:{
                updateOnContentResize: true,
                contentTouchScroll: true,
                autoScrollOnFocus:false
            }
        });
    },
    getImgSrc: function(item){
        var _this = $(item),
            $items = _this.find('li a.wrap-img-loading'), msnry;
        $items.each(function(){
            var _this = $(this),
                srcImg = _this.data('srcimg');
            _this.find('img').attr('src',srcImg);
        });
        //objCommon.loading();

        _this.imagesLoaded(function(){
            switch(_this.data('sort')) {
                case 'isu':
                    msnry = _this.masonry({
                        columnWidth: 225,
                        "isFitWidth": true,
                        isAnimated: true,
                        isResizable: true
                    });
                    break;
                case 'hotbox':
                    msnry = _this.masonry({
                        itemSelector: '#'+_this.attr('id')+' >li',
                        columnWidth: 220,
                        isAnimated: true,
                        isResizable: true
                    });
                    break;
                default:
                //_this.unloading();
            }

            _this.find('>li').css('visibility','visible');
        }).progress(onProgress)
            .always( function( instance ) {
                //objCommon.unloading();
            });

        function onProgress( imgLoad, image ) {
            var $item = $( image.img ).parents('.is-loading');
            $item.removeClass('is-loading');
        }
    },
    loading: function(){
        var cTotalFrames = 18;
        var cFrameWidth = 80;
        var cIndex = 0;
        var cXpos = 0;
        if($('.loading #loaderImage').length > 0){
            $('.loading').remove();
        }
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
        var myimage = $('#loaderImage');

        objCommon.changeBackgroundLoading(myimage, cFrameWidth, cTotalFrames, cIndex, cXpos);
    },
    changeBackgroundLoading: function(myimage, cFrameWidth, cTotalFrames, cIndex, cXpos){
        cXpos += cFrameWidth;
        cIndex += 1;
        if (cIndex >= cTotalFrames) {
            cXpos =0;
            cIndex=0;
        }

        myimage.css('background-position', (-cXpos)+'px');

        cPreloaderTimeout = setTimeout( function () {
            objCommon.changeBackgroundLoading(myimage, cFrameWidth, cTotalFrames, cIndex, cXpos);
        }, 50);
    },
    unloading: function(){
        clearTimeout(cPreloaderTimeout);
        $('.loading').fadeOut(500, function(){
            $(this).remove();
        });
    },
    checkMenuBar: function(){
        var checkShowHide = $('.wrapper_main_menu').is(':visible') ? true : false;
        if(!checkShowHide){
            $('.header').css('padding-left','0px');
        }else{
            $('.wrapper_container').exists(function(){$(this).addClass('menuExists')});
        }
    },
    loadvirtualformSettings: function(){
        $(document.body).on('change', '.virtual_form', function() {
            var class_text = $(this).attr('text');
            var value = $(this).find('option:selected').text();
            $('.' + class_text).text(value);
        });
    },
    showBoxComment: function(){
        $(document.body).on('click', '.function li.link_comment a, .feed .content ul li .info .function li.num_comment a', function(e){
            e.preventDefault();
            var _this = $(this);
            var _ul = $(this).closest('ol');

            $('.comment_list').removeClass('showBox').addClass('hideBox');
            if(_this.closest('.item').hasClass('item-active')){
                _this.closest('.item').removeClass('item-active');
                $('.sticky_column').fixed_col_scroll.callbackFun();
                return;
            }else{
                $('.feed .content ul li').removeClass('item-active');
            }

            if(_this.closest('.item').addClass('item-active').find('.comment_list').hasClass('hideBox')){
                _this.closest('.item').addClass('item-active').find('.comment_list').removeClass('hideBox').addClass('showBox');
                _this.closest('.item').addClass('item-active').find('.comment_list .cmt-post-text').focus();
            }else{
                _this.closest('.item').removeClass('item-active').find('.comment_list').removeClass('showBox').addClass('hideBox');
            }
            $('.sticky_column').fixed_col_scroll.callbackFun();
            return false;
        });
    },
    showHideBox: function(itemEvent, item){
        $(document.body).on('click',itemEvent, function(e){
            e.preventDefault();
            var _this = $(this);

            $(item).hide();
            if(_this.parent().find(item).is(':visible')){
                _this.parent().find(item).hide();
                _this.parent().find(item).removeClass('activeShowBox');
            }else{
                _this.parent().find(item).show();
                _this.parent().find(item).addClass('activeShowBox');
            }
            if(_this.parent().find(item).length == 0){
                _this.parents('body').find(item).show();
                _this.parents('body').find(item).addClass('activeShowBox');
            }

            return false;
        });
        $(document.body).on('click',function(){
            if($(item).hasClass('activeShowBox')){
                $(item).hide();
                $(item).removeClass('activeShowBox');
            }
        });
        $(document.body).on('click',item, function(e){
            e.stopPropagation();
        });
    },
    scorllContentPopup: function(itemDialog,titleDialog){
        var hWindow = $(window).outerHeight(),
            hPopup = $(itemDialog).outerHeight(),
            hTitle = $(titleDialog).outerHeight(),
            hScroll;

        if(hPopup > hWindow){
            hPopup = hWindow - 100;
            $(itemDialog).parent().css({
                height: hPopup,
                visibility: 'visible',
                top: 50
            });
        }else{
            $(itemDialog).parent().css({
                visibility: 'visible'
            });
        }
        hScroll = hPopup - hTitle;
        $('.wrap_scroll_popup').css('height',hScroll+'px');
        objCommon.scrollBar('.wrap_scroll_popup');
    },
    outSiteDialogCommon: function(itemDialog){
        $('.ui-widget-overlay').remove();
        $("body").css({ overflow: 'hidden' });
        var bgOverflow = $('<div class="bgoverflow_popup"></div>'),
            itemDialogs = $('.ui-dialog'),
            zIndexDialog = 0,
            zIndexBg = 0;
        if($(itemDialogs).length > 0){
            zIndexDialog = parseInt($(itemDialogs).eq($('.ui-dialog').length - 2).css('z-index')) + 2;
            $(itemDialog).parent().css('z-index', zIndexDialog);
        }
        zIndexBg = zIndexDialog - 1;
        bgOverflow.css({
            position: 'fixed',
            height: '100%',
            width: '100%',
            top: 0,
            left: 0,
            background: 'rgba(0, 0, 0, .4)',
            'z-index': zIndexBg
        });
        if($('.bgoverflow_popup').length > 0){
            $('.bgoverflow_popup').css({
                'z-index': zIndexBg
            });
        }else{
            bgOverflow.insertBefore($(itemDialog).parent());
        }

        $(document.body).on('click','.bgoverflow_popup',function(e){
            e.preventDefault();
            var itemDialogs = $('.ui-dialog'),
                numDialog = itemDialogs.length;

            for(var i = numDialog-1; i >= 0 ; i--){
                if(itemDialogs.eq(i).is(':visible')){
                    itemDialogs.eq(i).find('.ui-dialog-content').pdialog('close');
                    break;
                }
            }
        });
    },
    showGalleryUser: function(){
        $('.photo_video .content ul li a').click(function(){
            $('.gallery_user').fadeIn();
        });
    },
    cPixelToPercent: function(item, wRealItem, paddingItem){
        var objWidthItems = {
                wPercentItemLeft : 0,
                wPercentItemRight : 0
            },
            item = $(item),
            wItem = item.outerWidth(),
            parentItem = $(item).parent(),
            wParentItem = parentItem.width(),
            percentNeighbor = ((wParentItem - wItem)/wParentItem)*100;
        objWidthItems.wPercentItemRight = percentNeighbor;
        objWidthItems.wPercentItemLeft = 100 - objWidthItems.wPercentItemRight;

        l('Width Percent Left: '+ objWidthItems.wPercentItemLeft + ' - Width Percent Right: '+ objWidthItems.wPercentItemRight);
    },
    cMarginPaddingToPercent: function(item, num){
        var wItem = $(item).outerWidth(),
            wMarginPadding = 100 - ((wItem - num)/wItem)*100;

        l('Width Margin or Padding: '+ wMarginPadding);
    },
    shadowTop: function(item){
        $(item).each(function(){
            var offsetItem = $(this).parent().offset().top,
                wShadow = $(this).parent().outerWidth(),
                offsetLeft = $(this).parent().offset().left;
            $(this).css({
                top: offsetItem - 1,
                width: wShadow,
                left: offsetLeft
            });
            $(window).on('scroll',function(e){
                e.preventDefault();
                var valScroll = $(this).scrollTop();
                $(item).addClass('show');
                if(valScroll == 0){
                    $(item).removeClass('show');
                }
            });
        });

    },
    loadingInside: function(item){
        var loading = $('<div class="loading_process"><div class="loadingInside"></div></div>');
        $(item).closest('.loadingItem').append(loading);
        $('.loading_process').fadeIn(400);
    },
    unloadingInside: function(){
        $('body').find('.loading_process').fadeOut(300, function(){
            $(this).remove();
        });
    },
    tooltipPlun: function(options){
        var settings = {
            el: '',
            posiLeft: false,
            posiTop: false,
            posiRight: false,
            posiBottom: false,
            titleTip: ''
        }
        settings = $.extend(settings, options);

        $(document).on('mouseover',settings.el,function(){
            var _this = $(this),
                wItem = _this.outerWidth(),
                hItem = _this.outerHeight(),
                centerHeight = hItem/2,
                centerWidth = wItem/ 2,
                posiOffsetLeft = _this.offset().left,
                posiOffsetTop = _this.offset().top,
                divToolTip = $('<div class="tooltipPlun"><div class="tooltip-arrow"></div><div class="tooltip-inner">'+settings.titleTip+'</div></div>'),
                leftToolTip = 0,
                topToolTip = 0;

            $('body').append(divToolTip);

            if(settings.posiLeft){
                leftToolTip = posiOffsetLeft - divToolTip.outerWidth() - 5;
                topToolTip = posiOffsetTop + (hItem - divToolTip.outerHeight())/2;
                divToolTip.find('.tooltip-arrow').addClass('arrow_left');
            }
            if(settings.posiTop){
                leftToolTip = posiOffsetLeft + (wItem - divToolTip.outerWidth())/2;
                topToolTip = posiOffsetTop - divToolTip.outerHeight() - 5;
                divToolTip.find('.tooltip-arrow').addClass('arrow_top');
            }
            if(settings.posiRight){
                leftToolTip = posiOffsetLeft + wItem + 10;
                topToolTip = posiOffsetTop + (hItem - divToolTip.outerHeight())/2;
                divToolTip.find('.tooltip-arrow').addClass('arrow_right');
            }
            if(settings.posiBottom){
                leftToolTip = posiOffsetLeft + (wItem - divToolTip.outerWidth())/2;
                topToolTip = posiOffsetTop + hItem + 10;
                divToolTip.find('.tooltip-arrow').addClass('arrow_bottom');
            }
            divToolTip.css({
                top: topToolTip,
                left: leftToolTip
            }).fadeIn();
        })
            .on('mouseout',function(){
                $('.tooltipPlun').remove();
            });
    },
    getNewYearBg: function(){
        getTimeNewYear();
        var interTime = setInterval(function(){
            getTimeNewYear();
        },1000);

        function getTimeNewYear(){
            var count = 0,
                currentdate = new Date(),
                monthFirst,
                minuFirst;

            monthFirst = currentdate.getMonth()+1;
            minuFirst = currentdate.getMinutes();

            if(monthFirst == 1 && minuFirst >= 0){
                if($('.wrapNewYearUser').length > 0){
                    var hBanner = $('.wrapNewYearUser').outerHeight();
                    $('.online_num').css({
                        'margin-top': hBanner
                    });

                    setTimeout(function(){
                        $('.wrapNewYearUser').fadeIn();
                        $('.sticky_column').fixed_col_scroll.callbackFun();
                    },1000);
                }

                clearInterval(interTime);
            }
        }
    }
}

$.fn.pdialog = function (options) {
    var _this = this;
    switch (options) {
        case 'close':
            _this.dialog('close');
            break;
        default:
            var settings = $.extend({
                close: function(){
                    if($('.ui-dialog').length > 0 && $('.ui-dialog').is(':visible')){
                        $('.ui-dialog').each(function(i){
                            var _this = $(this);
                            if(_this.is(':visible')){
                                $('.bgoverflow_popup').css({
                                    'z-index': _this.css('z-index') - 1
                                });
                            }
                        });
                    }else{
                        $("body").css({ overflow: 'inherit' });
                        $('.bgoverflow_popup').remove();
                    }
                    settings.pclose();
                },
                pclose: function() {},
                resizable: false,
                position: 'middle',
                draggable: false,
                autoOpen: false,
                center: true,
                modal: true,
                show: {
                    effect: "fade",
                    duration: 500
                },
                hide: {
                    effect: "hide",
                    duration: 300
                }
            }, options);

            this.dialog(settings);
            this.dialog('open');

            break;
    }
}

//check item exists fast
$.fn.exists = function(callback) {
    var args = [].slice.call(arguments, 1);

    if (this.length) {
        callback.call(this, args);
    }

    return this;
}

//console fast
function l(value){
    console.log(value);
}


$.fn.limit  = function(options) {
    var defaults = {
        limit: 200,
        id_result: false,
        alertClass: false,
        btn_reset: '.btn-wrap-cancel button'
    }
    var options = $.extend(defaults,  options);
    return this.each(function() {
        var characters = options.limit;
        if(options.id_result != false){
            //$("#"+options.id_result).append("You have <strong>"+  characters+"</strong> characters remaining");
        }
        $(this).keyup(function(){
            if($(this).val().length > characters){
                $(this).val($(this).val().substr(0, characters));
            }
            if(options.id_result != false){
                var remaining =  characters - $(this).val().length;
                $("#"+options.id_result).html(remaining);
                if(remaining <= 10){
                    $("#"+options.id_result).addClass(options.alertClass);
                }
                else{
                    $("#"+options.id_result).removeClass(options.alertClass);
                }
            }
        });
        $(options.btn_reset).on('click',function(e){
            e.preventDefault();
            $("#"+options.id_result).html(options.limit);
        });
    });
};


(function() {
    /*version scroll fix load ajax 1.0*/
    $.fn.fixed_col_scroll = function(options){
        var win = $(window),
            parentWrap = $('.wrap_scroll'),
            div,
            hItem = 0,
            offsetItem = 65,
            hWin = win.outerHeight(),
            paddingTop = parseInt(parentWrap.css('padding-top')),
            paddingBottom = parseInt(parentWrap.css('padding-bottom')),
            parent_top = parentWrap.offset().top,
            valScrollReload = 0,
            hParent = parentWrap.height(),
            wItem,
            tempHeight = 0,
            arrHeight = [],
            lengThis = $(this).length,
            numTop = 0,
            itemTemp,
            _this = $(this),
            hCheckItem = false,
            statusVal = $('<span id="dvScroll_CurValue"></span>');

        $('#dvScroll_CurValue').remove();
        statusVal.css({
            display: 'none'
        });
        parentWrap.append(statusVal);

        $.fn.fixed_col_scroll.callbackFun = function(){
            calcuScroll(_this);
        };

        function calcuScroll(elem){//check element height min and add wrap column
            div = $('<div class="wrap_col"></div>');
            arrHeight = [];
            valScrollReload = win.scrollTop();

            var countLoop = 0;
            for (var _i = 0; _i < elem.length; _i++) {
                var hTempItem = $(elem[_i]).outerHeight();
                arrHeight.push(hTempItem);
                if(hTempItem <= hWin){
                    countLoop += 1;
                }
                if(countLoop == elem.length){
                    hCheckItem = true;
                }else{
                    hCheckItem = false;
                }
            }

            tempHeight = Math.min.apply(Math,arrHeight);
            hParent = Math.max.apply(Math, arrHeight);

            parentWrap.attr({
                'data-offsettop-parent': parent_top + paddingTop,
                'data-height-parent': hParent
            }).css({
                    position: 'relative'
                });

            for (var _i = 0; _i < elem.length; _i++) {
                var elm = $(elem[_i]);

                wItem = elm.outerWidth();
                hItem = elm.outerHeight();

                elm.attr({
                    'data-height-item': hItem,
                    'data-offset-top': offsetItem
                });

                if(elm.css('position') == 'fixed' || elm.css('position') == 'absolute' || elm.css('position') == 'static'){
                    if((hItem >= hParent && elm.parent().hasClass('wrap_col')) || (elm.parent().hasClass('wrap_col') && hItem == tempHeight)){
                        var divTemp = elm.parent();
                        elm.css({
                            position: 'static',
                            top: 'auto',
                            bottom: 'auto',
                            width: wItem
                        });
                        elm.insertBefore(divTemp);
                        divTemp.remove();
                    }
                }

                if(hItem < hParent){
                    itemTemp = elm;
                    if(hItem == tempHeight || $('.wrap_col').length == 0){
                        div.insertBefore(elm);
                        div.css({
                            height: tempHeight,
                            float: 'left',
                            width: wItem,
                            position: 'static'
                        });
                        div.append(elm);
                    }else{
                        elm.parent().css({
                            height: tempHeight
                        });
                    }
                    elm.css('width','inherit');
                }
            }
            if(typeof itemTemp != 'undefined'){
                scrollColum(itemTemp, valScrollReload);
            }
        }
        function scrollColum(elem, valScroll){
            if(hCheckItem){
                return;
            }else{
                hCheckItem = false;
            }
            if((tempHeight + offsetItem) <= hWin){
                elem.css({
                    position: 'fixed',
                    top: offsetItem,
                    bottom: 'auto'
                });
                return;
            }

            if(tempHeight + offsetItem - hWin - valScroll < 0 && arrHeight.length == lengThis){
                elem.css({
                    position: 'fixed',
                    top: -(tempHeight - hWin),
                    bottom: 'auto'
                });
            }else{
                elem.css({
                    position: 'static',
                    top: 'auto'
                });
                if($('#pop-find-him').length > 0){
                    $('#pop-find-him').removeClass('fixed_child');
                }
            }

            numTop = elem.css('top') == 'auto' ? 0 : parseInt(elem.css('top'), 10);

            if(hParent + parent_top - tempHeight - valScroll - numTop < 0){
                elem.css({
                    position: "absolute",
                    bottom: paddingBottom,
                    top: "auto"
                });
                if($('#pop-find-him').length > 0){
                    $('#pop-find-him').addClass('fixed_child');
                }
            }
        }

        win.on("scroll", function(e){
            var valScroll = win.scrollTop();
            $('#dvScroll_CurValue').attr({
                'data-offset-item': offsetItem,
                'data-win-scoll': valScroll,
                'data-fixed': tempHeight - hWin + offsetItem - valScroll,
                'data-bottom-check': hParent + parent_top - tempHeight - valScroll - numTop
            });
            if(typeof itemTemp != 'undefined'){
                scrollColum(itemTemp, valScroll);
            }

        });

        win.on('resize',function(){
            $('.wrap_col').css('width','auto');
            calcuScroll(_this);
        });

        calcuScroll(_this);

        return this;
    };

    $.fn.boxResizeImg = function(options){
        var _this = $(this),
            win = $(window);

        $.fn.boxResizeImg.settings = {
            parentWrap: '.wrap_scale_box',
            wRealBox: 176,
            hRealBox: 176,
            numMarginLeft: parseInt(_this.css('margin-left')),
            wrapContent: false,
            wrapImg: _this.find('.wrap-img')
        };
        var settings = $.extend({}, $.fn.boxResizeImg.settings, options);

        function calResizeImg(){
            var wParent = $(settings.parentWrap).width(),
                numCol = Math.floor(wParent/(settings.wRealBox + settings.numMarginLeft)),
                wRest = wParent - (settings.wRealBox + settings.numMarginLeft)*numCol,
                wBoxAdd = settings.wRealBox + settings.numMarginLeft - wRest, wRealBoxAdd = 0;

            if((_this.length)*(settings.wRealBox + settings.numMarginLeft) < wParent){
                _this.css({
                    visibility: 'visible',
                    display: 'block'
                });//.fadeIn();
                return;
            }

            wRealBoxAdd = settings.wRealBox - wBoxAdd/(numCol+1) + settings.numMarginLeft/(numCol+1);

            if((wRealBoxAdd+10)*(numCol+1) > wParent){
                wRealBoxAdd = Math.floor(wRealBoxAdd);
            }

            if(settings.wrapContent){
                _this.css({
                    width: wRealBoxAdd+'px'
                });
                settings.wrapImg.css({
                    width: wRealBoxAdd+'px',
                    height: wRealBoxAdd+'px'
                });
            }else{
                _this.css({
                    width: wRealBoxAdd+'px',
                    height: wRealBoxAdd+'px'
                });
            }
            _this.css({
                visibility: 'visible',
                display: 'block'
            });
        }

        calResizeImg();

        win.on('resize',function(){
            calResizeImg();
        });

        return this;
    };
}).call(this);