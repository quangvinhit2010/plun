var Hotbox = {
    isotope: function () {
        var $container = $('.pint-style .list'),
            wW = $(window).outerWidth(),
            wItems = $('.pint-style .list .item').outerWidth(),
            wItemsMargin = wItems + 20,
            numColl = Math.floor(wW/wItemsMargin),
            wParentCenter = numColl*wItemsMargin;
        $(window).resize(function(){
            wW = $(window).outerWidth();
            numColl = Math.floor(wW/wItemsMargin);
            wParentCenter = numColl*wItemsMargin;
            $('.pint-style').css({
                width: (wParentCenter+20)+'px',
                margin: '0px auto 0px'
            });
        });     
        $('.pint-style').css({
            width: (wParentCenter+10)+'px',
            margin: '0px auto 0px'
        });
        $container.isotope({
            itemSelector: '.item',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        }, function(){
            $container.find('.item').animate({
                opacity: 1
            },100);

        });
        var $optionSets = $('#filter ul li'),
            $optionLinks = $optionSets.find('a');


        $optionLinks.click(function () {
            var $this = $(this);
            // don't proceed if already selected
            if ($this.hasClass('active')) {
                return false;
            }
            var $optionSet = $this.parents('#filter');
            $optionSet.find('.active').removeClass('active');
            $this.addClass('active');

            // make option object dynamically, i.e. { filter: '.my-filter-class' }
            var options = {},
                key = $optionSet.attr('data-option-key'),
                value = $this.attr('data-option-value');
            // parse 'false' as false boolean
            value = value === 'false' ? false : value;
            options[ key ] = value;

            $container.isotope(options);


            return false;
        });
    },
    resize_thumbnail: function () {
        /*$('.pint-style .list .item').each(function( index ) {
         var countPhoto = $(this).find('.photo ul li').size();
         //console.log(countPhoto);
         if(countPhoto){
	         switch (countPhoto) {
		         case 1:
			         $(this).find('.photo ul li:eq(0)').addClass('first-item');
			         break;
		         case 2:
			         $(this).find('.photo ul li:eq(0)').addClass('first-item');
			         $(this).find('.photo ul li:eq(1)').addClass('first-item');
		         break;
		         case 3:
			         $(this).find('.photo ul li:eq(0)').addClass('first-item');
			         $(this).find('.photo ul li:eq(1)').addClass('photo-2child');
			         $(this).find('.photo ul li:eq(2)').addClass('photo-2child');
		         break;
		         case 4:
			         $(this).find('.photo ul li:eq(0)').addClass('first-item');
			         $(this).find('.photo ul li:eq(1)').addClass('photo-3child');
			         $(this).find('.photo ul li:eq(2)').addClass('photo-3child');
			         $(this).find('.photo ul li:eq(3)').addClass('photo-3child');
		         break;
	         }
         }
         });*/
    },
    load: function () {
        $("ul li.hotbox-load a.load").live("click", function (e) {
            e.preventDefault();
            $('body').loading();
            var url = $(this).attr('href');
            if (url) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (res) {
                        //$('.main-wrap').fadeOut().remove();
                        //$('.main').html(res);
                        //Hotbox.isotope();
                        //Util.changeUrlAjax(url);
                        var $wrapHTML = $('.popupBoxShow_hotbox_isu'), hWindow = $(window).outerHeight(), hScroll;
                        $wrapHTML.append($(res));
                        var $temp = $wrapHTML.find('.col-feed');
                        $wrapHTML.html('');
                        $wrapHTML.append($temp);
                        $wrapHTML.css({
                            display: 'block',
                            visibility: 'hidden'
                        });
                        $wrapHTML.find('.content .zoom_pics img').load(function(){
                            var hDialog = $wrapHTML.outerHeight();
                            //$('body').append($wrapHTML);
                            if(hDialog >= hWindow){
                                hScroll = hWindow - 40;
                            }else{
                                hScroll = 'auto';
                            }
                            $wrapHTML.css({
                                display: 'none',
                                visibility: 'visible',
                                'max-width': '640px'
                            });
                            $wrapHTML.pdialog({
                                width: 'auto',
                                //height: hWindow - 40,
                                height: hScroll,
                                dialogClass: 'dialog_hotbox',
                                beforeClose: function(){
                                    $('.popupBoxShow_hotbox_isu').html('');
                                    
                                    return false;
                                }
                            });
                            Hotbox.scroll_after_ajax_form();
                            Hotbox.submit_comment();
                        }).each(function(){
                            
                        });
                        $(this).unloading();
                        $('.btn-del-hotbox').live('click',function(){
                            $(".popupBoxShow_hotbox_isu").pdialog("close");
                        });
                    }
                });
            }
        });

    },
    show_more: function () {
        $(this).loading();
        var page = $('#hotboxs').attr('page');
        if (page != 'end') {
            var url = window.location.href.split('#')[0] + '?page=' + page;
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    if (data == 'end') {
                        $('.col-full .more-wrap').hide();
                        //@todo: show popup error
                    } else {
                        //get next_page
                        var next_page = $(data).filter('#next_page').attr('page');
                        if (next_page == 'end') {
                            $('.col-full .more-wrap').hide();
                        } else {
                            $('#hotboxs').attr('page', next_page);
                        }
                        var $data = $(data);
                        $('#hb-list').append('<div id="temp-load"></div>');
                        $('#temp-load').css({
                            position: 'absolute',
                            top: 0,
                            left: 0,
                            display: 'none'
                        }).append($data);
                        $('#temp-load').children().css({opacity: 0});
                        var len_img = $('#temp-load .item .photo').find('img').length,count_img = 0;

                        $('#temp-load .item .photo').find('img').load(function(){
                            count_img += 1;
                        }).error(function(){
                            len_img -= 1;
                            $(this).hide();
                        });
                        var showBox = setInterval(function(){
                            console.log(count_img+ ' num image '+len_img);
                            if(count_img == len_img){
                                $('#hotboxs').isotope('insert', $($('#temp-load').html()), function () {
                                    $('#hotboxs').children().css('opacity', 1);
                                    $('#temp-load').remove();
                                });
                                clearInterval(showBox);
                            }
                        },100);
                    }
                    $(this).unloading();
                },
                dataType: 'html'
            });
        } else {
            $('.col-full .more-wrap').hide();
            $(this).unloading();
        }
    },
    scoll_class: function (cls) {
        $(cls).mCustomScrollbar({
            scrollInertia: "0",
            mouseWheelPixels: "auto",
            autoHideScrollbar: true,
            advanced: {
                updateOnContentResize: true,
                updateOnBrowserResize: true,
                contentTouchScroll: true
            }
        });


    },
    scroll: function () {
        /*$(".main .col-full .pint-style").mCustomScrollbar("destroy");
         this.scoll_class('.main .col-full .pint-style');
         $('.main .col-full .pint-style').mCustomScrollbar('scrollTo', 'bottom');*/
    },
    scroll_after_ajax_form: function () {
        //$(".main .col-right .pint-style").mCustomScrollbar("destroy");
        //this.scoll_class('.main .col-right .pint-style');
        //$('.main .col-right .pint-style').mCustomScrollbar('scrollTo', 'bottom');
        //this.scoll_class('.col-right-hotnew');
        this.scoll_class('.popupBoxShow_hotbox_isu');
        //this.scoll_class('.detail_hotbox_400 .wrap_detail');
    },
    open_form: function (id) {
        $('#' + id).css('width', '788px');
        $('#' + id).show();
        /*$('#' + id ).dialog({
         width: 788,
         open: function(event, id) {
         //do something
         },
         close: function() {
         },

         });*/

    },
    close_form: function (id) {
        $('#' + id).hide();
    },
    submit: function (form, data, hasError) {
        if (!hasError) {
            $(this).loading();
            var item = $("#hotbox-form");
            var data = item.serialize();
            $.ajax({
                type: 'POST',
                url: item.attr('action'),
                data: data,
                success: function (response) {
                    window.location.href = "/hotbox";
                    /*if (response.status != undefined && response.status == true) {
                        $('.main-wrap').remove();
                        $('.main').html(response.after_save);
                        Hotbox.isotope();
                        Util.changeUrlAjax('/hotbox');
                        Hotbox.scroll_after_ajax_form();
                        if (typeof listImg !== "undefined" && listImg.length > 0) {
                            Hotbox.make_slider(listImg);
                        }
                        Hotbox.show_gal_hotbox();
                        Hotbox.submit_comment();

                    } else {
                        $.each(response, function (i, items) {
                        	if(item.find("#HotboxForm_" + i + "_em_").length > 0) {
                        		item.find("#HotboxForm_" + i + "_em_").html(items[0]);
                                item.find("#HotboxForm_" + i + "_em_").css('display', 'block');
                        	} else {
                        		item.find("#Hotbox_" + i + "_em_").html(items[0]);
                                item.find("#Hotbox_" + i + "_em_").css('display', 'block');
                        	}
                        });
                    }*/
                },
                dataType: 'json'
            });
        }
    },
    process_upload: function () {
        $(this).loading();
    },
    complete_upload: function (json, id) {
        $(this).loading();
        if (json.success) {
        	var base_url	=	'http://' + img_webroot_url + '/' + json.filepath;
        	
            var html = '<li id="hb_t_' + json.image_id + '">'
                + '<input type="hidden" value="' + json.image_id + '" name="HotboxForm[tmp_images][]">'
                + '<a href="javavascript:void(0);"><img src="'+ base_url + '/thumb300x0/' + json.filename + '"></a>'
                + '<ol>'
                + '<li><a onclick="Hotbox.delete_thumb(' + json.image_id + ')" class="icon_del_thumb"></a></li>'
                + '<li class="hb_default_t"><a onclick="Hotbox.set_thumb(' + json.image_id + ')" class="icon_check_thumb"></a></li>'
                + '</ol>'
                + '</li>';
            $(".hotbox_thumbnail ul").append(html);
            $('.notify_create_hb').show();
        }

        $(this).unloading();
    },
    onError: function (err) {
        if (err) {
            Util.popAlertFail(err, 500);
            setTimeout(function () {
                $(".pop-mess-fail").pdialog('close');
            }, 3000);
            return false;
        }
        return false;
    },
    delete_thumb: function (id) {
        var answer = confirm(tr('Are you sure you want to remove this photo?'));
        if (answer) {
            $(this).loading();
            $.ajax({
                type: "POST",
                url: "/hotbox/deleteImage/" + id,
            }).done(function () {
                    $(this).unloading();
                    $("#hb_t_" + id).fadeOut().remove();
                    // FineUploader_FineUploader.reset();
                    FineUploader_FineUploader._netFilesUploadedOrQueued = FineUploader_FineUploader._netFilesUploadedOrQueued - 1;
                });
        }
    },
    set_thumb: function (id) {
        if (id) {
            $('.hotbox_thumbnail ol li.hb_default_t').each(function (key, value) {
                $(value).find('a').removeClass('icon_check_thumb_active').addClass('icon_check_thumb');
            });
            var thumbnail = '<input type="hidden" name="HotboxForm[thumbnail_id]" value="' + id + '">';
            $('#thumbnail_id').html(thumbnail);
            $("#hb_t_" + id).find('.icon_check_thumb').addClass('icon_check_thumb_active').removeClass('icon_check_thumb');


        }
    },
    open_upload: function (id) {
        $('.' + id + ' input').trigger('click');
    },
    button_submit: function () {
        $('#hotbox-submit').click(function (e) {
            e.preventDefault();
            $("#hotbox-form").trigger('submit');

        });
    },
    button_cancel: function () {
        $("#hotbox-form").trigger('reset');
        var url = '/hotbox';
        Util.changeUrlAjax(url);
        window.location = url;

    },
    default_type: function () {
        var type = $("#Hotbox_type").val();
        if (typeof type != 'undefined' && type > 0) {
            if (type == 1) {
                FineUploader_FineUploader._options.multiple = true;
                FineUploader_FineUploader._options.validation.itemLimit = 20;
            } else {
                FineUploader_FineUploader._options.validation.itemLimit = 1;
            }
        }
    },
    change_type: function (item) {

        var type_id = item.value;
        if (type_id == 1) {
            FineUploader_FineUploader._options.multiple = true;
            FineUploader_FineUploader._options.validation.itemLimit = 20;
            //$('.row-event').show();
        } else {
            //$('.row-event').hide();
            FineUploader_FineUploader._options.validation.itemLimit = 1;
        }
    },
    close_detail: function (append) {

        //$(".col-feed,.col-right-hotnew").remove();
        // Chinh reload lai danh sach hotbox

        var url = '/hotbox';
        Util.changeUrlAjax(url);
        var isChrome = window.chrome;
        if (isChrome) {
            // is chrome
            window.location.href = url;
        } else {
            //other browser
            $(".col-feed").removeClass('col-left');
            $(".col-right-hotnew").css('width', '100%');
            var this_hotbox = $("#this_hotbox").html();
            $(".pint-style .list").prepend(this_hotbox).isotope('reloadItems');
            Hotbox.isotope();
        }

        /*if (append == true) {
            var this_hotbox = $("#this_hotbox").html();
            $(".pint-style .list").prepend(this_hotbox).isotope('reloadItems');
        }*/
        //Hotbox.isotope();

    },
    filter_hotbox: function (type, type_id) {
    	if(type != '*'){
    		var url = window.location.href + '?type=' + type_id;
    	} else {
    		var url = window.location.href;
    	}
        $('#hotboxs').isotope({ filter: type });
        /*$(this).loading();
        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {
                var $data = $(data);
                $('#hb-list').append('<div id="temp-load"></div>');
                $('#temp-load').append($data);
                $('#temp-load').children().css({opacity: 0});
                var len_img = $('.hotbox-new #temp-load .item .photo li img').length, count_img = 0;
                $('.hotbox-new #temp-load .item .photo li img').each(function () {
                    $(this).load(function () {
                        count_img += 1;
                        if (count_img == len_img) {
                            var addList = $('#temp-load').html();
                            $('#hotboxs').isotope('insert', $(addList), function () {
                                $('#hotboxs').children().css('opacity', 1);
                                $('#temp-load').remove();
                            });
                            return false;
                        } else {
                            console.log('Loading...');
                        }
                    }).error(function () {
                            console.log('Error load image!');
                	});
                });
                $(this).unloading();
            	return $('#hotboxs').isotope('reloadItems').isotope( 'option', { layoutMode: 'fitRows', filter: type } );
            },
            dataType: 'html'
        });*/
    	
    },
    show_gal_hotbox: function () {
        /*// Xac dinh chieu rong cua gallery
        $(".gallery_hotbox").css('width', $(window).width() - 400);
        $("ul#slideshow").css('height', $(window).height());

        $("#supersized").css('width', $(window).width() - 400);
        //$("#supersized li").css('width',$(window).width() - 400);

        // xac dinh trong tam hinh anh chieu rong
        $a = ($(window).width() - 400) / 2;
        $b = $('ul#slideshow li').width() / 2;
        $("ul#slideshow li").css('left', $a - $b);
        // xac dinh trong tam hinh anh chieu cao
        $c = $(window).height() / 2;
        $d = $('ul#slideshow li').height() / 2;
        $("ul#slideshow li").css('top', $c - $d);

        $(".zoom_pics").click(function () {
            $(".page-filter").hide();
            $(".hotbox_detail").hide();
            if (typeof listImg !== "undefined" && listImg.length > 0) {
                Hotbox.make_slider(listImg);
            }
            $(".popup_hotbox").show();
            $("#supersized").show();
            $("#supersized").css("display", "block");
            $("#supersized li img").css("top", "0");
            $(".col-nav + .main").addClass("left_0");
            $(".col-right-hotnew .pint-style").css("position", "absolute");
        });

        $(".gallery_close").click(function () {
            $(".page-filter").show();
            $(".hotbox_detail").show();
            $(".popup_hotbox").hide();
            $("#supersized").hide();
            $(".col-nav + .main").removeClass("left_0");
            $('.col-right').show();
            $(".col-right-hotnew .pint-style").css("position", "relative");
        });*/
    },
    make_slider: function (listImage) {
        /*$('#supersized').html('');
        $.supersized({
            // Functionality
            slideshow: 1,			// Slideshow on/off
            autoplay: 0,			// Slideshow starts playing automatically
            start_slide: 1,			// Start slide (0 is random)
            stop_loop: 0,			// Pauses slideshow on last slide
            random: 0,			// Randomize slide order (Ignores start slide)
            slide_interval: 3000,		// Length between transitions
            transition: 6, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
            transition_speed: 1000,		// Speed of transition
            new_window: 1,			// Image links open in new window/tab
            pause_hover: 0,			// Pause slideshow on hover
            keyboard_nav: 1,			// Keyboard navigation on/off
            performance: 1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
            image_protect: 1,			// Disables image dragging and right click with Javascript

            // Size & Position
            min_width: 0,			// Min width allowed (in pixels)
            min_height: 0,			// Min height allowed (in pixels)
            vertical_center: 1,			// Vertically center background
            horizontal_center: 1,			// Horizontally center background
            fit_always: 0,			// Image will never exceed browser width or height (Ignores min. dimensions)
            fit_portrait: 1,			// Portrait images will not exceed browser height
            fit_landscape: 0,			// Landscape images will not exceed browser width

            // Components
            slide_links: 'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
            thumb_links: 1,			// Individual thumb links for each slide
            thumbnail_navigation: 0,			// Thumbnail navigation
            slides: listImage,

            // Theme Options
            progress_bar: 1,			// Timer for each slide
            mouse_scrub: 0

        });*/

        
    },
    edit_form: function (id) {
        $('#' + id).css('width', '788px');
        $('#' + id).show();
        $('.col-left').hide();
        $('.col-right').hide();

    },
    remove: function (id) {
        if (id) {
            $(".popup-alert.delete_hotbox .frame_content").html(tr('Are you sure you want to remove this hotbox?'));
            $(".popup-alert.delete_hotbox").pdialog({
                title: tr('Hotbox'),
                buttons: [
                    {
                        text: tr("OK"),
                        click: function () {
                            $(this).loading();
                            var url = "/hotbox/remove/" + id;
                            $.ajax({
                                type: 'POST',
                                url: url,
                                success: function (response) {
                                    if (response.status != undefined && response.status == true) {
                                        Hotbox.close_detail(false);
                                        $(".popup-alert.delete_hotbox").dialog("destroy");
                                    }
                                    $(this).unloading();
                                },
                                dataType: 'json'
                            });
                        }
                    },
                    {
                        text: tr("Cancel"),
                        click: function () {
                            $(this).pdialog("close");
                        }
                    },
                ],
            });

        }

    },
    like: function (id, type) {
    	if(isGuest == 1){
    		window.location.href  = '/site/login?redirect_url=' + window.location.pathname;
    		return false;
    	}
        if (id > 0) {
            $(this).loading();
            var url = '/hotbox/like';
            var comment_like = $('.' + type + '-' + id).parent();
            var text_like = $('.like_comment #' + type + '-' + id);
            var $numLikeChange = $('#' + type + '-' + id).find('.num-like-hotbox'), $changeText = $('#' + type + '-' + id).find('.txt-change-like');
            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id, type: type},
                success: function (response) {
                    if (response.status != undefined && response.status == true) {
                    	comment_like.find('span').html(response.like_count);
                    	text_like.html(response.text);
                        $numLikeChange.html(response.like_count);
                        $changeText.html(response.text);
                    }
                    if (comment_like.find('i').attr('class') == 'ismall ismall-like-unactive') {
                        comment_like.find('i').attr('class', 'ismall ismall-like');
                    } else {
                        comment_like.find('i').attr('class', 'ismall ismall-like-unactive');
                    }
                    $(this).unloading();
                },
                dataType: 'json'
            });
        }
    },
    focus_comment: function (item) {
        $(".main .hotbox_detail").mCustomScrollbar("destroy");
        this.scoll_class('.main .hotbox_detail');
        $('.main .hotbox_detail').mCustomScrollbar('scrollTo', 'bottom');
        $('.replyMsg#comment_area').focus();
    },
    submit_comment: function () {
        $(".comment_hb_detail").on("keydown", "#comment_area", function (event) {
            if (event.which == 13 && !event.shiftKey) {
            	if(isGuest == 1){
            		window.location.href  = '/site/login?redirect_url=' + window.location.pathname;
            		return false;
            	}
                var content = $('textarea#comment_area').val();
                var id = $('textarea').attr('rel');
                var url = '/hotbox/comment';
                if (id && content.length > 0) {
                    $('body').loading();
                    $('textarea#comment_area').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {content: content, id: id},
                        success: function (response) {
                            if (response.status = 'true') {
                                $('.comment-hotbox-' + id).html(response.comment_count);
                                $('#comment_list_hotbox').append(response.after_save);
                                var number_comment = parseInt($('#comment_list_hotbox').find('li').size());
                                if (number_comment > 10) {
                                    $('#comment_list_hotbox').find('li:first').remove();
                                }
                            }
                            $('body').unloading();
                            $('textarea#comment_area').prop('disabled', false);
                        },
                        dataType: 'json'
                    });
                }
                event.preventDefault();
                $('textarea#comment_area').val('');
                return false;
            }
        });

    },
    load_more_comment: function (id) {
        var next_page = $('.nav_right').find('a').attr('next_page');
        if (next_page != 'end') {
            $('body').loading();
            $.ajax({
                type: "POST",
                url: '/hotbox/listComment?page=' + next_page,
                data: {id: id},
                success: function (response) {
                	$('body').unloading();
                    if (response.status = 'true') {
                        $('#comment_list_hotbox').prepend(response.after_save);
                        if ($(response.after_save).filter('.next_page').attr('next_page') == 0) {
                            Hotbox.focus_comment(this);
                            $('.nav_right').remove();
                            //console.log($(response.after_save).filter('.next_page').attr('next_page'));
                            //$('.nav_right').find('a').attr('next_page', $(response.after_save).filter('.next_page').attr('next_page'));
                        }
                        var dulicateArr = [];
                        $($('#comment_list_hotbox').find('li')).each(function (index, value) {
                            var currentID = $(value).attr('class');
                            if (currentID > 0) {
                                if ($.inArray(currentID, dulicateArr) == -1) {
                                    dulicateArr.push(currentID);
                                } else {
                                    $(this).remove();
                                }
                            }
                        });
                    }
                    
                },
                dataType: 'json'
            });
        }
    },
    delete_comment: function (id) {
        if (id) {
            $(".popup-alert.delete_comment .frame_content").html(tr('Are you sure you want to remove this comment?'));
            $(".popup-alert.delete_comment").pdialog({
                title: tr('Comment'),
                buttons: [
                    {
                        text: tr("OK"),
                        click: function () {
                            $('body').loading();
                            $.ajax({
                                type: "POST",
                                url: '/hotbox/deleteComment',
                                data: {id: id},
                                success: function (response) {
                                    if (response.status = 'true') {
                                        $('.comment-hotbox-' + response.hotbox_id).html(response.comment_count);
                                        $('.comment-id-' + id).fadeOut().remove();
                                    }
                                    $(".popup-alert.delete_comment").pdialog("close");
                                    $('body').unloading();
                                },
                                dataType: 'json'
                            });
                        }
                    },
                    {
                        text: tr("Cancel"),
                        click: function () {
                            $(".popup-alert.delete_comment").pdialog("close");
                        }
                    },
                ],
            });

        }
    },
    select_share: function () {

    },
    change_type: function() {
    	FineUploader_FineUploader._netFilesUploadedOrQueued = $('.hotbox_thumbnail ul > li').length;
    	if($( "#when-from").length > 0)
    		$( "#when-from, #when-to" ).datetimepicker({dateFormat: 'dd-mm-yy', timeFormat: "hh:mm"});
    	$(document).on('click', '#link-wrap a', function(e){
    		$('#HotboxForm_type').val($(this).data('type'));
    		var parent = $(this).parent();
    		if(parent.attr('id')=='photo-link') {
				$('.row-where, .row-when').hide();
				FineUploader_FineUploader._options.validation.itemLimit = 1;
			} else {
				$('.row-where, .row-when').show();
				// change 1 -> n for limit upload on creating event hotbox 
				FineUploader_FineUploader._options.validation.itemLimit = 1;
			}
    		$('#link-wrap').fadeOut('500', function(){
    			$('.hotbox-create').fadeIn();
    		});
    		e.preventDefault();
    	});
    }
};


$(document).ready(function () {
    $(".upload-zone").hide();
    Hotbox.isotope();
    Hotbox.resize_thumbnail();
    Hotbox.show_gal_hotbox();
    Hotbox.load();
    Hotbox.submit_comment();
    if($('#hotbox-form').length > 0) Hotbox.change_type();
});

function getStateRegister(){
	$('body').loading();
	$('#select-state').val('').parent().hide();
	$('#select-city').val('').parent().hide();
	$('#select-district').val('').parent().hide();
	var data = { country_id: $('#select-country option:selected').val() };
	$.ajax({
        type: "GET",
        data: data,
        url: '/location/getStateListRegister',
        success: function(data) {
    		if(data != ''){
    			$('#select-state').html(data).parent().show();
    			$('.state_register_text').html($('#select-state option').eq(0).text());
    		}
    		$('body').unloading();
        },
        dataType: 'html'
    });
}
function getCityRegister(){
	$('body').loading();
	$('#select-city').val('').parent().hide();
	$('#select-district').val('').parent().hide();	
	var data = { state_id: $('#select-state option:selected').val() };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/getcitylistRegister',
        success: function(data) {
        	if(data != ''){
    			$('#select-city').html(data).parent().show();
    			$('.city_register_text').html($('#select-city option').eq(0).text());
    		}
    		$('body').unloading();
        },
        dataType: 'html'
    });	
}
function getDistrictRegister(){
	$('body').loading();
	$('#select-district').val('').parent().hide();
	var data = { city_id: $('#select-city option:selected').val() };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/getdistrictlistRegister',
        success: function(data) {
        	if(data != ''){
    			$('#select-district').html(data).parent().show();
    			$('.district_register_text').html($('#select-district option').eq(0).text());
    		}
    		$('body').unloading();
        },
        dataType: 'html'
    });	
}