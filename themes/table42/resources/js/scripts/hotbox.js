var hotbox = {
	currentId: '',
	init: function(){
		if($('.popup_detail_hotbox').children().length > 0 ) {
			objCommon.loading();
			currentId = $('.popup_detail_hotbox').find('.poster_hotbox').data('id');
			if($('.pics_hotbox img').length > 0){
				$('.pics_hotbox img').load(function(){
					$( ".popup_detail_hotbox" ).pdialog({
						open: function(){
							objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
							objCommon.outSiteDialogCommon(this);
						},
						width: 700
					});
					objCommon.no_title();
				}).error(function(){
					$( ".popup_detail_hotbox" ).pdialog({
						open: function(){
							objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
							objCommon.outSiteDialogCommon(this);
						},
						width: 700
					});
					objCommon.no_title();
				});
			}else{
				$( ".popup_detail_hotbox" ).pdialog({
					open: function(){
						objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
						objCommon.outSiteDialogCommon(this);
					},
					width: 700
				});
				objCommon.no_title();
			}
			objCommon.unloading();
		}
		
		$('.pagging a').click(function(e){
			e.preventDefault();
			objCommon.loading();
			var url = $(this).attr('href');
			$.get(url, function(response){
				var source = $('<div>'+response+'</div>');
				var items = source.find('.items_hot_box');
				objCommon.insertHotBox_ISU(items, '#hotbox_block');
				var pagging = $(source).find('.pagging');
				if(pagging.length == 0) {
					$('.pagging').remove();
				} else {
					$('.pagging a').attr('href', pagging.find('a').attr('href'));
				}
				objCommon.unloading();
			});
			return false;
		});
		$(document).on('click', '.hotbox-detail a', function(e) {
			e.preventDefault();
			objCommon.loading();
			var url = $(this).attr('href');
			currentId = '#'+$(this).closest('li').attr('id');
			$.get(url, function(response){
				$( ".popup_detail_hotbox" ).html(response);
				
				if($('.pics_hotbox img').length > 0){
					$('.pics_hotbox img').load(function(){
						$( ".popup_detail_hotbox" ).pdialog({
							open: function(){
								objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
								objCommon.outSiteDialogCommon(this);
							},
							width: 700
						});
						objCommon.no_title();
					}).error(function(){
						$( ".popup_detail_hotbox" ).pdialog({
							open: function(){
								objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
								objCommon.outSiteDialogCommon(this);
							},
							width: 700
						});
						objCommon.no_title();
					});
				}else{
					$( ".popup_detail_hotbox" ).pdialog({
						open: function(){
							objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
							objCommon.outSiteDialogCommon(this);
						},
						width: 700
					});
					objCommon.no_title();
				}
				objCommon.unloading();
			});
			return false;
		});
		$(document).on('click', '.like', function(e){
			var self = $(this);
			var type = self.data('type');
			$.ajax({
                type: 'POST',
                url: $(this).attr('href'),
                data: {id: $(this).data('id'), type: type},
                success: function (response) {
                    if (response.status != undefined && response.status == true) {
                    	if(type == 'comment') {
                    		self.prev().find('.comment-like-num').html(response.like_count);
                    		self.prev().find('.ismall-like-unactive').toggleClass('active');
                    		self.find('span').html(response.text);
                    	} else {
                    		$(currentId).find('.like-num').html(response.like_count);
                        	$('#like-num').html(response.like_count);
                        	$('.num_like a').toggleClass('active');
                        	$('.title .like').html(response.text);
                    	}
                    }
                },
                dataType: 'json'
            });
			e.preventDefault();
		});
		$(document).on('click', '#load_more_comment', function(e){
			var self = $(this);
			var next_page = self.data('next');
	        if (next_page != 'end') {
	            objCommon.loading();
	            $.ajax({
	                type: "POST",
	                url: self.attr('href') + '?page=' + next_page,
	                data: {id: self.data('id')},
	                success: function (response) {
	                	objCommon.unloading();
	                	var listComment = $('<div>'+response.after_save+'</div>').find('> li');
	                	$('.list_comment').prepend(listComment);
	                	
	                	self.data('next', Number(next_page)+1);
	                	
	                	if(next_page == response.pages)
	                		self.hide();
	                },
	                dataType: 'json'
	            });
	        }
	        e.preventDefault();
		});
		$(document).on('keydown', '#comment_area', function(e){
			if (e.which == 13 && !e.shiftKey) {
				objCommon.loading();
				
				var self = $(this);
				var content = self.val();
				
				if (content != "") {
					var id = self.data('id');
					var url = $('#replymsg-form').attr('action');
					self.prop('disabled', true);
					
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {content: content, id: id},
                        success: function (response) {
                        	if (response.status = 'true') {
                            	$(currentId).find('.comment-num').html(response.comment_count);
                            	$('#comment-num').html(response.comment_count);
                            	$('.list_comment').append(response.after_save);
                            	$('.wrap_scroll_popup').mCustomScrollbar("update");
                            	if($('#load_more_comment').css('display') != 'none') {
                            		$('.list_comment li').eq(0).remove();
                            	}
                            }
                            objCommon.unloading();
                            self.prop('disabled', false);
                        },
                        dataType: 'json'
                    });
                }
                $('textarea#comment_area').val('');
                e.preventDefault();
			}
		});
		$('.showPopup_create_hotbox').click(function(e){
			if($('#create-hotbox-holder').children().length > 0) {
				$('.popup_create_hotbox').pdialog({
    				open: function(event, ui) {
    					objCommon.no_title();
    					objCommon.outSiteDialogCommon(this);
    				},
    				width: 440,
    				height: 320
    			});
			} else {
				objCommon.loading();
				$.ajax({
	                type: "POST",
	                url: $(this).attr('href'),
	                success: function (response) {
	                	var responseWrap = $('#create-hotbox-holder').html(response);
	                	var chooseType = responseWrap.find('.popup_create_hotbox');
	                	var createPopup = responseWrap.find('.popup_hotbox_event');
	                	
	                	$( "#when-from, #when-to" ).datetimepicker({dateFormat: 'dd-mm-yy', timeFormat: "hh:mm"});
	                	var selected = $('#select-country optgroup option:selected');
	                	$('#select-country optgroup option').each(function(){
	                		if($(this).val() == selected.val()) {
	                			$(this).prop('selected', true);
	                			selected.prop('selected', false);
	                			return false;
	                		}
	                	});
	                	
	                	$('.but_upload').click(function(){
	                		$('#FineUploader input').trigger('click');
	                	});
	                	
	                	chooseType.pdialog({
	        				open: function(event, ui) {
	        					objCommon.outSiteDialogCommon(this);
	        					objCommon.no_title();
	        					chooseType.find('.showcreate_hotbox_event').click(function(e){
	        						displayCreteHotbox($(this).data('type'), createPopup, chooseType);
	        						e.preventDefault();
	        					});
	        				},
	        				width: 440,
	        				height: 320
	        			});
	                	objCommon.unloading();
	                }
	            });
			}
			e.preventDefault();
		});
		$(document).on('click', '.del_upload', function(e){
			var answer = confirm(tr('Are you sure you want to remove this photo?'));
			var item = $(this).closest('li');
	        if(answer) {
	        	objCommon.loading();
	            $.ajax({
	                type: "POST",
	                url: "/hotbox/deleteImage/" + item.find('input').val(),
	            }).done(function () {
	            	item.fadeOut().remove();
	                FineUploader_FineUploader._netFilesUploadedOrQueued = FineUploader_FineUploader._netFilesUploadedOrQueued - 1;
	                objCommon.unloading();
	            });
	        }
			e.preventDefault();
		});
		$(document).on('click', '.icon_edit', function(e) {
			objCommon.loading();
			var type = $(this).data('type');
			$.ajax({
                type: "POST",
                url: $(this).attr('href'),
            }).done(function (response) {
            	var responseWrap = $('#edit-hotbox-holder').html(response);
            	var createPopup = responseWrap.find('.popup_hotbox_event');
            	
            	$( "#when-from, #when-to" ).datetimepicker({dateFormat: 'dd-mm-yy', timeFormat: "hh:mm"});
            	
            	$('.but_upload').click(function(){
            		$('#FineUploader input').trigger('click');
            	});
            	
            	FineUploader_FineUploader._netFilesUploadedOrQueued = $('.list_upload ul > li').length;
            	
            	displayCreteHotbox(type, createPopup);
            	
                objCommon.unloading();
            });
			e.preventDefault();
		})
	},
	completeUpload: function(response, thumbnail) {
		if(response.success) {
			var base_url = 'http://' + img_webroot_url + '/' + response.filepath;
			var html = '<li><input type="hidden" value="'+response.image_id+'" name="HotboxForm[tmp_images][]" /><img width="100" height="100" src="'+base_url+'/thumb300x0/'+response.filename+'" align="absmiddle"> <a class="del_upload" href="#" title="Delete photo"></a></li>';
			$('.list_upload ul').append(html);
        }
        objCommon.unloading();
	},
	submit: function() {
		
	},
	onError: function(id, name, errorReason) {
		alert(errorReason);
	}
}
$(document).ready(function(){
	objCommon.rHotBox();
	$(window).resize(function(){
		objCommon.rHotBox();
	});
	hotbox.init();
});
function getStateRegister(){
	objCommon.loading();
	$('#select-state').val('').hide();
	$('#select-city').val('').hide();
	$('#select-district').val('').hide();
	var data = { country_id: $('#select-country option:selected').val() };
	$.ajax({
        type: "GET",
        data: data,
        url: '/location/Getstatelist',
        success: function(data) {
    		if(data != ''){
    			$('#select-state').html(data).show();
    		}
    		objCommon.unloading();
        },
        dataType: 'html'
    });
}
function getCityRegister(){
	objCommon.loading();
	$('#select-city').val('').hide();
	$('#select-district').val('').hide();	
	var data = { state_id: $('#select-state option:selected').val() };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/Getcitylist',
        success: function(data) {
        	if(data != ''){
    			$('#select-city').html(data).show();
    		}
        	objCommon.unloading();
        },
        dataType: 'html'
    });	
}
function getDistrictRegister(){
	objCommon.loading();
	$('#select-district').val('').hide();
	var data = { city_id: $('#select-city option:selected').val() };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/Getdistrictlist',
        success: function(data) {
        	if(data != ''){
    			$('#select-district').html(data).show();
    		}
        	objCommon.unloading();
        },
        dataType: 'html'
    });	
}

function displayCreteHotbox(type, createPopup, chooseType) {
	$('#HotboxForm_type').val(type);
	if(type == 2) {
		createPopup.find('.where').hide();
		createPopup.find('.when').hide();
	} else {
		createPopup.find('.where').show();
		createPopup.find('.when').show();
	}
	createPopup.pdialog({
		open: function(event, ui) {
			objCommon.outSiteDialogCommon(this);
			objCommon.no_title();
			tinyMCE.init({
			    selector: "textarea",
			    mode : "textareas",
			    theme : "advanced",
			    theme_advanced_toolbar_location : "top",
			    theme_advanced_buttons1 : "bold,italic,underline",
			    theme_advanced_buttons2 : "",
			    theme_advanced_buttons3 : "",
			    theme_advanced_statusbar_location : "none",
			    plugins : "paste",
			    paste_text_sticky : true,
			    setup : function(ed) {
			        ed.onInit.add(function(ed) {
			          ed.pasteAsPlainText = true;
			        });
			    }
			});
		},
		pclose: function() {
			// createPopup.remove();
		},
		width: 550,
		buttons: [{
			text: tr("Cancel"),
		    click: function() {
		    	$(this).pdialog('close');
		    	if(typeof chooseType !== 'undefined')
		    		chooseType.pdialog('close');
		    }
		}, {
			text: tr("Send"),
			class: 'active',
		    click: function() {
		    	objCommon.loading();
	            var item = $(this).find('.hotbox-form');
	            if(typeof chooseType !== 'undefined')
	            	$('#HotboxForm_body').val(tinyMCE.activeEditor.getContent());
	            else
	            	$('#Hotbox_body').val(tinyMCE.activeEditor.getContent());
	            
	            var data = item.serialize();
	            $.ajax({
	                type: 'POST',
	                url: item.attr('action'),
	                data: data,
	                success: function (response) {
	                	objCommon.unloading();
	                    if (response.status != undefined && response.status == true) {
		                	createPopup.pdialog('close');
		                	if(typeof chooseType !== 'undefined')
		                		chooseType.dialog('close');
		                	tinyMCE.activeEditor.setContent('');
	                    	$( ".popup_detail_hotbox" ).html(response.after_save);
		                	currentId = $('.popup_detail_hotbox').find('.poster_hotbox').data('id');
	                    	if($('.pics_hotbox img').length > 0){
	            				$('.pics_hotbox img').load(function(){
	            					$( ".popup_detail_hotbox" ).pdialog({
	            						open: function(){
	            							objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
	            							objCommon.outSiteDialogCommon(this);
	            						},
	            						width: 700
	            					});
	            					objCommon.no_title();
	            				}).error(function(){
	            					$( ".popup_detail_hotbox" ).pdialog({
	            						open: function(){
	            							objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
	            							objCommon.outSiteDialogCommon(this);
	            						},
	            						width: 700
	            					});
	            					objCommon.no_title();
	            				});
	            			}else{
	            				$( ".popup_detail_hotbox" ).pdialog({
	            					open: function(){
	            						objCommon.scorllContentPopup(".popup_detail_hotbox",'.poster_hotbox');
            							objCommon.outSiteDialogCommon(this);
            						},
            						width: 700
	            				});
	            				objCommon.no_title();
	            			}
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
	                    }
	                },
	                dataType: 'json'
	            });
		    }
		}]
	});
}