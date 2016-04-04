var arrFiles = [];
const LIMIT_UPLOAD = 5;
$(function(){
	Register.init();
});
window.onload = function(){
	 if(document.location.href.search('#') > -1){
		 var urlGetParam = document.location.hash.split('#')[1];
		 if(urlGetParam=='thamgia'){
			 $('.menu .thamgia a').trigger( 'click' ); return false;
		 }
		 if(urlGetParam=='thele'){
			 $('.menu .thele a').trigger( 'click' ); return false;
		 }
		 if(urlGetParam=='hotro'){
			 $('.menu .hotro a').trigger( 'click' ); return false;
		 }
	 }
}


var Register = {
	init: function(){		
		$('.menu ul li a').attr('class', '');
		$('.menu ul li a').live('click', function(){
			$('.menu ul li a').attr('class', '');
			$(this).attr('class', 'active');
			return false;
		});
		$('.thamgia_thele').live('click', function(){
			$('.menu .thele a').trigger( 'click' ); return false;
		});
	},
	join: function(){
		$('.menu .thamgia a').live('click', function(){	
			if($('#frm-profile').length > 0)
				$('#frm-profile')[0].reset();
			if($( ".popup_thamgia" ).length > 0){
				$( ".popup_thamgia" ).pdialog({
					width: 481,
//				position: [230,0],
					open: function(){
						var posiParent = $(this).parent().position().top;
						$(this).parent().css({
							'margin-top': (posiParent*2 + 20)+'px',
						});
					}
				});	
				$(".ui-dialog-titlebar").hide();
			}else if($( ".popup_login" ).length > 0){
				$( ".popup_login" ).pdialog({
					title: tr('Login'),
					width: 440,
				});	
				$(".ui-dialog-titlebar").hide();
			}
			return false;
		});
		
		$('.menu .thele a').live('click', function(){			
			$( ".popup_thele" ).pdialog({
				width: 700,
//				position: [230,0],
				open: function(){
//					var posiParent = $(this).parent().position().top;
//					$(this).parent().css({
//				        'margin-top': (posiParent*2 + 20)+'px',
//				    });
				}
			});	
			$(".ui-dialog-titlebar").hide();
			sprScroll('.content_thele');
			return false;
		});
		
		
		$('.menu .hotro a').live('click', function(){			
			$( ".popup_hotro" ).pdialog({
				width: 408,
//				position: [230,0],
				open: function(){
//					var posiParent = $(this).parent().position().top;
//					$(this).parent().css({
//				        'margin-top': (posiParent*2 + 20)+'px',
//				    });
				}
			});	
			$(".ui-dialog-titlebar").hide();
			return false;
		});
		
		
		$('.but_upload').live('click', function(){
			$('#uploadImage').hide();
			$('#uploadFile').trigger( 'click' ); return false;
		});
		
		$('#frm-profile .but_submit').live('click', function(){
			$('#uploadImage').hide();
			if($(".checkbox").is(':checked') == true){
				$('body').loading();
				if(arrFiles && arrFiles.length > 0){					
					if (window.FormData) {
					    formdata = new FormData();
					}
					$.each(arrFiles, function( index, value ) {
						formdata.append("images[]", value);
					});
					formdata.append("PurpleguyProfile[fullname]", $('#PurpleguyProfile_fullname').val());
					formdata.append("PurpleguyProfile[phone]", $('#PurpleguyProfile_phone').val());
					formdata.append("PurpleguyProfile[email]", $('#PurpleguyProfile_email').val());
					
					$.ajax({
						url: $('#frm-profile').attr('action'),
						data: formdata,//$('#frm-profile').serialize(),
						dataType: 'html',
						type: "POST",
						processData: false,
						contentType: false,
						dataType: 'html',
						success: function (res) {
							$('body').unloading();
							$('.popup_thamgia').find('.content').html('<ul><li><p>Bạn đã đăng ký thành công. Chúng tôi sẽ kiểm duyệt và đăng thông tin của bạn trong thời gian sớm nhất.<br/> Cám ơn và hy vọng bạn sẽ trở thành GƯƠNG MẶT ĐẠI DIỆN của PLUN.</p></li></ul>');
						}
					});
				}else{
					$('#uploadImage').show();
					$('#uploadImage').html('Bạn phải tải ảnh để tham gia chương trình !');
					$('body').unloading();
				}
			}else{
				console.log(5555);
				$(".checkbox").closest('label').attr('style', 'color:red;');
			}
			return false;
		});
		
		$('#frm-profile .up_avatar .but_del').live('click', function(){
			var index;
			index = $(this).parent().index();
			$(this).parent().remove();
			arrFiles.splice(index, 1);
		});
	},
	readURL: function(input){
		if (input.files && input.files[0]) {
			var i = 0, len = input.files.length, img, reader, file;			
			var canUpload = true;
            for ( ; i < len; i++ ) {
            	var idx = arrFiles.length + 1;
            	if(idx <= LIMIT_UPLOAD){
            		file = input.files[i];
            		if (!!file.type.match(/image.*/)) {
        				if ( window.FileReader ) {
        					reader = new FileReader();
        					reader.onloadend = function (e) { 
        						var html = '<div class="up_avatar">'
        							+ '<a href="#"><img src="' + e.target.result +'" align="absmiddle" height="74" width="74"></a>' 
        							+ '<a class="but_del"></a>' +
        							'</div>';
        						$('.imgList').append(html);
        						$('.up_avatar img').resizecrop({
        							width:74,
        							height:74,
        							vertical:"center"
        						});
//                        	$('.up_avatar img').attr('src', e.target.result).width(100).height(100);
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
            	alert('Giới hạn tải ảnh của bạn vượt quá 5 tấm!');
            }
        }
	},
};
