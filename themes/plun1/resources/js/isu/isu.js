var ISU = {
	isotope: function(){
		var $container = $('.pint-style .list'),
			wW = $('.isu-new').outerWidth(),
			wItems = $('.isu-new .pint-style .list .item').outerWidth(),
			wItemsMargin = wItems + 20,
			numColl = Math.floor(wW/wItemsMargin),
			wParentCenter = numColl*wItemsMargin;
		console.log(1);
		$(window).resize(function(){
			wW = $('.isu-new').outerWidth();
			numColl = Math.floor(wW/wItemsMargin);
			wParentCenter = numColl*wItemsMargin;
			$('.pint-style').css({
				width: wParentCenter+'px',
				margin: '0px auto 0px'
			});
		});		
		$('.pint-style').css({
			width: wParentCenter+'px',
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
		$optionLinks.click(function(){
	        var $this = $(this);
	        // don't proceed if already selected
	        if ( $this.hasClass('active') ) {
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
	        $container.isotope( options );
	     
	        return false;
		});
	},	
	delete_isu: function(id){
		var answer = confirm (tr('Are you sure you want to delete this item?'));
		if (answer){
			
			if(id){
				$(this).loading();
				var url = "/isu/delete/" + id;
				$.ajax({
					type: 'POST',
					url: url,
					success:function(response){
						if (response.status != undefined && response.status == true){
							ISU.close(false);
						}
						$(this).unloading();
				    },
				 	dataType:'json'
				});
			}
		}
	}, 
	
	load_isu: function(){
		$("ul li.isu-load .ajax_isu").live( "click", function(e) {
			//e.preventDefault();
			var id = $(this).attr('rel');
			if(id){
				$('body').loading();
				var url = "/isu/load/" + id;
				$.ajax({
					type: "GET",
					url: url,
					success: function( res ) {
						var $wrapHTML = $('.popupBoxShow_hotbox_isu'), hWindow = $(window).outerHeight(),hScroll;
                        $wrapHTML.append($(res));
                        var $temp = $wrapHTML.find('.col-feed'),
                        	$DomReply = $wrapHTML.find('#isu-reply-box'),
                        	$DomForward = $wrapHTML.find('#isu-forward-box'),
                        	$popupReply = $('.popupBoxShow_isu_reply'),
                        	$popupForward = $('.popupBoxShow_isu_forward');
                        $wrapHTML.html('');
                        $wrapHTML.append($temp);
                        $popupReply.append($DomReply);
                        $popupForward.append($DomForward);
                        $wrapHTML.css({
                            display: 'block',
                            visibility: 'hidden'
                        });
                        var hDialog = $wrapHTML.outerHeight();
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
                            	$('.popupBoxShow_isu_reply').html('');
                            	$('.popupBoxShow_isu_forward').html('');
								
								return false;
							}
                        });
						var chat = $(".chat-box-area").clone();
						//$('.main-wrap').fadeOut().remove();
						//$('.main').html(res);
						$('.main').append(chat);
						//Util.changeUrlAjax(url);
						/*$('.btn-del-hotbox').live('click',function(){
                            $wrapHTML.pdialog('close');
                            return false;
                        });*/
						ISU.scoll_class('.hotbox_detail');
						ISU.isotope();
						$(this).unloading();
						$('.btn-del-hotbox').live('click',function(){
                            $(".popupBoxShow_hotbox_isu").pdialog("close");
                        });
					}
				});
			}
			return false;
		});
		/*
		$("ul li.isu-load a").live( "click", function() {
			$('body').loading();
			return true;
		});
		var isuid = $(this).attr('rel');
		$(".item-active").removeClass("item-active");
		$(this).closest(".item").addClass("item-active");
		if(isuid){
			var url = "/isu/load/id/" + isuid;
			$.get( url, function( res ) {
				Util.changeUrlAjax(url);
				if ($(".pint-style .item").hasClass("item-detail"))
				{
					$(".item-detail").closest(".item").remove();
				} 
				$(".pint-style .list").prepend(res).isotope('reloadItems').isotope({ sortBy: 'original-order'}, sprScrollTop);
				$(this).unloading();
			});
		}*/
	},
	reply: function(){
		if(isGuest == 1){
    		window.location.href  = '/site/login?msgLogin=true&redirect_url=' + window.location.pathname;
    		return false;
    	}
		$('.popupBoxShow_isu_reply').pdialog({
			width: 500,
			dialogClass: 'dialog_reply_isu'
		});
		$(".ui-dialog-titlebar").hide();
		var zIndexPopupISU = $('.dialog_reply_isu').css('z-index');
		$('.dialog_reply_isu').prev('.ui-widget-overlay').css('z-index',zIndexPopupISU);
		$("#reply-isu-form").trigger('reset');
		
	},
	reply_submit : function(form, data, hasError) {
		if (!hasError) {
			var item = $("#reply-isu-form");
			var data = item.serialize();
			$(this).loading();
			$.ajax({
				type: 'POST',
				url: item.attr('action'),
			  	data:data,
				success:function(response){
					if (response.status != undefined && response.status == true){
						$('#isu-reply-box').modal('hide');
						if(current_lang == 'en'){
							Util.popAlertSuccess(tr('Reply sent!'), 230);
						} else {
							Util.popAlertSuccess(tr('Reply sent!'), 300);
						}
						setTimeout(function () {
							$( ".pop-mess-succ" ).pdialog('close');
						}, 1000);
					} else{
						$.each(response, function(i, items) {
							item.find("#MessageForm_"+i+"_em_").html(items[0]);
							item.find("#MessageForm_"+i+"_em_").css('display', 'block');
						});
					}
					$(this).unloading();
			    },
			 	dataType:'json'
			 });
		}
	},
	forward_submit : function(form, data, hasError) {
		if (!hasError) {
			var item = $("#forward-isu-form");
			var data = item.serialize();
			$(this).loading();
			$.ajax({
				type: 'POST',
				url: item.attr('action'),
			  	data:data,
				success:function(response){
					if (response.status != undefined && response.status == true){
						$('#isu-forward-box').modal('hide');
						Util.popAlertSuccess(tr('This ISU has been forwarded!'), 340);
						setTimeout(function () {
							$( ".pop-mess-succ" ).pdialog('close');
						}, 1000);
					} else{
						$.each(response, function(i, items) {
							item.find("#MessageForm_"+i+"_em_").html(items[0]);
							item.find("#MessageForm_"+i+"_em_").css('display', 'block');
						});
					}
					$(this).unloading();
			    },
			 	dataType:'json'
			 });
		}
	},
	forward: function(){
		if(isGuest == 1){
    		window.location.href  = '/site/login?msgLogin=true&redirect_url=' + window.location.pathname;
    		return false;
    	}
		//$('#isu-forward-box').modal('show');
		$('.popupBoxShow_isu_forward').pdialog({
			width: 500,
			dialogClass: 'dialog_reply_isu'
		});
		$(".ui-dialog-titlebar").hide();
		var zIndexPopupISU = $('.dialog_reply_isu').css('z-index');
		$('.dialog_reply_isu').prev('.ui-widget-overlay').css('z-index',zIndexPopupISU);
		$("#forward-isu-form").trigger('reset');
		
	},
	
	submit: function(form, data, hasError) {
		if (!hasError) {
			$(this).loading();
			var item = $("#isu-form");
			var data = item.serialize();
			$.ajax({
				type: 'POST',
				url: item.attr('action'),
			  	data:data,
				success:function(response){
					$(this).unloading();
					window.location.href = "/isu";
					/*if (response.status != undefined && response.status == true){
						$('.main-wrap').remove();
						$('.main').html(response.after_save);
						ISU.scoll_class('.hotbox_detail');
						Util.changeUrlAjax(response.url);
						ISU.isotope();
					} else{
						$.each(response, function(i, items) {
							item.find("#Notes_"+i+"_em_").html(items[0]);
							item.find("#Notes_"+i+"_em_").css('display', 'block');
						});
					}*/
			    },
			 	dataType:'json'
			 });
		}
	},
	form_back: function(){
		$("#isu-form").trigger('reset');
		var url = '/isu';
		Util.changeUrlAjax(url);
		window.location = url;
		
	},
	cancel: function(id, e){
		e.preventDefault();
		if($('.popupBoxShow_isu_reply').is(':visible')){
			$('.popupBoxShow_isu_reply').pdialog('close');
		}
		if($('.popupBoxShow_isu_forward').is(':visible')){
			$('.popupBoxShow_isu_forward').pdialog('close');
		}
		return false;
	},
	show_more: function(){
		$(this).loading();
		var page = $('#isus').attr('page');
		if(page != 'end'){
			var url = (window.location.href).split('#')[0] + '?page=' + page;
			$.ajax({
				type: "GET",
				url: url,
				success: function(data){
					if(data == 'end'){
						$('.col-full .more-wrap').hide();
					} else {
						var next_page =   $(data).filter('#next_page').attr('page');
						if(next_page == 'end'){
							$('.col-full .more-wrap').hide();
						} else {
							$('#isus').attr('page', next_page);
						}
                        var $data = $(data);
                        $('#isu-list').append('<div id="temp-load"></div>');
                        $('#temp-load').css({
                        	position: 'absolute',
                        	top: 0,
                        	left: 0,
                        	display: 'none'
                        }).append($data);
                        $('#temp-load').children().css({opacity: 0});
                        var len_img = $('#temp-load .item .cont').find('img').length, count_items = 0, strHtml = '',count_img = 0,flagImg = false, $tempDom = $('<div></div>');

                        $('#temp-load .item .cont').find('img').load(function(){
                            count_img += 1;
                            console.log('LOADED!!!! '+ $(this).parents('.item'));
						}).error(function(){
							len_img -= 1;
                        	$(this).hide();
                        	console.log('ERROR!!!!! '+ $(this).parents('.item'));
                        });
                        var showBox = setInterval(function(){
                        	console.log(count_img+ ' num image '+len_img);
                        	if(count_img == len_img){
                        		$('#isus').isotope('insert', $($('#temp-load').html()), function () {
		                            $('#isus').children().css('opacity', 1);
		                            $('#temp-load').remove();
		                        });
		                        clearInterval(showBox);
                        	}
                        },100);
                        
                        /*var len_img = $('.isu-new #temp-load .item .cont').length, count_img = 0, strHtml = '';
                        $('#temp-load .item .cont').each(function(){
                        	var _this = $(this);
                        	if(_this.find('img').length > 0){
                        		_this.find('img').load(function (i) {
                        			console.log('Image loading...');
                        			strHtml += $(this).closest('.item')[0].outerHTML;
                        		}).each(function(i){
                        			if(this.complete){
                        				console.log('Image load completed');
                        				strHtml += $(this).closest('.item')[0].outerHTML;	
                        			}else{
                        				console.log('Image not found!!!!!!!');	
                        				this.style.display = 'none';
                        				strHtml += $(this).closest('.item')[0].outerHTML;
                        			}
                        			
                        		});
                        	}else{
                        		console.log('img khong ton tai '+count_img);
								strHtml += $('#temp-load').children().eq(count_img)[0].outerHTML;
							}
							count_img += 1;
                        });
						$('#isus').isotope('insert', $(strHtml), function () {
                            $('#isus').children().css('opacity', 1);
                            $('#temp-load').remove();
                        });*/
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
	scroll: function () {
		$(".main .col-full").mCustomScrollbar("destroy");
		sprScroll('.main .col-full');
		//$('.main .col-full').mCustomScrollbar('scrollTo', 'bottom');
		$('.main .col-full').mCustomScrollbar("scrollTo",'last');
	},
	close: function(append){
		$('.popupBoxShow').pdialog('close');
		/*//$(".col-feed").removeClass("col-left");
		// Chinh reload lai danh sach hotbox
		var url = '/isu';
		Util.changeUrlAjax(url);
		
		var isChrome = window.chrome;
		ISU.scoll_class('.col-right-hotnew');
		if(isChrome) {
		   // is chrome
			window.location.href = url;
		} else {
			//other browser
			//$(".col-right-hotnew").css('width','100%');
            $(".col-feed").removeClass('col-left');
            $(".col-right-hotnew").css('width', '100%');
            var this_hotbox = $("#this_isu").html();
            $(".pint-style .list").prepend(this_hotbox).isotope('reloadItems');
            ISU.isotope();
		}*/
		
		/*if(append == true){
			var this_isu = $("#this_isu").html();
			$(".pint-style .list").prepend(this_isu).isotope('reloadItems');
		}
		ISU.isotope();*/
		
	},
	process_upload: function(){
		$(this).loading();
	},
	complete_upload: function(json, id){
		$(this).loading();
		if(json.success){
			var base_url	=	'http://' + img_webroot_url + '/' + json.filepath;
			var html = '<li id="isu-t">' 
					+ '<input type="hidden" value="' + json.filename + '" name="Notes[image]">'
					+ '<input type="hidden" value="' + json.filepath + '" name="Notes[image_path]">'
	            	+ '<a href="javavascript:void(0);"><img src="' +  base_url + '/thumb300x0/' + json.filename + '"></a>'
	            	+ '<ol>'
		            	+ '<li><a onclick="ISU.delete_thumb(\''+json.filename+'\', \''+json.filepath+'\')" class="icon_del_thumb"></a></li>'
	            	+ '</ol>'
            	+'</li>';
			$(".hotbox_thumbnail ul").append(html);
		}
		
		$(this).unloading();
	},
	onError: function(err){
		if(err){
			Util.popAlertFail(err, 500);
			setTimeout(function () {
				$( ".pop-mess-fail" ).pdialog('close');
			}, 3000);
			return false;
		}
		
	},
	delete_thumb: function(name, path){
		var answer = confirm (tr('Are you sure you want to remove this photo?'));
		if (answer)
		{
			$(this).loading();
			$.ajax({
				type: "POST",
				url: "/isu/RemoveImg",
				data: {name: name, path: path},
			}).done(function() {
				FineUploader_FineUploader.reset();
				$(this).unloading();
				$("#isu-t").fadeOut().remove();
			});
		}
	},
	
	open_upload: function(id){
		var get_thumbnail = $('.hotbox_thumbnail ul').find('li').html();
		if(get_thumbnail == undefined){
			$('.' +id+' input').trigger('click');
		} else {
			alert(tr('Please delete image first for new upload.'));
		}
	},
	filter: function(type){
		return $('#isus').isotope('reloadItems').isotope( 'option', { layoutMode: 'fitRows', filter: type } );
	},
	limit_upload: function(){
		FineUploader_FineUploader._options.validation.itemLimit = 0;
	},	
	scoll_class: function(cls){
		$(cls).mCustomScrollbar({
			scrollInertia: "0",
			mouseWheelPixels: "auto",
			autoHideScrollbar: true,
			advanced:{
				updateOnContentResize: true,
				contentTouchScroll: true
			}
		});
		
	},
	scroll_after_ajax_form: function () {
		$(".main .col-right .pint-style").mCustomScrollbar("destroy");
		this.scoll_class('.main .col-right .pint-style');
		$('.main .col-right .pint-style').mCustomScrollbar('scrollTo', 'bottom');
		this.scoll_class('.col-right-hotnew');
		this.scoll_class('.hotbox_detail');
	},
};


$(document).ready(function () {
	$(".upload-zone").hide();
	ISU.isotope();
	ISU.load_isu();
});




		
