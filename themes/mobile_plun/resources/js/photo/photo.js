var Photo = {
	delete_photo: function (item) {
        if(item){
        	var confirm = '<div class="del_photo_avatar">' + 
                        	'<p>' + tr('Delete this Photo?') + '</p>' + 
                            '<div class="but_del">' + 
                        		'<a class="but btn-gray yes" rel="Yes" href="javascript:void(0);">' + tr('Yes') + '</a>' + 
                            	'<a class="but btn-gray no" rel="No" href="javascript:void(0);">' + tr('No') + '</a>' + 
                            '</div>' +
                        '</div>'; 
        		
        	
        	var this_photo = $(item).closest('.photo_detail');
        	this_photo.append(confirm);
        	var photo_id = this_photo.attr('id');
        	this_photo.find('.btn-gray').on( "click", function(e) {
        		e.preventDefault();
        		if($(this).attr('rel') == 'Yes'){
        			e.preventDefault();
        			var parent_list = $('.list_photo_public');
        			var type = parent_list.attr('type');
        			$(this).loading();
        			$.ajax({
        				type: "POST",
        				data: {photo_id: photo_id, type: type},
        				url: '/photo/delete',
	 			        success: function(data){
	 			    		if($(data == 0)){
	 			    			this_photo.fadeOut().remove();
	 			    			$('#container').show();
	 			    			parent_list.find('#'+photo_id).remove();
	 			    			$(this).unloading();
	 			    		} 
	 			    		
	 			        },
	 			        dataType: 'html'
        			}); 
        		} else if($(this).attr('rel') == 'No'){
        			e.preventDefault();
        			this_photo.find('.del_photo_avatar').hide();
        		}
        	});
        } else {
        	return false;
        }
    },
    upload_photo_open: function(item, id){
    	if(Util.isSupportUpload() == true){
    		$('#' +id+' input').trigger('click');
    	} else {
    		alert(tr('Your device does not support'));
    	}
    },
    more_upload_photo_open: function(item, id){
    	alert(1);
    	//$(item).closest('.block-photo-more').find('.photos-upload').find('#' +id+' input').trigger('click');
    },
    photoBeforeUpload: function (){
    	$(this).loading();
    },
    prePhotoAfterUpload: function (json,id){
    	if(!json.error){
    		var item = $('#' + id);
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
    		$(item).prepend('<li id="'+ json.fileid +'">'
	    				+ '<a onclick="Photo.viewPhotoDetail(this);" lis_me="true" limg="/' + json.filepath + '/detail1600x900/' + json.filename +'" lcaption="" href="javascript:void(0);" title="">'
	    				+ '<img align="absmiddle" alt="'+ json.filename +'" src="/' + json.filepath + '/thumb275x275/' + json.filename +'">'
	    				+ '</a>'
	    				+ '<a href="javascript:void(0);" onclick="javascript:Photo.delete_photo(this);" class="del"></a>'
    				+ '</li>');
    	}
    },
    photoAfterUpload: function (json, id){
    	Photo.prePhotoAfterUpload(json,id);
    	if(json.success){
    		$(this).unloading();
    		location.reload(); 
    		
    		
			/*$("#"+id+"-upload ul.item-list-1").prepend('<li id="'+ json.fileid +'" class="item_1">'
						+ '<a href="#" title="" class="ava">' 
						+ '<img src="/' + json.filepath + '/thumb275x275/' + json.filename +'" alt="" border="" width="160" height="160">'  
						+ '<span class="ava-bg"></span></a>' 
					+ '<div class="info">'
						+ '<ul>'
							+ '<li>'
								+ '<div class="input-wrap input-textarea">'
								+ '<textarea id="caption" placeholder="' + tr('Add Caption') + '..."></textarea>'
								+ '</div>'
							+ '</li>'
							+ '<li>'
								+ '<div class="buttons">'
								+ '<button class="btn btn-violet" onclick="javascript:Photo.photoSave(this, \'' + id + '\');">' + tr('Save') + '</button>'
								+ '<button class="btn btn-white" onclick="javascript:Photo.photoDiscard(this, \'' + id + '\');">' + tr('Discard') + '</button>'
							+ '</li>'
						+ '</ul>'
					+ '</div>'
					+ '</li>'
			);
			
			
			Photo.open_close_dialog(true, id);
			//Photo.upload_scroll();
			if($('#'+id+'-upload ul.item-list-1 .item_1').size() > 1){
				$("#all-"+id).show();
			}*/
		} 
    },
    photoOnError: function(errorReason){
    	$(this).unloading();
    	alert(errorReason);
    	/*Util.popAlertFail(errorReason, 400);
		setTimeout(function () {
			$( ".pop-mess-fail" ).pdialog('close');
		}, 6000);*/
    	//alert(errorReason);
    	return false;
    },
    photoDiscard: function(item, list){
    	var answer = confirm (tr("Are you sure you want to remove this photo?"));
		if (answer){
			var last_photo = $('#' + list + '-upload ul.item-list-1 .item_1').size();  
		    var id = $(item).closest('li.item_1').attr('id');
		    var parent_item = $('#' + list + '-active ul.item-list');
		    $(this).loading();
			$.ajax({
				type: "POST",
				url: "/photo/discard",
				data: { "id" : id },
				}).done(function() {
					$(this).unloading();
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
	    	$(this).loading();
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
					$(this).unloading();
					
					
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
    		$(this).loading();
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
	    		$(this).unloading();
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
	    		$(this).loading();
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
		    		$(this).unloading();
		    	});
	    	}
		}
		
    },
    show_more: function(id){
    	$(this).loading();
    	var item = $('#' + id);
		var page = item.attr('page');
		if(page != 'end'){
			var url = window.location.href + '&page=' + page;
			$.ajax({
				type: "GET",
				url: url,
				success: function(data){
					
					if(data == 'end'){
						//item.find('li:last').hide();
						$('.block_loading').hide();
					} else {
						//get next_page
						var next_page =   $(data).filter('#next_page').attr('page');
						if(next_page == 'end'){
							$('.block_loading').hide();
							//item.find('li:last').hide();
						} else {
							item.attr('page',next_page);
						}
						
						item.append(data);
						//$(data).insertBefore(item.find('li:last').prev("li"));
						
						
						/*var dulicateArr = [];
						$(item.find('li')).each(function (index, value) {
							var currentID = $(value).attr('id');
							if(currentID > 0){
								if($.inArray(currentID, dulicateArr) == -1){
									dulicateArr.push(currentID);   
								} else {
									$(this).remove();
								}
							}
						});*/
					}
					$(this).unloading();
				},
				dataType: 'html'
			});
		} else {
			//item.find('li:last').hide();
			$('.block_loading').hide();
			$(this).unloading();
		}
    },
    show_more_your_photo: function(id){
    	$(this).loading();
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
    				$(this).unloading();
    			},
    			dataType: 'html'
    		});
    	} else {
    		item.find('li:last').hide();
    		$(this).unloading();
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
		$(this).loading();
		var type = $('.list_photo_public').attr('type');
		var it_my_photo = $(curr_photo).attr('lis_me');
		var share_photo = $(curr_photo).attr('share_photo');
		var set_main_html = (type == 1) ? '<a class="setmain" href="javascript:Avatar.set_main_action('+$(curr_photo).attr('data-photo-id')+');"><span></span> '+ tr('Set main') + '</a>' : '';
		var delete_html =  (it_my_photo != undefined) ?  '<a class="delete" href="javascript:void(0);" onclick="javascript:Photo.delete_photo(this);"><span></span> '+ tr('Delete') + '</a>' : '';
		var share_html =  (share_photo != undefined) ?  '<a class="share_photo" href="/photo/sharephotoview?photoid='+$(curr_photo).attr('data-photo-id')+'"><span></span> '+ tr('Share Photo') + '</a>' : '';
		
		var photo_detail_html = '<div class="photo_detail" id="'+$(curr_photo).attr('data-photo-id')+'">' +
                    	'<div class="but_close"><a href="javascript:Photo.close_photo_detail(this);"></a></div>' +
                    	'<div class="pics"><img id="show-photo-detail-img" src="'+$(curr_photo).attr('limg')+'" align="absmiddle"></div>' +
                        '<div class="desc_pics"></div>' +
                        '<div class="set_main">' +
                        	set_main_html +
                        	share_html + 
                        	delete_html +
                        '</div>' +
                    '</div>';
		
		if($('.photo_detail').length == 0){
			$('#col2').prepend(photo_detail_html);
			
			$('#show-photo-detail-img').load(function(){
				$('#wrapper_header').hide();
				$('.scroll_info').css('margin', 0);
				$('#block_data_center').css('margin-top', '0');
				$('.photo_detail').css('margin-top', '0');
				$('.photo_detail').css('height',$(window).height());
				$('#container').hide();
				$('#menu_footer').hide();
				$(this).unloading();
				Util.imgSize($(this));
				
			});
		} else {
			$('.pics img').attr('src', $(curr_photo).attr('limg'));
		}
		
		
		if($(curr_photo).attr('lcaption').length > 0){
			$('.desc_pics').html($(curr_photo).attr('lcaption'));
		} else {
			$('.desc_pics').hide();
			$('.desc_pics').html('');
		}
		
		
		/*$('.show-photo-detail-img').replaceWith('<img class="show-photo-detail-img" border="0" alt="" src="'+$(curr_photo).attr('limg')+'">');
		//$('.overlay').show();
		//alert($('.overlay').html());
		$('.show-photo-detail-img').hide();
		$('.show-photo-detail-img').load(function(){
			imgSize($(this));
			
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
			$("body").unloading();
		});
		
		if($('.photo-view').attr('class')){
			$('.photo-view').addClass('active_photo');
		}*/

	},
	viewYourPhotoDetail: function(curr_photo){
		$(this).loading();
		var type = $('.list_photo_public').attr('type');
		var photo_detail_html = '<div class="photo_detail" id="'+$(curr_photo).attr('data-photo-id')+'">' +
                    	'<div class="but_close"><a href="javascript:Photo.close_photo_detail(this);"></a></div>' +
                    	'<div class="pics"><img id="show-photo-detail-img" src="'+$(curr_photo).attr('limg')+'" align="absmiddle"></div>' +
                        '<div class="desc_pics"></div>' +
                    '</div>';
		
		if($('.photo_detail').length == 0){
			$('#col2').prepend(photo_detail_html);
		} else {
			$('.pics img').attr('src', $(curr_photo).attr('limg'));
		}
		
		
		if($(curr_photo).attr('lcaption').length > 0){
			$('.desc_pics').html($(curr_photo).attr('lcaption'));
		} else {
			$('.desc_pics').hide();
			$('.desc_pics').html('');
		}
		
		
		$('#show-photo-detail-img').load(function(){
			$('#wrapper_header').hide();
			$('.scroll_info').css('margin', 0);
			$('#block_data_center').css('margin-top', '0');
			$('.photo_detail').css('margin-top', '0');
			$('.photo_detail').css('height',$(window).height());
			$('#container').hide();
			$('#menu_footer').hide();
			$(this).unloading();
			Util.imgSize($(this));
			
		});
	},	
	request_view_photo: function(photo_id){
		if(Activation.isActive == false){
			Activation.show();
			return false;
		}
		$(document).loading();
	    var data = {
	    		photo_id: photo_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/photo/requestViewPhoto',
	        success: function(data){
	    		if($(data == '1')){
	    			var html = '<div class="alert">'+ tr('Request sent!') +'</div>';
	    			$('#photo-' + photo_id).find('.mask').remove()
	    			$('#photo-' + photo_id).append(html);
	    			$(document).unloading();
	    		}
	        },
	        dataType: 'html'
	    });	
	},
	accept_photo_request: function(e, request_id, cls){
		e.preventDefault();
		$(document).loading();
	    var data = {
	    		request_id: request_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/photo/acceptRequest',
	        success: function(data){
	    		if($(data == '1')){
	    			$('.request-photo-' + request_id).fadeOut().remove();
	    			$('.list_thumb_photo li.list-request-photo-' + request_id).fadeOut().remove();
	    			Photo.after_action(cls);
	    			$(document).unloading();
	    			return false;
	    		}
	        },
	        dataType: 'html'
	    });	
	},
	accept_all_photo_request: function(){
		var cls = $('.list_photo_setting').attr('id');
		if(cls){
			var list_request = $('.photo-setting-request-list').find('li.'+cls);
			var popup_request = $('.list_photo_setting');
			var data = [];
			$.each(popup_request.find('li.item_1'), function(key, value){
	    		var photo_id = $(value).find('img').attr('data-pid');
	    	    if(photo_id > 0){
	    	    	data.push({photo_id: photo_id});
	    	    }
		    });
			if(data){
				$(document).loading();
				 $.ajax({
				        type: "POST",
				        data: { "data" : data},
				        url: '/photo/acceptAllRequest',
				        success: function(data){
				    		if($(data == '1')){
				    			$(popup_request).empty();
				    			$(list_request).empty();
				    			Photo.after_action(cls);
				    			$(document).unloading();
				    			return false;
				    		}
				        },
				        dataType: 'html'
			    });
				
			}
		}
		
	},
	decline_all_photo_request: function(){
		var cls = $('.list_photo_setting').attr('id');
		if(cls){
			var list_request = $('.photo-setting-request-list').find('li.'+cls);
			var popup_request = $('.list_photo_setting');
			var data = [];
			$.each(popup_request.find('li.item_1'), function(key, value){
	    		var photo_id = $(value).find('img').attr('data-pid');
	    	    if(photo_id > 0){
	    	    	data.push({photo_id: photo_id});
	    	    }
		    });
			if(data){
				$(document).loading();
				 $.ajax({
				        type: "POST",
				        data: { "data" : data},
				        url: '/photo/declineAllRequest',
				        success: function(data){
				    		if($(data == '1')){
				    			$(popup_request).empty();
				    			$(list_request).empty();
				    			Photo.after_action(cls);
				    			$(document).unloading();
				    			return false;
				    		}
				        },
				        dataType: 'html'
			    });
				
			}
		}
	},
	decline_photo_request: function(e, request_id, cls){
		e.preventDefault();
		$(document).loading();
	    var data = {
	    		request_id: request_id
	    };	
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/photo/declineRequest',
	        success: function(data){
	    		if($(data == '1')){
	    			$('.request-photo-' + request_id).fadeOut().remove();
	    			$('.list_thumb_photo li.list-request-photo-' + request_id).fadeOut().remove();
	    			Photo.after_action(cls);
	    			$(document).unloading();
	    			return false;
	    		}
	        },
	        dataType: 'html'
	    });	
	},
	after_action: function(cls){
		var last_photo = $('.all_photo_accept ul.list_photo_setting .item_1').size();
		if(last_photo == 0){
			Photo.close_popup_request();
		}
		
		var last_request = parseInt($('li.'+cls+' ul.list_thumb_photo').find('li').size()); 
		if(last_request == 0){
			$('li.'+ cls).fadeOut().remove();
		}
		
		var list_request = $('.list_mobile_request');
		var total_request = parseInt($(list_request).find('li.item').size());
		if(total_request == 0){
			$('.list_mobile_request').find('.date').hide();
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
	open_request_popup: function(cls, _this){
		$( ".all_photo_accept .list_photo_setting" ).html('');
		var html = $(_this).closest('.photo-setting-request-list').find('li.'+ cls).find('ul.list_thumb_photo li');
		var request_data_html = '';
		$.each(html, function(key, liobj){
			var rid = $(liobj).find('img').attr('data-rid');
			var src = $(liobj).find('img').attr('src');
			if(src){
				request_data_html += '<li class="item_1 request-photo-'+rid+'">'
						+ '<p><img border="" data-pid="'+ rid +'" alt="" src="'+ src +'" width="140" height="140" align="absmiddle"></p>'
			                +'<div class="accept_decline">'
				                +'<a class="accept active" href="javascript:void(0);" onclick="Photo.accept_photo_request(event, '+rid+', \''+cls+'\');">'+ tr('Accept')+'</a>'
				                +'<a class="decline" href="javascript:void(0);" onclick="Photo.decline_photo_request(event, '+rid+', \''+cls+'\');">' + tr('Decline')+'</a>'
			                +'</div>'
	                + '</li>';
			}
			if(key == 0){
				$(".all_accept_decline").find('.right').hide();
			} else {
				$(".all_accept_decline").find('.right').show();
			}
	    });
		$( ".all_photo_accept .list_photo_setting" ).attr('id', cls);
		$( ".all_photo_accept .list_photo_setting" ).html(request_data_html);
		//sprScroll('.list_photo_setting');
		$('.list_mobile_request').hide();
		$('#request_popup').show();
	},
	update_notice_limit: function(number){
		
	},
	close_photo_detail: function(photo){
		
		if($('.photo_detail').length > 0){
			$('.photo_detail').remove();
			$('#wrapper_header').show();
			$('.scroll_info').css('margin-top', 32);
			$('#block_data_center').css('margin-top', 'inherit');
			$('.photo_detail').css('margin-top', 0).css('height',$(window).height());
			$('#container').show();
			$('#menu_footer').show();
			
		}
	},
	close_popup_request: function(){
		if($('#request_popup').length > 0){
			$('#request_popup').hide();
			$('.list_mobile_request').show();
		}
	},
	viewmorephoto: function(alias){
		var offset = parseInt($('#vault_photo_showmore_offset').val());
		var limit	=	parseInt($('#vault_photo_showmore_limit').val());
		var data	=	{
				offset:	offset
		};
		$(document).loading();
		 $.ajax({
		        type: "GET",
		        data: data,
		        url: '/u/' + alias + '/viewphotomore',
		        success: function(data){
		        	if(!data.show_more){
		        		$('.block_loading').remove();
		        	}
		        	$('.vault_photo_user ul').append(data.html);
		        	offset	=	offset + limit;
		        	$('#vault_photo_showmore_offset').val(offset);
		        	$(document).unloading();
		        },
		        dataType: 'json'
	    });		
	},
	photorequest_showmore: function(){
		$(document).loading();
		var data	=	{
				offset: $('#photo_request_offset').val()
		};
		 $.ajax({
		        type: "POST",
		        data: data,
		        url: '/photo/PhotoRequestShowMore',
		        success: function(data){
		        	if(data !=''){
		    			$('.photorequest-list').append(data);		    			
		    			var next_offset	=	parseInt($('#photo_request_offset').val()) + parseInt($('#photo_request_limit').val());
		    			$('#photo_request_offset').val(next_offset);
		        	}else{
		        		$('.show-more-photorequest').hide();
		        	}
	    			$(document).unloading();
	    			return false;
		        },
		        dataType: 'html'
	    });		
	},
	photoUpdateReadStatus: function(ids){
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
	},
	sendSharePhoto: function(alias){
		var userid	=	$('#txtusername_sharephoto').val();
		
		$(document).loading();
		if(userid == ''){
			$(document).unloading();
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
		        	$( ".photo_share_one" ).pdialog('close');	
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
		        	
		        	window.location = '/u/' + alias + '/photo?type=3';
		        	
	    			$(document).unloading();
	    			
	    			return false;
		        },
		        dataType: 'html'
	    });		
	},
	requestAgain: function(photoid, userid){
		$(document).loading();
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
		        		$('#photo-' + photoid + ' div').text(tr('Request sent!'));
		        		$('.request_again_' + photoid).hide();
		        	}
		        	
	    	        if(data == '2'){
		    	    	Util.popAlertSuccess(tr('You\'ve reached your request limit!'), 300);
		    	        setTimeout(function () {
		    	         $( ".pop-mess-succ" ).pdialog('close');
		    	        }, 2000);	    	        	
	    	        }
	    	        
	    			$(document).unloading();
	    			return false;
		        },
		        dataType: 'html'
	    });			
	}	
};




$(document).ready(function () {
	//Photo.scroll_photo_setting();
	$("#public_upload_photo").hide();
	$("#private_upload_photo").hide();
	$("#vault_upload_photo").hide();
});
//end Nam LE



$(window).resize(function() {
	Util.imgSize($('.pics img'));
});

$(document).keypress(function(e) { 
    if (e.keyCode == 27) { 
    	$('.photo-view').removeClass('active_photo');
    }
});

window.addEventListener("orientationchange", function() {
	// Announce the new orientation number
	//alert(window.orientation);
	if($('.photo_detail')){
		$('.photo_detail').css('margin-top', 0).css('height',$(window).height());
	}
}, false);


window.addEventListener("resize", function() {
	// Get screen size (inner/outerWidth, inner/outerHeight)
	if($('.photo_detail')){
		$('.photo_detail').css('margin-top', 0).css('height',$(window).height());
	}
	
}, false);

