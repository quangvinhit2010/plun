$(function(){
	Home.init();
	Home.actionPhoto();	
});
var arrFiles = [];
var jcrop_api;
const LIMIT_UPLOAD = 1;

var Home = {
	init: function(){				
	},
	/**slide***/
	actionPhoto: function(){
		$(document).on('click','.wrap_edit_photo .addLink',function(){
            var _li = $(this).parents('li');
			var _fid = _li.attr('data-fid');
            var _html = _li.parent().find('.boxAddLink').html();
//            $( '.boxAddLink' ).dialog( "destroy" );
            if ($('.boxAddLink').hasClass('ui-dialog-content')) {
            	$('.ui-dialog').empty().remove();
            }
            $('<div class="boxAddLink">'+_html+'</div>').dialog({
				title: 'Add Link',				
				buttons: {				
					"Cancel": {
						text: 'Cancel',
						click: function() {
							$( this ).dialog( "destroy" );
		                },
					},
					"Save": {
						text: 'Save',
						click: function() {
							if (window.FormData) {
							    formdata = new FormData();
							}
							formdata.append("id", _fid);
							formdata.append("photo[link]", $('.boxAddLink.ui-dialog-content .link').val());
							$.ajax({
								url: $('#upload_url').attr('url-update'),
								data: formdata,//$('#frm-addprofile').serialize(),
								dataType: 'html',
								type: "POST",
								processData: false,
								contentType: false,
								dataType: 'JSON',
								success: function (res) {
									console.log(res);
								}
							});
							$( this ).dialog( "destroy" );
						},
					},
				}
			});
        });
		
		$(document).on('click','.wrap_edit_photo .delPhoto',function(){
			Home.loading();
			var _li = $(this).parents('li');
			var _fid = _li.attr('data-fid');
			if(_fid.length > 0){
				$.ajax({
					url: $('#upload_url').attr('url-delphoto'),
					data: {id:_fid},
					type: "POST",
					success: function (res) {
						console.log(_li);
						_li.find('.wrapbox_img').html('<a href="javascript:void(0);"></a>');
						Home.unloading();
					}
				});
			}
		});
		
		$(document.body).on('dblclick', '#masonry_box li a', function(event) {
			Home.loading();
			$('.popUpload').attr('data-col', $(this).parents('li').attr('data-col'));
			$('.popUpload').attr('data-row', $(this).parents('li').attr('data-row'));
			$('.popUpload').attr('data-position', $(this).parents('li').attr('data-position'));
			$('.popUpload').attr('data-index', $(this).parents('li').index());
			$('#uploadImage').trigger( 'click' );
		});
    },
	readURL: function(input){
		Home.loading();
		if (input.files && input.files[0]) {
			var i = 0, len = input.files.length, img, reader, file;					
			var canUpload = true;	
			arrFiles = [];
			for (i=0 ; i < len; i++ ) {
				var idx = arrFiles.length + 1;
				if(idx <= LIMIT_UPLOAD){
					file = input.files[i];
					if (!!file.type.match(/image.*/)) {
						if ( window.FileReader ) {
							reader = new FileReader();
							reader.onloadend = function (e) { 
								var html = '<a href="javascript:void(0);"><img class="uploadPreview" src="' + e.target.result +'" align="absmiddle" style="max-width:700px;max-height:700px;"></a>';
								$('.popUpload').html(html);
								var pic_real_width, pic_real_height;
								var size = Home.typeUploadTWidthHeigh($('.popUpload').attr('data-col'), $('.popUpload').attr('data-row'));
								$('.uploadPreview').load(function() {
									pic_real_width = this.naturalWidth;
									pic_real_height = this.naturalHeight;
									$('.uploadPreview').Jcrop({
										allowSelect:     true,
										allowResize:     true,
										aspectRatio: size[0]/size[1],
										bgFade:     true,
										bgOpacity: .2,
										boxWidth: 700,
										boxHeight: 700,
										trueSize: [pic_real_width, pic_real_height],										
										onSelect: function(c) {
											$('#x').val(c.x);
											$('#y').val(c.y);
											$('#w').val(c.w);
											$('#h').val(c.h);
										}
									},function(){
										jcrop_api = this;
										jcrop_api.setSelect([ 0, 0, size[0], size[1] ]);
									});
								});
								
								$('.popUpload').dialog({
									title: 'Send a message',
									open: function(event, ui) {
										
									},
									resizable: true,
									position: 'top',
									draggable: true,
									autoOpen: true,
									center: true,
									width: 800,
									modal: true,
									buttons: {				
										"Cancel": {
											text: 'Cancel',
											click: function() {
												$( this ).dialog( "close" );
							                },
										},
										"Crop": {
											text: 'Crop',
											click: function() {
												var _popup = $(this);
												if(arrFiles.length > 0){
													if (window.FormData) {
													    formdata = new FormData();
													}
													$.each(arrFiles, function( index, value ) {
														formdata.append("images", value);
													})
													formdata.append("crop[x]", $('#x').val());
													formdata.append("crop[y]", $('#y').val());
													formdata.append("crop[w]", $('#w').val());
													formdata.append("crop[h]", $('#h').val());
													
													formdata.append("type[col]", $('.popUpload').attr('data-col'));
													formdata.append("type[row]", $('.popUpload').attr('data-row'));
													
													formdata.append("position", $('.popUpload').attr('data-position'));
													
													$.ajax({
														url: $('#upload_url').attr('url-upload'),
														data: formdata,//$('#frm-addprofile').serialize(),
														dataType: 'html',
														type: "POST",
														processData: false,
														contentType: false,
														dataType: 'JSON',
														success: function (res) {
															var _li_index = $('.popUpload').attr('data-index');
															var _html = '<img src="/'+res.file+'"><div class="wrap_edit_photo"><span class="delPhoto">Del</span><span class="addLink">Link</span>';
															_html += '<div class="boxAddLink"><input class="link" type="text" /></div></div>';
															$('#masonry_box li:eq( '+_li_index+' )').attr('data-fid', res.id);
															$('#masonry_box li:eq( '+_li_index+' ) .wrapbox_img').html(_html);
															_popup.dialog( "close" );															
														}
													});
												}
							                }
										}
									}
								});
								$('.popUpload').dialog('open');
							};
							reader.readAsDataURL(file);
						}
						arrFiles.push(file);        				
					}
				}else{
					var canUpload = false;
				}
			}
			if(canUpload == false){
				alert('Giới hạn tải ảnh của bạn vượt quá '+LIMIT_UPLOAD+' tấm!');
			}
			Home.unloading();
		}
	},
	typeUploadTWidthHeigh: function(col, row){
		var selectW = 490, selectH = 490;
		if(col == 1 && row == 1){
			selectW = 245;
			selectH = 245;
		}else if(col == 1 && row == 2){
			selectW = 245;
			selectH = 490;
		}else if(col == 2 && row == 1){
			selectW = 490;
			selectH = 245;
		}else if(col == 2 && row == 2){
			selectW = 490;
			selectH = 490;
		}
		return [selectW, selectH];
	},
	order: function(){
		Home.loading();
		var arrPos = [];
		var arrOrder = [];
		$.each($('#masonry_box li'), function(i, item) {
			arrPos[i] = $(item).attr('data-position');
			arrOrder[i] = $(item).attr('data-order');
		});
		if(arrOrder.length > 0 && arrPos.length > 0){
			$.ajax({
				url: $('#upload_url').attr('url-order'),
				data: {pos:arrPos.join( "|" ), order: arrOrder.join( "|" )},
				type: "POST",
				dataType: 'JSON',
				success: function (res) {
					Home.unloading();
				}
			});
		}
	},
	loading: function(){
		$('#loading-list').show();
	},
	unloading: function(){
		$('#loading-list').hide();
	},
	/***slide***/
	loadPhotos: function(type, files){		
		var html_li = '';
		if(files[type].length > 0){
			$.each(files[type], function(i, item) {
				html_li += '<li><img width="100" src="/'+item.file+'" /><a class="button del" data-type="'+type+'" data-offset="'+i+'" href="javascript:void(0);">Del</a></li>';
			});
		}
		$('.listPhotos').html(html_li);
	},
	
}

