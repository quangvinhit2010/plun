$(function(){	
	Photo.generateSlidePhoto();
});
var Photo = {
	delete_photo: function (item) {
        if(item){
        	
        	$(item).closest('.item').addClass('show-overlay');
        	$(item).closest('.item').find('.share_del').hide();
        	
        	var confirm = '<div class="ulti ulti-delete active show">' +
			 '<p>' + tr('Delete this Photo?') + '</p>' +
			 '<div class="buttons">' +
			 '<button type="button" rel="Yes" class="btn btn-gray yes">' + tr('Yes') + '</button>' + 
			 '<button type="button" rel="No" class="btn btn-gray no">' + tr('No') + '</button>' + 
			 '</div>' +
			 '</div>';
        	var parentItem = $(item).closest('li.item');
        	parentItem.append(confirm);
            parentItem.find('.ulti-delete').show();
        	parentItem.find('button.yes').click(function(){
        		var photo_id = $(item).closest('.item').attr('id');
    			var type = $(item).data('type');
    			objCommon.loading();
    			$.ajax({
    				type: "POST",
    				data: {photo_id: photo_id, type: type},
    				url: '/photo/delete',
 			        success: function(data){
 			    		if($(data == 0)){
 			    			$(item).closest('.item').fadeOut().remove();
 			    			objCommon.unloading();
 			    		} 
 			    		
 			        },
 			        dataType: 'html'
    			});
        	});
        	parentItem.find('button.no').click(function(){
                var _this = $(this);
                _this.closest('.item').removeClass('show-overlay');
                _this.closest('.item').find('.share_del').show();
                _this.closest('.ulti-delete').removeClass('show').addClass('hide');
                setTimeout(function(){
                    _this.closest('.ulti-delete').remove();
                },250);
        	});
        } else {
        	return false;
        }
    },
    upload_photo_open: function(item){
    	FineUploader_upload_photo._options.request.endpoint = $(item).data('endpoint');
		$('#upload_photo input').trigger('click');
    },
    more_upload_photo_open: function(item, id){
    	$(item).closest('.block-photo-more').find('.photos-upload').find('#' +id+' input').trigger('click');
    },
    photoBeforeUpload: function (){
    	objCommon.loading();
    },
    prePhotoAfterUpload: function (json,id){
    	if(!json.error){
    		var item = $('#' + id + '-view');
    		var number_item = parseInt(item.children().size());    	
    		if(number_item <= 9){
    			Photo.insertPhoto(item, json);
    		}else {
    			if(item.find('.item .btn-more-load').length < 1){
    				var show_more = '<li class="item" id="photo_show_more">'
    					+ '<a href="javascript:Photo.show_more(\''+ id +'\');" class="btn-more-load">'
    					+ '<label></label>'
    					+ '</a>'
    					+ '</li>';
    				item.attr('page', 2);
    				item.append(show_more);
    			} else {
    				item.find('li:last').prev("li").remove();
    				Photo.insertPhoto(item, json);
    			}
    		}
    	}
    },
    insertPhoto: function(item, json){
    	if(json.success){
    		var base_url	=	'http://' + img_webroot_url + '/' + json.filepath;
    		$(item).prepend('<li id="'+ json.fileid +'" class="item">'
	    				+ '<a onclick="Photo.viewPhotoDetail(this);" lis_me="true" data-photo-id="'+ json.fileid +'" limg="' + base_url + '/detail1600x900/' + json.filename +'" lcaption="" href="javascript:void(0);" title="" class="ava">'
	    				+ '<img alt="'+ json.filename +'" src="' + base_url + '/thumb275x275/' + json.filename +'">'
	    				+ '<span class="ava-bg"></span>'
	    				+ '</a>'
	    				+ '<div class="ulti ulti-done">'
		    				+ '<ul>'
		    					+ '<li><a onclick="javascript:Photo.delete_photo(this);" href="javascript:void(0);" class="btn-del"><i class="ismall ismall-x"></i></a></li>'
		    				+ '</ul>'
	    				+ '</div>'
    					+ '<div class="chiase_hinhanh"><a href="javascript:void(o);" photoid="' + json.photoid +'" smallimg="' + base_url + '/detail1600x900/' + json.filename +'" onclick="Photo.sharePhoto(this);">' + tr('Share Photo To Everyone') + '</a></div>'

    				+ '</li>');
        }
    },
    photoAfterUpload: function (json, id){
    	objCommon.unloading();
    	if(json.success) {
    		$('.photo_video .content ul li').eq(0).after($(json.after_save).find('.item'));
    		var items = $('.photo_video .content ul li.item');
    		if(items.length > json.limit && $('.pagging').length > 0)
    			items.last().remove();
    		$('.no_photo').hide();
    		$('.has-photo-wrap').show();
    		applyColorBox();
            $('.photo_video .content ul > li').boxResizeImg({
                parentWrap: '.has-photo-wrap',
                wRealBox: 160,
                hRealBox: 160
            });
            $('.sticky_column').fixed_col_scroll.callbackFun();
    	}
    },
    photoOnError: function(errorReason){
    	objCommon.unloading();
    	Util.popAlertFail(errorReason, 400);
		setTimeout(function () {
			$( ".pop-mess-fail" ).pdialog('close');
		}, 6000);
    	//alert(errorReason);
    	return false;
    },
    photoDiscard: function(item, list){
    	var answer = confirm (tr("Are you sure you want to remove this photo?"));
		if (answer){
			var last_photo = $('#' + list + '-upload ul.item-list-1 .item_1').size();  
		    var id = $(item).closest('li.item_1').attr('id');
		    var parent_item = $('#' + list + '-active ul.item-list');
		    objCommon.loading();
			$.ajax({
				type: "POST",
				url: "/photo/discard",
				data: { "id" : id },
				}).done(function() {
					objCommon.unloading();
					$(item).closest('li.item_1').fadeOut().remove();
					parent_item.find('li#' + id).remove();
					if(last_photo == 1){
						Photo.open_close_dialog(false, list);
					}
			});
		}
    },
    photoSave: function (item, parent_id){
    	var parent_list = $('#' + parent_id + '-active ul.item-list');
    	var last_photo = $('#' + parent_id + '-upload ul.item-list-1 .item_1').size();  
    	var id = $(item).closest('li.item_1').attr('id');
	    var caption = $('textarea#caption').val();
	    	objCommon.loading();
			$.ajax({
				type: "POST",
				url: "/photo/save",
				data: { "id" : id, "caption" : caption },
				}).done(function() {
					$(parent_list).find('li#' + id).find('a.ava').attr('lcaption', caption);
					$(item).closest('li.item_1').fadeOut().remove();
					if(last_photo == 1){
						Photo.open_close_dialog(false, parent_id);
					}
					objCommon.unloading();
					
					
				}	
				
			);
    }, 
    photoSaveAll: function(item, parent_id){
    	var parent_list = $('#' + parent_id + '-active ul.item-list');
    	var items = $("#"+parent_id+"-upload .item-list-1 li.item_1");
    	var data = [];
    	$.each(items, function(key, value){
    		var id = $(value).attr('id');
    	    var caption = $(value).find('.info textarea#caption').val();
    	    $(parent_list).find('li#' + id).find('a.ava').attr('lcaption', caption);
    	    if(id > 0){
    	    	data.push({id: id,caption: caption});
    	    }
	    });
    	
    	if(data){
    		objCommon.loading();
    		$.ajax({
	    		type: "POST",
	    		url: "/photo/saveAll",
	    		data: { "data" : data},
	    	}).done(function() {
	    		$.each(items, function(key, value){
	    			if($(value).hasClass('item_1')){
	    				$(value).fadeOut().remove();
	    			}
	    	    });
	    		Photo.open_close_dialog(false, parent_id);
	    		objCommon.unloading();
	    	});
    	}
    	
    	
    },
    photoDiscardAll: function(item, parent_id){
    	
    	var answer = confirm (tr("Are you sure you want to remove all photo?"));
		if (answer){
			var items = $("#"+parent_id+"-upload .item-list-1 li.item_1");
			var list = $('#' + parent_id + '-view');
	    	var data = [];
	    	$.each(items, function(key, value){
	    		var id = $(value).attr('id');
	    	    if(id > 0){
	    	    	data.push({id: id});
	    	    }
		    });
	    	
	    	if(data){
	    		objCommon.loading();
	    		$.ajax({
		    		type: "POST",
		    		url: "/photo/discardAll",
		    		data: { "data" : data},
		    	}).done(function() {
		    		$.each(items, function(key, value){
		    			var id = $(value).attr('id');
		    				list.find('li#' + id).remove();
		    			  if(id > 0){
		  	    	    	$(value).fadeOut().remove();
		  	    	    }
		    	    });
		    		Photo.open_close_dialog(false, parent_id);
		    		objCommon.unloading();
		    	});
	    	}
		}
		
    },
    show_more: function(id){
    	objCommon.loading();
    	var item = $('#' + id + '-active ul.item-list');
		var page = item.attr('page');
		var type = item.attr('type');
		if(page != 'end'){
			var url = window.location.href + '?page=' + page + '&type=' + type;
			$.ajax({
				type: "GET",
				url: url,
				success: function(data){
					if(data == 'end'){
						item.find('li:last').hide();
					} else {
						//get next_page
						var next_page =   $(data).filter('#next_page').attr('page');
						if(next_page == 'end'){
							item.find('li:last').hide();
						} else {
							item.attr('page',next_page);
						}
						$(data).insertBefore(item.find('li:last').prev("li"));
						Photo.scroll();
						
						var dulicateArr = [];
						$(item.find('li')).each(function (index, value) {
							var currentID = $(value).attr('id');
							if(currentID > 0){
								if($.inArray(currentID, dulicateArr) == -1){
									dulicateArr.push(currentID);   
								} else {
									$(this).remove();
								}
							}
						});
					}
					objCommon.unloading();
				},
				dataType: 'html'
			});
		} else {
			item.find('li:last').hide();
			objCommon.unloading();
		}
    },
    show_more_your_photo: function(id){
    	objCommon.loading();
    	var item = $('.' + id);
    	var page = item.attr('page');
    	var type = item.attr('type');
    	if(page != 'end'){
    		var url = item.attr('url') + '&page=' + page + '&type=' + type;
    		$.ajax({
    			type: "GET",
    			url: url,
    			success: function(data){
    				if(data == 'end'){
    					item.find('li:last').hide();
    				} else {
    					//get next_page
    					var next_page =   $(data).filter('#next_page').attr('page');
    					if(next_page == 'end'){
    						item.find('li:last').hide();
    					} else {
    						item.attr('page',next_page);
    					}
    					$(data).insertBefore(item.find('li:last').prev("li"));
    					
    					var dulicateArr = [];
						$(item.find('li')).each(function (index, value) {
							
							var currentID = $(value).attr('id');
							
							if(currentID > 0){
								if($.inArray(currentID, dulicateArr) == -1){
									dulicateArr.push(currentID);   
								} else {
									$(this).remove();
								}
							}
						});
    					
    				}    				
    				objCommon.unloading();
    			},
    			dataType: 'html'
    		});
    	} else {
    		item.find('li:last').hide();
    		objCommon.unloading();
    	}
    },
    upload_scroll: function () {
		$(".photos-upload-list .item-list-1").mCustomScrollbar("destroy");
		sprScroll('.photos-upload-list .item-list-1');
		$('.photos-upload-list .item-list-1').mCustomScrollbar('scrollTo', 'bottom');
	},
	scroll: function () {
		$(".main .col-right .photos-setting").mCustomScrollbar("destroy");
		sprScroll('.main .col-right .photos-setting');
		$('.main .col-right .photos-setting').mCustomScrollbar('scrollTo', 'bottom');
	},
	scroll_photo_setting: function(){
		sprScroll('#list-photo-setting');
	},
	viewPhotoDetail: function(curr_photo){
		var html = '';
		if($(curr_photo).closest('ul').length > 0){
			var type = $(curr_photo).closest('ul').attr('type');
			$(curr_photo).closest('ul').find('>li').each(function( index ) {
				console.log(index);
				if($( this ).find('a').attr('limg')){
					var it_my_photo = $(this).find('a').attr('lis_me');
					var link_setavatar = '';
					if(it_my_photo != undefined && it_my_photo == 'true' && type == 1){
						link_setavatar = '<a href="javascript:Avatar.set_main_action('+$(this).find('a').attr('data-photo-id')+');" class="setAvatar">' + tr('Set main') + '</a>';
					}
					html += '<li><div><span class="helper"></span><span class="wrap-img"><img class="show-photo-detail-img" src="' +$( this ).find('a').attr('limg')+ '" align="absmiddle"><span>' + link_setavatar + '</span></span></div></li>';
				}
			});
			this.showSlidePhoto($(curr_photo), $('.photo_gallery'), html);
		}		
		return false;
	},
	viewImgDetail: function(curr_photo){
		objCommon.loading();
		$('.show-photo-detail-img').replaceWith('<img class="show-photo-detail-img" border="0" alt="" src="'+$(curr_photo).attr('limg')+'">');
		$('.show-photo-detail-img').hide();
		$('.show-photo-detail-img').load(function(){
			Util.imgSize($(this));
			
			if($(curr_photo).attr('lis_me') == 'true'){
				$('.photo-view .photo .overlay').css('bottom', '56px');
			}
			if($(curr_photo).attr('lcaption').length > 0){
				$('.photo-view .photo .overlay').show();
				$('.photo-view .des').html($(curr_photo).attr('lcaption'));
			} else {
				$('.photo-view .photo .overlay').hide();
				$('.photo-view .des').html('');
			}
			if($(curr_photo).attr('lbutton') == 'true'){
				$('.overlay').append(
						'<div class="buttons">'
						+'<button onclick="Photo.accept_photo_request('+$(curr_photo).attr('lid')+');" class="btn btn-violet">'+ tr('Accept') +'</button>'
						+'<button onclick="Photo.decline_photo_request('+$(curr_photo).attr('lid')+');" class="btn btn-white">'+ tr('Decline') +'</button>'
						+'</div>'
				);
				$('.photo-view .photo .overlay').show();
			} else {
				$('.photo-view .photo .overlay').find('.buttons').remove();
			}
			
			if($(curr_photo).attr('lcaption').length == 0 && $(curr_photo).attr('lbutton') == 'false'){
				$('.overlay').hide();
			} 
			objCommon.unloading();
		});
		
		if($('.photo-view').attr('class')){
			$('.photo-view').addClass('active_photo');
		}

	},
	showSlidePhoto: function (_this, _slide, _list_img){		
		var part = _this.closest('li'),
			posi_active = part.index();	
		_slide.find('.bxslider').html(_list_img);
		if(_slide.find('.bxslider li').length == posi_active){
			posi_active -= 1;
		}
		$(".photo_gallery .bxslider li div").css("height",$(window).height() - 55);	
		var slideGallary = _slide.find('.bxslider').bxSlider({
			mode: 'fade',
			startSlide: posi_active,
			pager: false
		});
		_slide.show();
		_slide.css({visibility: 'visible'}).hide().fadeIn();
		$(".photo_gallery .photo-close").click( function(){
			$(this).parents(".photo_gallery").fadeOut(200);
			setTimeout(function(){
				slideGallary.destroySlider();
				$('.photo_gallery .gallery .bxslider').html('');	
			});
		});
	},
	generateSlidePhoto: function (){
		if($(".photo_gallery").length == 0 ){
			var html = '<div class="photo_gallery">' + 
			'<a href="javascript:void(0);" class="photo-close"><i></i></a>' +
			'<div class="left gallery">' +        	
			'<ul class="bxslider">' +
			'</ul>' +
			'</div>' +
			'</div>';
			$('body').append(html);
		}
	},
	request_view_photo: function(photo_id, requestEl){
		objCommon.loading();
	    var data = {
	    		photo_id: photo_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/photo/requestViewPhoto',
	        success: function(data){
	    		if($(data == '1')){
	    			/*
	    			var html = '<div class="ulti ulti-delete request-privatephoto'+ photo_id +' active"><p>'+ tr('Request sent!') +'</p></div>';
	    			$('#photo-' + photo_id).find('.icon-private-' + photo_id + ' i').remove()
	    			$('#photo-' + photo_id).append(html);
	    			$('.thumbnail-photo-request-' + photo_id).attr('onclick','');
	    			*/
	    			$(requestEl).addClass('active').off('click').html('').append('<div class="wrap_icon_photo"><ins class="icon_vault"></ins></div><label>'+tr('Request sent!')+'</label>').find('.tooltip').remove();
	    			objCommon.unloading();
	    		}
	        },
	        dataType: 'html'
	    });
	},
	request_view_private_photo: function(photo_id, requestEl){
		objCommon.loading();
	    var data = {
	    		photo_id: photo_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        dataType: 'json',
	        url: '/photo/requestViewPrivatePhoto',
	        success: function(data){
	    		if(data.success == '1'){
	    			$(requestEl).closest('li').html('<a href="'+data.detail+'" class="group_gallery cboxElement"><img src="'+data.thumbnail+'" align="absmiddle" width="158" height="158"></a>');
	    			objCommon.unloading();
	    			applyColorBox();
	    		}
	        }
	    });
	},
	accept_photo_request: function(e, request_id, cls){
		
		e.preventDefault();
		objCommon.loading();
	    var data = {
	    		request_id: request_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/photo/acceptRequest',
	        success: function(data){
	    		if($(data == '1')){
	    			/*
	    			$('.request-photo-' + request_id).fadeOut().remove();
	    			$('.list_avatar li.list-request-photo-' + request_id).fadeOut().remove();
	    			Photo.after_action(cls);
	    			*/
	    			$('#list-request-photo-'+request_id).remove();
	    			$('#request-photo-'+request_id).remove();
	    			if($('.popup_request_photo ul').children().length == 0) {
	    				if($('#view-'+cls).parent().children().length == 1) {
	    					$('#view-'+cls).parent().prev().remove();
	    					$('#view-'+cls).parent().remove();
	    				} else {
	    					$('#view-'+cls).remove();
	    				}
	    				
	    				if($('.photo_setting .content .left').children().length == 0) {
	    					$('.photo_setting .content .left').append('<p style="padding: 12px;">'+tr('No waiting photo request')+'</p>');
	    				}
	    				
	    				$('.popup_request_photo').pdialog('close');
	    			}
	    			objCommon.unloading();
	    			return false;
	    		}
	        },
	        dataType: 'html'
	    });	
	},
	accept_all_photo_request: function(){
		objCommon.loading();
		var listPhoto = $('.popup_request_photo .content ul li');
		var data = [];
		listPhoto.each(function(){
			var photo_id = $(this).attr('id').replace('request-photo-', '');
			data.push({photo_id: photo_id});
		});
		$.ajax({
	        type: "POST",
	        data: { "data" : data},
	        url: '/photo/acceptAllRequest',
	        success: function(data){
	    		if($(data == '1')){
	    			
	    			var viewRequest = $('#view-' + $('.popup_request_photo .content ul').attr('cls-id'));
	    			if(viewRequest.parent().children().length == 1) {
	    				viewRequest.parent().prev().remove();
	    				viewRequest.parent().remove();
	    			} else {
	    				viewRequest.remove();
	    			}
	    			if($('.photo_setting .content .left').children().length == 0) {
    					$('.photo_setting .content .left').append('<p style="padding: 12px;">'+tr('No waiting photo request')+'</p>');
    				}
	    			$('.popup_request_photo').pdialog('close');
	    			
	    			objCommon.unloading();
	    			return false;
	    		}
	        },
	        dataType: 'html'
		});
	},
	decline_all_photo_request: function(){
		
		objCommon.loading();
		var listPhoto = $('.popup_request_photo .content ul li');
		var data = [];
		listPhoto.each(function(){
			var photo_id = $(this).attr('id').replace('request-photo-', '');
			data.push({photo_id: photo_id});
		});
		$.ajax({
	        type: "POST",
	        data: { "data" : data},
	        url: '/photo/declineAllRequest',
	        success: function(data){
	    		if($(data == '1')){
	    			
	    			var viewRequest = $('#view-' + $('.popup_request_photo .content ul').attr('cls-id'));
	    			if(viewRequest.parent().children().length == 1) {
	    				viewRequest.parent().prev().remove();
	    				viewRequest.parent().remove();
	    			} else {
	    				viewRequest.remove();
	    			}
	    			if($('.photo_setting .content .left').children().length == 0) {
    					$('.photo_setting .content .left').append('<p style="padding: 12px;">'+tr('No waiting photo request')+'</p>');
    				}
	    			$('.popup_request_photo').pdialog('close');
	    			
	    			objCommon.unloading();
	    			return false;
	    		}
	        },
	        dataType: 'html'
		});
	},
	decline_photo_request: function(e, request_id, cls){
		e.preventDefault();
		objCommon.loading();
	    var data = {
	    		request_id: request_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/photo/declineRequest',
	        success: function(data){
	    		if($(data == '1')){
	    			/*
	    			$('.request-photo-' + request_id).fadeOut().remove();
	    			$('.list_avatar li.list-request-photo-' + request_id).fadeOut().remove();
	    			Photo.after_action(cls);
	    			*/
	    			$('#list-request-photo-'+request_id).remove();
	    			$('#request-photo-'+request_id).remove();
	    			if($('.popup_request_photo ul').children().length == 0) {
	    				if($('#view-'+cls).parent().children().length == 1) {
	    					$('#view-'+cls).parent().prev().remove();
	    					$('#view-'+cls).parent().remove();
	    				} else {
	    					$('#view-'+cls).remove();
	    				}
	    				
	    				if($('.photo_setting .content .left').children().length == 0) {
	    					$('.photo_setting .content .left').append('<p style="padding: 12px;">'+tr('No waiting photo request')+'</p>');
	    				}
	    				
	    				$('.popup_request_photo').pdialog('close');
	    			}
	    			objCommon.unloading();
	    			return false;
	    		}
	        },
	        dataType: 'html'
	    });	
	},
	after_action: function(cls){
		var last_photo = $('.photo-view-request ul.item-list-1 .item_1').size();
		if(last_photo == 0){
			$( ".popup-alert.photo-view-request" ).dialog('close');
		}
		
		var last_request = parseInt($('li.'+cls+' ul.list_avatar').find('li').size()); 
		if(last_request == 0){
			$('li.'+ cls).fadeOut().remove();
			
		}
		
		var list_request = $('.cont_photo_setting .photo-setting-request-list');
		var total_request = parseInt($(list_request).find('li.item').size());
		if(total_request == 0){
			$('.cont_photo_setting').find('.date').hide();
			var no_more = '<p class="no-request-friends">'+ tr('No waiting photo request') + '</p>';
			$(list_request).append(no_more);
		}
	},
	open_close_dialog: function(open, id){
		if(open == true){
			$('.'+ id + '-dialog').pdialog({
				title: tr('Review your uploading photos'),
        		resizable: false,
        		modal: true,
        		width: 570		
        	});
		} else{
			$('.'+ id +'-dialog').pdialog('close');
		}
	},
	view_photo_request: function(curr_photo){
		$('.main').tooltip({
			 content: function() {
				 return "<img src='" + $(curr_photo).attr('ltooltip') + "' />";
			 }, 
			 open: function(){
				alert(111); 
			 },
		});
	},
	open_request_popup: function(cls, _this, row_key){
		$( ".popup_request_photo ul" ).html('');
		$(_this).closest('.list_avatar').find('img').each(function(){
			var rid = $(this).data('rid');
			var item = '<li id="request-photo-'+rid+'"><p><img width="160" height="160" src="'+$(this).attr('src')+'" align="absmiddle"></p><div class="left but"><a onclick="Photo.decline_photo_request(event, '+rid+', \''+cls+'\');" href="#">Decline</a> <a onclick="Photo.accept_photo_request(event, '+rid+', \''+cls+'\');" class="active" href="#">Accept</a></div></li>';
			$( ".popup_request_photo ul" ).attr('cls-id', cls).append(item);
		});
		$( ".popup_request_photo" ).pdialog({
			open: function() {
				$( ".popup_request_photo .scroll" ).mCustomScrollbar({
					scrollInertia: "0",
					mouseWheelPixels: "auto",
					autoHideScrollbar: false,
					advanced:{
						updateOnContentResize: true,
						contentTouchScroll: true,
						autoScrollOnFocus:false,
					}
				});
				objCommon.outSiteDialogCommon(this);
			},
			title: tr('Photo request'),
			resizable: false,
			modal: true,
			width: 550,
			buttons: [{
				text: 'Decline All',
				click: function() {
					Photo.decline_all_photo_request();
				}
			}, {
				text:  'Accept All',
				class: 'active',
				click: function() {
					Photo.accept_all_photo_request();
				}
			}]
		});
	},
	update_notice_limit: function(number){
		
	},
	photorequest_showmore: function(){
		objCommon.loading();
		var data	=	{
				offset: $('#photo_request_offset').val()
		};
		 $.ajax({
		        type: "POST",
		        data: data,
		        url: '/photo/PhotoRequestShowMore',
		        success: function(data){
		        	if(data !=''){
		    			$('.photo_setting .content .request-content-wrap').append(data);		    			
		    			var next_offset	=	parseInt($('#photo_request_offset').val()) + parseInt($('#photo_request_limit').val());
		    			$('#photo_request_offset').val(next_offset);
		        	}else{
		        		$('.photo_setting .pagging').hide();
		        	}
		        	objCommon.unloading();
                    $('.sticky_column').fixed_col_scroll.callbackFun();
	    			return false;
		        },
		        dataType: 'html'
	    });
	},
	photoUpdateReadStatus: function(ids){
		if(ids.length > 2){
			var data	=	{
					ids: ids
			};
			 $.ajax({
			        type: "POST",
			        data: data,
			        url: '/photo/UpdateReadStatus',
			        success: function(data){
			        		
			        },
			        dataType: 'html'
		    });		
		}
	},
	sharePhoto: function(obj) {
		/*
		var smallimg	=	$(obj).attr('smallimg');
		var photoid		=	$(obj).attr('photoid');
		
		$('.photo_share_one img').attr('src', smallimg);
		$('#sharephoto_photoid').val(photoid);
		*/
		var parent = $(obj).closest('.item');
		$( ".popup_share_photo" ).pdialog({
			open: function(event, ui) {
				$("body").css({ overflow: 'hidden' });
				objCommon.no_title(); // config trong file jquery-ui.js
				objCommon.outSiteDialogCommon(this);
				$('.share-thumb').attr('src', $('>a >img', parent).attr('src'));
				$('#sharephoto_photoid').val(parent.attr('id'));
			},
			resizable: false,
			position: 'middle',
			draggable: false,
			autoOpen: false,
			center: true,
			width: 325,
			modal: true
		});
	},
	sendSharePhoto: function(){
		var userid	=	$('#txtusername_sharephoto').val();
		
		objCommon.loading();
		if(userid == ''){
			objCommon.unloading();
	    	alert(tr('Please select a username'));    	
	        return false;
		}
		var data	=	{
				photoid: $('#sharephoto_photoid').val(),
				userid: $('#txtusername_sharephoto').val()
		};
		 $.ajax({
		        type: "POST",
		        data: data,
		        url: '/photo/SharePhoto',
		        success: function(data){
		        	$( ".popup_share_photo" ).pdialog('close');	
		        	if(data == '1'){
		    	    	Util.popAlertSuccess(tr('Share photo successfully!'), 300);
		    	        setTimeout(function () {
		    	         $( ".pop-mess-succ" ).pdialog('close');
		    	        }, 2000);
		        	}
		        	if(data == '2'){
		    	    	Util.popAlertSuccess(tr('You\'ve already shared this photo'), 300);
		    	        setTimeout(function () {
		    	         $( ".pop-mess-succ" ).pdialog('close');
		    	        }, 2000);
		        	}		        	
		        	objCommon.unloading();
	    			
	    			return false;
		        },
		        dataType: 'html'
	    });		
	},
	requestAgain: function(photoid, userid, requestEl){
		objCommon.loading();
		var data	=	{
				photoid: photoid,
				userid: userid
		};		
		 $.ajax({
		        type: "POST",
		        data: data,
		        url: '/photo/ResendRequest',
		        success: function(data){
		        	
		        	if(data == '1'){
		        		$(requestEl).addClass('active').removeAttr('onclick').html('').append('<div class="wrap_icon_photo"><ins class="icon_vault"></ins></div><label>'+tr('Request sent!')+'</label>').find('.tooltip').remove();
		        	}
		        	
	    	        if(data == '2'){
		    	    	Util.popAlertSuccess(tr('You\'ve reached your request limit!'), 300);
		    	        setTimeout(function () {
		    	        	$( ".pop-mess-succ" ).pdialog('close');
		    	        }, 2000); 	        	
	    	        }
	    	        
	    	        objCommon.unloading();
	    			return false;
		        },
		        dataType: 'html'
	    });			
	}
};




$(document).ready(function () {
    $(document).on('click','.photo_video .content ul li .share_del ol li a.icon_candy_photo',function(e){
        e.preventDefault();
        
        var _this = $(this);
        var parentBox = _this.parents('ol').parents('li');
        var item = _this.closest('.item');
        
        var defaultCandy = item.data('candy');
        var candySelectHtml = '';
        var candySelect = [1, 2, 3, 4, 5, 6, 7];
        for(i=1; i<=candySelect.length; i++) {
        	var candy = i*5;
        	if(Number(defaultCandy) == candy)
        		candySelectHtml += '<option selected="selected">'+candy+'</option>';
        	else
        		candySelectHtml += '<option>'+candy+'</option>';
        }
        
        var divWrap = $('<div class="boxCandy_num show"><div class="wrap_set_num"><p>Nhập số candy cho ảnh</p><div class="select_style"><select class="virtual_form">'+candySelectHtml+'</select><span class="txt_select"><span class="language_preference">'+defaultCandy+'</span></span> <span class="btn_combo_down"></span></div></div></div>');

        item.addClass('show-overlay');
        item.find('.share_del').hide();
        
        parentBox.append(divWrap);
        divWrap.show();
    });

	// Photo.scroll_photo_setting();
	$("#public_upload_photo").hide();
	$("#private_upload_photo").hide();
	$("#vault_upload_photo").hide();
	$(document).on('mouseenter', '.share_del li', function(){
		$(this).find('.tooltip').show();
	}).on('mouseleave', '.share_del li', function(){
		$(this).find('.tooltip').hide();
	});
	$(document).on('mouseenter', '.photo_vault a', function(){
		$(this).find('.tooltip').show();
	}).on('mouseleave', '.photo_vault a', function(){
		$(this).find('.tooltip').hide();
	});
	$(document).on('mouseenter', '.photo_private a', function(){
		$(this).find('.tooltip').show();
	}).on('mouseleave', '.photo_private a', function(){
		$(this).find('.tooltip').hide();
	});
	$('.link_photo_video a').click(function(e){
		objCommon.loading();
		$('.link_photo_video a.active').removeClass('active').parent().removeClass('active');
		$(this).addClass('active').parent().addClass('active');
		var url = $(this).data('href');
		$.ajax({
	        url: url,
	        success: function(data){
	        	objCommon.unloading();
	        	$('.photo_video > .content').html(data);
	        	applyColorBox();
	        	$('.photo_video .content .has-photo-wrap ul > li').boxResizeImg({
	        		parentWrap: '.has-photo-wrap',
	        		wRealBox: 160,
	        		hRealBox: 160
	            });
                $('.sticky_column').fixed_col_scroll.callbackFun();
	        },
	        dataType: 'html'
		});			
		e.preventDefault();
	});
	
	$(document).on('click', '.has-photo-wrap .pagging a', function(e){
		objCommon.loading();
		var url = $(this).attr('href');
		$.ajax({
	        url: url,
	        success: function(data){
	        	objCommon.unloading();
	        	var response = $('<div>'+data+'</div>');
	        	$('.photo_video > .content ul').append(response.find('ul > li'));
	        	
	        	var page = response.find('.pagging a');
	        	if(page.length > 0) {
	        		$('.pagging a').attr('href', page.attr('href'));
	        	} else {
	        		$('.pagging').remove();
	        	}
	        	applyColorBox();
	        	$('.photo_video .content ul > li').boxResizeImg({
	        		parentWrap: '.has-photo-wrap',
	        		wRealBox: 160,
	        		hRealBox: 160
	            });
                $('.sticky_column').fixed_col_scroll.callbackFun();
	        },
	        dataType: 'html'
		});			
		e.preventDefault();
	});
	
	$(document).on('change', '.boxCandy_num .virtual_form', function(){
		objCommon.loading();
		var self = $(this);
		var item = self.closest('.item');
        $.post( "/photo/setPrivatePhotoCandy", {photo_id: item.attr('id'), price: self.val()}, function( response ) {
        	if(response.result == '1') {
        		self.next().children().text(self.val());
        		item.data('candy', self.val()).find('.candy-no').text(self.val());
        	} else {
                Util.popAlertSuccess(response.message, 300);
    	        setTimeout(function () {
    	        	$( ".pop-mess-succ" ).pdialog('close');
    	        }, 2000);
        	}
        	
        	item.removeClass('show-overlay');
        	self.closest('.boxCandy_num').removeClass('show').addClass('hide');
            setTimeout(function(){
            	self.closest('.boxCandy_num').remove();
            },250);
	        
        	objCommon.unloading();
        }, 'json');
	});
	
	$('.photo_video').on('mouseenter', '.has-photo-wrap .item', function(){
		if(!$(this).hasClass('show-overlay'))
			$('.share_del', this).show();
	}).on('mouseleave', '.item', function(){
		$('.share_del', this).hide();
	});
	
	applyColorBox();
	applyResponseColorBox();
});
//end Nam LE



$(window).resize(function() {
	Util.imgSize($('.show-photo-detail-img'));
	$.colorbox.resize();
});

$(document).keypress(function(e) { 
    if (e.keyCode == 27) { 
    	$('.photo-view').removeClass('active_photo');
    }
});

function applyColorBox() {
	$('.group_gallery').colorbox({
		photo:true,
		slideshowAuto: false,
		rel:'group_gallery',
		fixed: true,
		scrolling: false,
		innerHeight: true,
		scalePhotos: true,
	    maxWidth: '100%',
		maxHeight: '95%',
		onOpen: function(target) {
			var el = $(target.el);
			if(el.hasClass('canSetMain')) {
				$('#colorbox').append('<span class="setAvatarWrap"><a class="setAvatar" href="javascript:Avatar.set_main_action('+el.closest('.item').attr('id')+', \''+el.find('img').attr('src')+'\');">'+tr('Set main')+'</a></span>');
			}
		},
		onClosed: function() {
			$('#colorbox .setAvatarWrap').remove();
		},
		onNext: function(target) {
			var el = $(target.el);
			$('#colorbox .setAvatarWrap').remove();
			if(el.hasClass('canSetMain')) {
				$('#colorbox').append('<span class="setAvatarWrap"><a class="setAvatar" href="javascript:Avatar.set_main_action('+el.closest('.item').attr('id')+', \''+el.find('img').attr('src')+'\');">'+tr('Set main')+'</a></span>');
			}
		},
		onPrev: function(target) {
			var el = $(target.el);
			if(el.hasClass('canSetMain')) {
				$('#colorbox').append('<span class="setAvatarWrap"><a class="setAvatar" href="javascript:Avatar.set_main_action('+el.closest('.item').attr('id')+', \''+el.find('img').attr('src')+'\');">'+tr('Set main')+'</a></span>');
			}
		}
	});
}
function applyResponseColorBox() {
	$('.group_response').colorbox({
		photo:true,
		slideshowAuto: false,
		fixed: true,
		scrolling: false,
		innerHeight: true,
		scalePhotos: true,
	    maxWidth: '100%',
		maxHeight: '95%'
	});
}
