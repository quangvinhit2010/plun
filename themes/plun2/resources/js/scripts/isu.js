$(document).ready(function(){
	objCommon.rISU();
	$(window).resize(function(){
		objCommon.rISU();			
	});
	isu.init();
});
var isu = {
	createPopup: null,
	init: function() {
		$('.pagging a').click(function(e){
			e.preventDefault();
			objCommon.loading();
			var url = $(this).attr('href');
			$.get(url, function(response){
				var source = $('<div>'+response+'</div>');
				var items = source.find('#isu_block > li');
				objCommon.insertHotBox_ISU(items, '#isu_block');
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
		$('.showPopup_create_isu').click(function(e){
			if($('#create-isu-holder').children().length > 0) {
				displayCreteIsu(isu.createPopup, true);
			} else {
				objCommon.loading();
				$.ajax({
	                type: "POST",
	                url: $(this).attr('href'),
	                success: function (response) {
	                	
	                	var responseWrap = $('#create-isu-holder').html(response);
	                	isu.createPopup = responseWrap.find('.popup_hotbox_event');
	                	
	                	$('.but_upload').click(function(){
	                		$('#FineUploader input').trigger('click');
	                	});

	                	$( "#when-from, #when-to" ).datetimepicker({dateFormat: 'dd-mm-yy', timeFormat: "hh:mm"});
	                	
	                	var selected = $('#select-country optgroup option:selected');
	                	$('#select-country optgroup option').each(function(){
	                		if($(this).val() == selected.val()) {
	                			$(this).prop('selected', true);
	                			selected.prop('selected', false);
	                			return false;
	                		}
	                	});
	                	
	                	displayCreteIsu(isu.createPopup, true);
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
	                url: "/isu/RemoveImg/",
	                data: {name: item.find('.filename').val(), path: item.find('.filepath').val()},
	            }).done(function () {
	            	item.fadeOut().remove();
	                FineUploader_FineUploader._netFilesUploadedOrQueued = FineUploader_FineUploader._netFilesUploadedOrQueued - 1;
	                objCommon.unloading();
	            });
	        }
			e.preventDefault();
		});
		$(document).on('click', '.isu-detail', function(e) {
			e.preventDefault();
			objCommon.loading();
			var url = $(this).attr('href');
			$.get(url, function(response){
				$( ".popup_isu_detail" ).html(response);
				
				if($('.pics_isu img').length > 0){
					$('.pics_isu img').load(function(){
						$( ".popup_isu_detail" ).pdialog({
							open: function(){
								objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
								objCommon.outSiteDialogCommon(this);
                                objCommon.no_title(this);
							},
							pclose: function() {
								$( ".popup_isu_forward" ).remove();
							},
							width: 700
						});

					}).error(function(){
						$( ".popup_isu_detail" ).pdialog({
							open: function(){
								objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
								objCommon.outSiteDialogCommon(this);
                                objCommon.no_title(this);
							},
							pclose: function() {
								$( ".popup_isu_forward" ).remove();
							},
							width: 700
						});

					});
				}else{
					$( ".popup_isu_detail" ).pdialog({
						open: function(){
							objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
							objCommon.outSiteDialogCommon(this);
                            objCommon.no_title(this);
						},
						pclose: function() {
							$( ".popup_isu_forward" ).remove();
						},
						width: 700
					});

				}
				objCommon.unloading();
			});
			e.preventDefault();
		});
		$(document).on('click', '.link_reply_forward .reply, .link_reply_forward .forward', function(e) {
			if(isGuest == 1){
	    		window.location.href  = '/site/login?msgLogin=true&redirect_url=' + window.location.pathname;
	    		return false;
	    	}
			var item = $("#forward-isu-form");
			var url = item.attr('action');
			if($(this).hasClass('reply')) {
				url = $('#reply-url').val();
				$('#MessageForm_to').val($(this).data('username')).prop('readonly', true);
			} else {
				$('#MessageForm_to').val('').prop('readonly', false);
			}
			$( ".popup_isu_forward" ).pdialog({
				open: function(){
					objCommon.outSiteDialogCommon(this);
                    objCommon.no_title(this);
				},
				pclose: function(){
					$('#MessageForm_body').val('');
				},
				width: 600,
				height: 280,
				buttons: [{
					text: tr("Cancel"),
					click: function() {
						$( this ).pdialog( "close" );
					}
				}, {
					text: tr("Send"),
					class: 'active',
					click: function() {
						objCommon.loading();
						$('.errorMessage').hide();
						var data = item.serialize();
						$this = $(this);
						$.ajax({
							type: 'POST',
							url: url,
						  	data: data,
							success:function(response){
								if (response.status != undefined && response.status == true){
									$this.pdialog( "close" );
								} else{
									$.each(response, function(i, items) {
										item.find("#MessageForm_"+i+"_em_").html(items[0]);
										item.find("#MessageForm_"+i+"_em_").css('display', 'block');
									});
								}
								objCommon.unloading();
						    },
						 	dataType:'json'
						 });
					}
				}],
			});

			e.preventDefault();
		});
	},
	submit: function() {
		
	},
	onError: function(id, name, errorReason) {
		alert(errorReason);
	},
	completeUpload: function(response, thumbnail) {
		if(response.success) {
			var base_url = 'http://' + img_webroot_url + '/' + response.filepath;
			var html = '<li><input class="filename" type="hidden" value="'+response.filename+'" name="Notes[image]"><input class="filepath" type="hidden" value="'+response.filepath+'" name="Notes[image_path]"><img width="100" height="100" src="'+base_url+'/thumb300x0/'+response.filename+'" align="absmiddle"> <a class="del_upload" href="#" title="Delete photo"></a></li>';
			$('.list_upload ul').append(html);
        }
        objCommon.unloading();
	},
}
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
function displayCreteIsu(createPopup, isCreate) {
	createPopup.pdialog({
		open: function(event, ui) {
			objCommon.outSiteDialogCommon(this);
			objCommon.no_title(this);
			tinyMCE.init({
			    selector: "textarea.tinymce",
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
			if(!isCreate)
				createPopup.remove();
		},
		width: 550,
		buttons: [{
			text: tr("Cancel"),
		    click: function() {
		    	$(this).pdialog('close');
		    }
		}, {
			text: tr("Send"),
			class: 'active',
		    click: function() {
		    	objCommon.loading();
	            var item = $(this).find('.isu-form');
	            $('#Notes_body').val(tinyMCE.activeEditor.getContent());
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
		                	$( ".popup_isu_detail" ).html(response.after_save);
		    				
		    				if($('.pics_isu img').length > 0){
		    					$('.pics_isu img').load(function(){
		    						$( ".popup_isu_detail" ).pdialog({
		    							open: function(){
		    								objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
		    								objCommon.outSiteDialogCommon(this);
                                            objCommon.no_title(this);
		    							},
		    							pclose: function() {
		    								$( ".popup_isu_forward" ).remove();
		    							},
		    							width: 700
		    						});

		    					}).error(function(){
		    						$( ".popup_isu_detail" ).pdialog({
		    							open: function(){
		    								objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
		    								objCommon.outSiteDialogCommon(this);
                                            objCommon.no_title(this);
		    							},
		    							pclose: function() {
		    								$( ".popup_isu_forward" ).remove();
		    							},
		    							width: 700
		    						});

		    					});
		    				}else{
		    					$( ".popup_isu_detail" ).pdialog({
		    						open: function(){
		    							objCommon.scorllContentPopup(".popup_isu_detail",'.poster_isu');
		    							objCommon.outSiteDialogCommon(this);
                                        objCommon.no_title(this);
		    						},
		    						pclose: function() {
		    							$( ".popup_isu_forward" ).remove();
		    						},
		    						width: 700
		    					});

		    				}
	                    } else {
	                        $.each(response, function (i, items) {
	                        	item.find("#Notes_" + i + "_em_").html(items[0]);
                                item.find("#Notes_" + i + "_em_").css('display', 'block');
	                        });
	                    }
	                },
	                dataType: 'json'
	            });
		    }
		}]
	});
}