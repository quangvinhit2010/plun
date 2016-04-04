var objTable42 = {
    loadBoxPerson: function(option){
        var settings = {
            wBoxReal: 165,
            hBoxReal: 194,
            boxParent: $('.list_request_page .wrap_user_req'),
            wrapBox: $('.wrap_user_req ul'),
            marginLeftBox: 20,
            marginBottomBox: 20
        };

        settings = $.extend(settings,  option);

        var wBoxParent = settings.boxParent.outerWidth(),
            numCollunmWidth = Math.floor(wBoxParent/(settings.wBoxReal + settings.marginLeftBox)),
            hWrapBox = settings.wrapBox.outerHeight(),
            numRowHeight = Math.floor(hWrapBox/(settings.hBoxReal)),
            wWrapBox = numCollunmWidth*(settings.wBoxReal + settings.marginLeftBox);

        settings.wrapBox.css({
            width: wWrapBox + 5,
            margin: '0px auto 0px'
        });

        return numCollunmWidth*numRowHeight;
    },
    loadingInside: function(item){
        var loading = $('<span class="loadingInside"></span>');

        $(item).css('position','relative');
        loading.css({
            position: 'absolute',
            width: '100%',
            height: '100%',
            top: 0,
            left: 0,
            background: 'url(/themes/table42/resources/html/css/images/bx_loader.gif) no-repeat center 40%'
        });

        $(item).append(loading);
    },
    unloadingInside: function(){
        $('body').find('.loadingInside').fadeOut(300, function(){
            $(this).remove();
        });
    },
    loadListEffectOrder: function(listItem){
        var timeLoad = 100,
            _this = this,
            countLoad = 0;

        $(listItem).fadeIn();
        //objTable42.slideShowPerson('.wrap_user_req ul');
    },
    showEffectPopup: function(options,item){
        var settings = {
            effectBasic: false,
            effectBoxImgLoad: false,
            ajaxLoadAppend: false,
            width: 'auto',
            height: 'auto',
            htmlAppend: '',
            popupMulti: false,
            popupDefault: false,
            scrollContent: ''
        };

        var idBox = typeof $(item).data('effect-id') == 'undefined' ? item : $(item).data('effect-id'),
            itemShow = $('#'+idBox),
            wWindow = $(window).outerWidth(),
            hWindow = $(window).outerHeight(),
            wLoading = 200,
            hLoading = 40,
            bgOverflow = $('<div class="bgBoxEffect"></div>'),//background overflow
            offsetLeftItem = typeof $(item).data('effect-id') != 'undefined' ? $(item).offset().left : 0,
            offsetTopItem = typeof $(item).data('effect-id') != 'undefined' ? $(item).offset().top : 0,
            wItem = $(item).outerWidth(),
            hItem = $(item).outerHeight(),
            centerTopItemLoad = offsetTopItem + hItem/2,
            centerLeftItemLoad = offsetLeftItem + wItem/2,
            _this = this,
            timeShow = 300,
            loadEffectFirst = $('<div class="loadFirst"><div class="wrapEffectPopup clearfix"><a href="#" class="close_popupEffect icon_table42"></a></div></div>'),//wrap box effect popup
            flagShow = false;

        settings = $.extend(settings, options);

        loadEffectFirst.css({
            position: 'fixed',
            top: centerTopItemLoad,
            left: centerLeftItemLoad,
            width: 0,
            height: 0,
            opacity:0,
            'z-index': 101
        });

        _this.getCenterBox = function(item){
            var posiTopLeft = {};
            if(settings.htmlAppend != '' || (settings.width > 0 && settings.height > 0)){
                posiTopLeft.leftCenter = (wWindow - settings.width)/ 2;
                posiTopLeft.topCenter = (hWindow - settings.height)/ 2;
            }else{
                posiTopLeft.leftCenter = (wWindow - $(item).outerWidth())/ 2;
                posiTopLeft.topCenter = (hWindow - $(item).outerHeight())/ 2;
            }
            return posiTopLeft;
        }

        _this.openEffect = function(itemPopup){
            if(settings.popupMulti){
                var zIndexBg = parseInt($('.loadFirst').css('z-index')),
                    zIndexNew = zIndexBg + 1;
                var posiBox = _this.getCenterBox(itemPopup);
                $('.wrapEffectPopup .popup_general').hide();
                $('.wrapEffectPopup .popup_general').prependTo('body');
                $('.loadFirst').css({
                    top: posiBox.topCenter,
                    left: posiBox.leftCenter,
                    width: settings.width,
                    height: settings.height
                });
                $('.loadFirst .wrapEffectPopup').append(itemPopup);
                itemPopup.fadeIn(250);
            }else{
                $('body').append(loadEffectFirst);
                loadEffectFirst.find('.wrapEffectPopup').append($(itemPopup));
                if(!settings.effectBoxImgLoad){
                    setTimeout(function(){
                        itemShow.fadeIn();
                    },timeShow + 20);
                }

                $('body').css({
                    overflow: 'hidden'
                }).append(bgOverflow);

                setTimeout(function(){
                    bgOverflow.fadeIn(100);
                },timeShow+20);
            }
            _this.closeEffect();
        }

        _this.openEffectTxt = function(itemPopup){
            var posiBox = _this.getCenterBox(itemPopup);
            loadEffectFirst.animate({
                top: posiBox.topCenter,
                left: posiBox.leftCenter,
                width: settings.width,
                height: settings.height,
                opacity: 1
            },timeShow);
            _this.openEffect(itemPopup);
        }

        _this.openEffectBoxImgClone = function(){
            var wBoxImg = $(item).outerWidth(),
                hBoxImg = $(item).outerHeight(),
                $cloneItem = $(item).clone();
            $cloneItem.addClass('itemClone').css({
                width: wBoxImg,
                height: hBoxImg,
                position: 'relative'
            });
            loadEffectFirst.addClass('resetCss');
            loadEffectFirst.css({
                top: offsetTopItem,
                left: offsetLeftItem,
                width: wBoxImg,
                height: hBoxImg,
                opacity: 1
            });
            var posiBox = _this.getCenterBox($(item));
            _this.openEffect($cloneItem);
            objTable42.loadingInside(loadEffectFirst.find('.wrapEffectPopup'));
            loadEffectFirst.animate({
                top: posiBox.topCenter,
                left: posiBox.leftCenter,
                opacity: 1
            },timeShow);
        }

        _this.closeEffect = function(){
            $(document).on('click','.close_popupEffect, .bgBoxEffect', function(e){
                loadEffectFirst.removeClass('effectShowAjaxBox effectBeforeShow');

                loadEffectFirst.animate({
                    top: centerTopItemLoad,
                    left: centerLeftItemLoad,
                    width: 0,
                    height: 0,
                    opacity: 0
                },timeShow);

                $('body').css({
                    overflow: 'inherit'
                });

                setTimeout(function(){
                    itemShow.hide();
                    bgOverflow.fadeOut(100, function(){
                        $(this).remove();
                        itemShow.prependTo('body');
                        $('.loadFirst').remove();
                        if(settings.htmlAppend != ''){
                            itemShow.html('');
                        }
                    });
                },timeShow+50);
            });
        }

        if(settings.htmlAppend != ''){
            itemShow.append(settings.htmlAppend);
            var lImg = $(settings.htmlAppend).find('img').length,count = 0;
            if(lImg > 0){
                $(settings.htmlAppend).find('img').load(function(){
                    count += 1;
                    if(count == lImg){
                        $('.loadFirst').addClass('effectBeforeShow',function(){
                            var $this = $(this),
                                posiBox = _this.getCenterBox();
                            setTimeout(function(){
                                $('.itemClone').remove();
                                $this.removeClass('resetCss');
                                $('.loadFirst').css({
                                    width: settings.width,
                                    height: settings.height,
                                    top: posiBox.topCenter,
                                    left: posiBox.leftCenter
                                });
                                $('.loadFirst .wrapEffectPopup').append(itemShow);
                                $('.loadFirst').addClass('effectShowAjaxBox');
                                itemShow.fadeIn();
                                _this.closeEffect();
                            },timeShow+200);
                        });
                    }
                });
            }else{
                alert('No Image');
            }
            _this.closeEffect();
        }

        if(settings.scrollContent)
            objCommon.scrollBar(settings.scrollContent);

        if(settings.effectBoxImgLoad){
            _this.openEffectBoxImgClone();
        }
        if(settings.effectBasic){
            _this.openEffectTxt(itemShow);
        }
        if(settings.popupMulti){
            _this.openEffect(itemShow);
        }
        if(settings.popupDefault){
            itemShow.pdialog({
               open: function(){
                   objCommon.outSiteDialogCommon(this);
               },
               width: settings.width,
               height: settings.height
            });
        }
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
                leftToolTip = posiOffsetLeft + wItem + 5;
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
    slideShowPerson: function(listSlides, options){
        var settings = {
            ajaxLoad: false
        };
        settings = $.extend(settings, options);
        var _this = this,
            actionSlides = {
            nextSlide: function(){},
            backSlide: function(){},
            ajaxLoadAppend: function(){},
            initSlide: function(){},
            showSlide: function(){}
        };

        actionSlides.showSlide = function(index){
            $('.wrap_user_req ul').removeClass('activeShow').hide();
            $('.wrap_user_req ul').eq(index).addClass('activeShow').fadeIn(1000);
        }

        actionSlides.initSlide = function(){
            var l = $(listSlides).length;
            if(l == 1){
                $('.icon_next_list').fadeIn();
                $('.wrap_user_req ul').attr('data-index',0).addClass('activeShow');
            }

        }

        actionSlides.nextSlide = function(){
            var flag;
            $(listSlides).each(function(){
                var $this = $(this),
                    l = $(listSlides).length,
                    dataIndex;
                if($this.hasClass('activeShow')){
                    dataIndex = $this.data('index');
                    if(l - dataIndex - 1 > 0){
                        actionSlides.showSlide(dataIndex+1);
                        $('.icon_back_list').show();
                        flag =  true;
                    }else{
                        flag =  false;
                    }
                    return false;
                }
            });
            return flag;
        }

        actionSlides.backSlide = function(){
            $(document).on('click','.icon_back_list',function(){
                var thisButton = $(this);

                $(listSlides).each(function(){
                    var $this = $(this),
                        indexShow,
                        lengthSlide = $(listSlides).length;

                    if(lengthSlide == 0 || indexShow < 0) return;

                    if($this.hasClass('activeShow')){
                        indexShow = $this.data('index') - 1;
                        actionSlides.showSlide(indexShow);
                        if(indexShow == 0){
                            thisButton.hide();
                        }
                        return false;
                    }
                });
                return false;
            });
        }

        actionSlides.ajaxLoadAppend = function(){
            var lengthItem = $(listSlides).length;

            actionSlides.showSlide(lengthItem-1);

            $('.wrap_user_req ul').eq(lengthItem-1).attr('data-index',lengthItem-1);
            if(lengthItem > 1){
                $('.icon_back_list').show();
            }
        }

        /*if(settings.ajaxLoad){
            actionSlides.ajaxLoadAppend();
        }*/

        return actionSlides;
    }
};

window.onload = function(){
    //click chon image upload tu public photo
    $(document).on('click','#popup_updatePublic .wrapImgUpload ul li a.wrap_img',function(e){
        e.preventDefault();
        var _this = $(this);
        _this.addClass('selectedImgUpload');
    });
    $(document).on('click','#popup_updatePublic .wrapImgUpload ul li a.wrap_img.selectedImgUpload span',function(e){
        e.stopPropagation();
        var _this = $(this),
            wrapParent = _this.closest('.wrap_img');
        wrapParent.removeClass('selectedImgUpload');
    });

    //click show list user slideshow
    /*$(document).on('click','.icon_next_list',function(){

        var objLoadSlide = objTable42.slideShowPerson('.wrap_user_req ul'),
            flagShow = objLoadSlide.nextSlide();

        if(!flagShow){
            objTable42.loadingInside('.wrap_img_person');
            $('.wrap_user_req ul').hide();
            $.ajax({
                type: 'GET',
                url: 'https://dl.dropboxusercontent.com/u/43486987/imgTable42.html',
                success: function(res){
                    objTable42.unloadingInside();
                    var $res = $(res);
                    $('.wrap_user_req .wrap_img_person').append($res);
                    objLoadSlide.ajaxLoadAppend();
                },
                dataType: 'html'
            });
        }

        return false;
    });*/


}