$(document).ready(function(){
	Tablefortwo.uploadPhoto();
	objTable42.tooltipPlun({
		el: '#popup_updatePublic .wrapImgUpload ul li a.wrap_img',
		posiTop: true,
		titleTip: 'Click chọn hình upload'	
	});
	
	
	//get list user signup
	var numBox = objTable42.loadBoxPerson({
		boxParent: $('.list_request_page .wrap_img_person'),
		wrapBox: $('.wrap_img_person')
	});	
	if($('.listmember').attr('class')){
		$('#numPersonShow').val(numBox);
		Tablefortwo.getListmember();
	}
	if($('.listrequest').attr('class')){
		$('#numPersonShow').val(numBox);
		Tablefortwo.getListrequest();
	}
	if($('.listagree').attr('class')){
		$('#numPersonShow').val(numBox);
		Tablefortwo.getListagree();
	}
	if($('.listcouple').attr('class')){
		var numBox = objTable42.loadBoxPerson({
			boxParent: $('.couple_user'),
			wrapBox: $('.wrap_img_person'),
			wBoxReal: 330,
            hBoxReal: 194,
		});
		$('#numPersonShow').val(numBox);
		Tablefortwo.getListcouple();
	}	
	$('#yw0_button').trigger('click');
});
var Tablefortwo = {
		SignUp: function(){
			if(!$('#popup_thamgia #sigup_input').val()){
				Util.popAlertSuccess('Vui Lòng Nhập 1 Số Điện Thoại Hoặc URL Facebook Của Bạn', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 1200);
				return false;
			}
			if($('#popup_thamgia #SignupForm_sexrole').attr('id')){
				if($('.loadFirst #SignupForm_sexrole').val() == ''){
					Util.popAlertSuccess('Vui Lòng Chọn Vai Trò', 300);
	    	        setTimeout(function () {
	          	         $( ".pop-mess-succ" ).pdialog('close');
	          	        }, 1200);
					return false;
				}		
			}
			if(!$('#popup_thamgia #SignupForm_rememberMe').is(':checked')){
				Util.popAlertSuccess('Bạn Phải Đồng Ý Điều Khoản', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 1200);
				return false;
			}
			
			objCommon.loading();
			
			var data	=	{
					'signup_info': $('#popup_thamgia #sigup_input').val(),
					'sex_role': $('#popup_thamgia #SignupForm_sexrole').val()
			};
			
			$.ajax({
				type: "POST",
				url: '/table42/signup',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objCommon.unloading();
					if(response.status == '4'){
						Util.popAlertSuccess('Event Chỉ Dành Cho Top & Bottom! Click Vào <a href="/settings">đây </a> Để Cập Nhật Lại Thông Tin Cá Nhân', 300);
						return false;						
					}					
					if(response.status == '2'){
						Util.popAlertSuccess('Bạn Đã Gởi Đăng Ký Trước Đó', 300);
		    	        setTimeout(function () {
		       	         $( ".pop-mess-succ" ).pdialog('close');
		       	        }, 2000);  
						return false;						
					}
					if(response.status == '1'){
                        //Tablefortwo.openPopup('popup_xacnhan', 308, 122);
						Tablefortwo.openPopup('popup_upload', 308, 176);
                        $('#popup_thamgia').pdialog('close');
                        $('.join_now_botton').html('<a class="bg_btn" href="javascript:void(0);" onclick="Tablefortwo.openPopup(\'popup_upload\', 308, 176);" title="Tham gia ngay"><span class="bg_btn">tham gia ngay</span></a>');
					}
				}
			});
			return false;			
		},		
		Login: function(){
			
			if($('#LoginForm_username').val() == '' || $('#LoginForm_password').val() == ''){
				Util.popAlertSuccess('Vui Lòng Nhập Đẩy Đủ Tên Đăng Nhập Và Mật Khẩu', 300);
    	        setTimeout(function () {
       	         $( ".pop-mess-succ" ).pdialog('close');
       	        }, 1200);  
				return false;
            }
			objCommon.loading();
			var data	=	{
					'LoginForm[username]': $('#LoginForm_username').val(),
					'LoginForm[password]': $('#LoginForm_password').val(),
					'LoginForm[rememberMe]': $('#LoginForm_rememberMe').val()
			};
			$.ajax({
				type: "POST",
				url: '/table42/login',
				data: data,
				dataType: 'json',
				success: function( response ) {					
					objCommon.unloading();
					if(response.status){
						location.reload();
					}else{						
						Util.popAlertSuccess('Tên Đăng Nhập hoặc Mậu Khẩu không đúng', 300);
					}
				}
			});
			return false;
		},
		register: function(){
			if($('#RegisterForm_username').val() == ''){
				$('#RegisterForm_username').focus();
				Util.popAlertSuccess('Vui Lòng Nhập Username', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}
			if($('#RegisterForm_email').val() == ''){
				$('#RegisterForm_email').focus();
				Util.popAlertSuccess('Vui Lòng Nhập Email', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}
			if($('#RegisterForm_password').val() == ''){
				$('#RegisterForm_password').focus();
				Util.popAlertSuccess('Vui Lòng Nhập Mật Khẩu', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}
			if($('#RegisterForm_password').val() != $('#RegisterForm_confirm_password').val()){
				$('#RegisterForm_confirm_password').focus();
				Util.popAlertSuccess('Vui Lòng Gõ Lại Xác Nhận Mật Khẩu', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}	
			if($('#RegisterForm_verifyCode').val() == ''){
				$('#RegisterForm_verifyCode').focus();
				Util.popAlertSuccess('Vui Lòng Gõ Mã Xác Nhận', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}			
			if($('#RegisterForm_sexrole').val() == ''){
				$('#RegisterForm_sexrole').focus();
				Util.popAlertSuccess('Vui Lòng Chọn Vai Trò', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}			

			if(!$('#RegisterForm_agree').is(':checked')){
				Util.popAlertSuccess('Bạn Phải Đồng Ý Điều Khoản', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 2000);
				return false;
			}		
			var data	=	{
					'RegisterForm[username]': $('#RegisterForm_username').val(),
					'RegisterForm[email]': $('#RegisterForm_email').val(),
					'RegisterForm[password]': $('#RegisterForm_password').val(),
					'RegisterForm[confirm_password]': $('#RegisterForm_confirm_password').val(),
					'RegisterForm[sex_role]': $('#RegisterForm_sexrole').val(),
					'RegisterForm[verifyCode]': $('#RegisterForm_verifyCode').val(),
					'RegisterForm[day]': $('#RegisterForm_day').val(),
					'RegisterForm[month]': $('#RegisterForm_month').val(),
					'RegisterForm[year]': $('#RegisterForm_year').val()
			};
			objCommon.loading();
			$.ajax({
				type: "POST",
				url: '/table42/register',
				data: data,
				dataType: 'json',
				success: function( response ) {					
					objCommon.unloading();
					$('#yw0_button').trigger('click');
					if(response.status){
						Util.popAlertSuccess('Đăng Ký Thành Công', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 2000);
						location.reload();
					}else{							
						if(response.msg['username']){
							Util.popAlertSuccess(response.msg['username'], 300);
			    	        setTimeout(function () {
			          	         $( ".pop-mess-succ" ).pdialog('close');
			          	        }, 2000);
							$('#RegisterForm_username').focus();
							return false;
						}
						if(response.msg['email']){
							Util.popAlertSuccess(response.msg['email'], 300);
			    	        setTimeout(function () {
			          	         $( ".pop-mess-succ" ).pdialog('close');
			          	        }, 2000);
							$('#RegisterForm_email').focus();
							return false;
						}	
						if(response.msg['verifyCode']){
							Util.popAlertSuccess(response.msg['verifyCode'], 300);
			    	        setTimeout(function () {
			          	         $( ".pop-mess-succ" ).pdialog('close');
			          	        }, 2000);
							$('#RegisterForm_verifyCode').focus();
							return false;
						}						
					}
					
				}
			});
			return false;			
		},
		openPopup: function(idname, width, height){
            objTable42.showEffectPopup({//popup register
                width: width,
                height: height,
                popupDefault: true
            }, idname);			
		},
		uploadPhoto: function(){
			$('#uploadType').val('1');
			$('#uploadImage').change(function(){
			    var formdata = false;
			    
			    if(this.files.length != 3){
					Util.popAlertSuccess('Bạn Phải Chọn 3 Hình', 300);
	    	        setTimeout(function () {
	          	         $( ".pop-mess-succ" ).pdialog('close');
	          	        }, 1200);
	    	        return false;
			    }
			    
			    if (window.FormData) {
			        formdata = new FormData();
			    }
		        var i = 0, len = this.files.length, img, reader, file;
		        var first	=	false;
		        $('#popup_updateLocal ul').empty();
		        for ( ; i < len; i++ ) {
		            file = this.files[i];
		            if (!!file.type.match(/image.*/)) {
		                if ( window.FileReader ) {
		                    reader = new FileReader();
		                    reader.onloadend = function (e) { 
		                    };
		                    reader.readAsDataURL(file);
		                }
		                if (formdata) {
		                    formdata.append("table42file", file);
		                    objCommon.loading();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
		
		                    jQuery.ajax({
		                    	url: '/table42/upload',
		                        type: "POST",
		                        dataType: 'json',
		                        data: formdata,
		                        processData: false,
		                        contentType: false,
		                        success: function (res) {
		                        	if(i == len){
		                        		objCommon.unloading();
		                        	}
		                        	if(res.success){
		                        		$('#popup_updateLocal ul').append(res.html);
		                        		if(!first){
		                        			Tablefortwo.openPopup('popup_updateLocal', 767, 374);
		                        			first	=	true;
		                        		}
		                        	}else{
				            			Util.popAlertSuccess(res.error, 400);
				            			setTimeout(function () {
				            				$( ".pop-mess-succ" ).pdialog('close');
				            			}, 2000);			                    	
				                    }
		                        }
		                    });
		                    
		                }
		            }
		            else
		            {
		                alert('Not a vaild image!');
		            }   
		        }
		        
			});
		},
		choosePhotoFromUpload: function(){
			$('#uploadType').val('1');
			objCommon.loading();
			var data	=	{};
			data['type']	=	1;
		    //for my photoids
		    var photoids = new Array();
		    $('#popup_updateLocal .photoUploaded').each(function(k, v) {
		    	photoids.push($(this).find('img').attr('pid'));
		    });
		    
		    if (photoids.length > 0) {
		        data['photoids'] = photoids;
		    }
		    
			$.ajax({
				type: "POST",
				url: '/table42/choosephoto',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objCommon.unloading();
 					if(response.status == '1'){
						$('#popup_xacnhan li').text('Bạn Đã Hoàn Thành Thủ Tục Đăng Ký, Vui Lòng Chờ Chúng Tôi Duyệt Hồ Sơ');
						Tablefortwo.openPopup('popup_xacnhan', 308, 122);
						$('.txt_intro a').hide();
						$('#popup_upload').pdialog('close');
						$('#popup_updateLocal').pdialog('close');
		    	        return false;
					}
 					if(response.status == '2'){
						$('#popup_xacnhan li').text('Bạn Đã Hoàn Thành Thủ Tục Đăng Ký, Nhưng Đã Quá Thời Hạn Đăng Ký Nên Chúng Tôi Sẽ Duyệt Đơn Đăng ký Của Bạn Vào Kỳ Sau');
						Tablefortwo.openPopup('popup_xacnhan', 308, 122);
						$('.txt_intro a').hide();
						$('#popup_upload').pdialog('close');
						$('#popup_updateLocal').pdialog('close');
		    	        return false;
					}
				}
			});
			return false;
		},
		importPublicPhoto: function(){
			$('#uploadType').val('2');
			objCommon.loading();
			$.ajax({
				type: "POST",
				url: '/table42/importpublicphoto',
				dataType: 'json',
				success: function( response ) {		
					objCommon.unloading();
					if(response.status){
						$('#popup_updatePublic').html(response.html);
						Tablefortwo.openPopup('popup_updatePublic', 765, 614);
						objCommon.scrollBar('#popup_updatePublic .wrapImgUpload ul');
					}else{
						Util.popAlertSuccess('Bạn Chưa có ảnh nào trong thư viện ảnh công cộng', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 2000);
		    	        return false;							
					}
				}
			});
			return false;			
		},
		choosePhotoFromPublicPhoto: function(){
			var total_photo_choose	=	$('#popup_updatePublic .selectedImgUpload').length;
			if(total_photo_choose != 3){
				Util.popAlertSuccess('Bạn Cần chọn 3 hình', 300);
    	        setTimeout(function () {
          	         $( ".pop-mess-succ" ).pdialog('close');
          	        }, 1200);
    	        return false;				
			}
			objCommon.loading();
			var data	=	{};
			data['type']	=	$('#uploadType').val();
		    //for my photoids
		    var photoids = new Array();
		    $('#popup_updatePublic .selectedImgUpload').each(function(k, v) {
		    	photoids.push($(this).find('img').attr('pid'));
		    });
		    
		    if (photoids.length > 0) {
		        data['photoids'] = photoids;
		    }
		    
			$.ajax({
				type: "POST",
				url: '/table42/choosephoto',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objCommon.unloading();
 					if(response.status == '1'){
						$('#popup_xacnhan li').text('Bạn Đã Hoàn Thành Thủ Tục Đăng Ký, Vui Lòng Chờ Chúng Tôi Duyệt Hồ Sơ');
						Tablefortwo.openPopup('popup_xacnhan', 308, 122);
						$('.txt_intro a').hide();
						$('#popup_upload').pdialog('close');
						$('#popup_updatePublic').pdialog('close');
		    	        return false;
					}
 					if(response.status == '2'){
						$('#popup_xacnhan li').text('Bạn Đã Hoàn Thành Thủ Tục Đăng Ký, Nhưng Đã Quá Thời Hạn Đăng Ký Nên Chúng Tôi Sẽ Duyệt Đơn Đăng ký Của Bạn Vào Kỳ Sau');
						Tablefortwo.openPopup('popup_xacnhan', 308, 122);
						$('.txt_intro a').hide();
						$('#popup_upload').pdialog('close');
						$('#popup_updateLocal').pdialog('close');
		    	        return false;
					}
				}
			});
			return false;			
		},
		getListmember: function(){
			
			objTable42.loadingInside('.wrap_img_person');
			
			var data	=	{};
			data['type']	=	$('#memberType').val();
			data['limit']	=	$('#numPersonShow').val();
			data['paging']	=	$('#paging').val();
			
			$.ajax({
				type: "POST",
				url: '/table42/listmember',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objTable42.unloadingInside();
					
					
					if(response.status){
						$('.wrap_user_req .wrap_img_person').html(response.html);
						objTable42.loadListEffectOrder('.wrap_img_person ul');
						if(response.next_page){
							$('.icon_next_list').show();
						}else{
							$('.icon_next_list').hide();
						}
						
						if(parseInt($('#paging').val()) > 1){
							$('.icon_back_list').show();
						}else{
							$('.icon_back_list').hide();
						}
					}else{
						$('.wrap_user_req .wrap_img_person').html('');
						$('.icon_back_list').hide();
						$('.icon_next_list').hide();
					}
				}
			});
			return false;			
		},
		getListmemberNext: function(){
			$('#paging').val(parseInt($('#paging').val()) + 1);
			Tablefortwo.getListmember();
		},
		getListmemberPre: function(){
			$('#paging').val(parseInt($('#paging').val()) - 1);
			Tablefortwo.getListmember();			
		},
		changeListmembertab: function(tab){
			$('#memberType').val($(tab).attr('type'));
			$('#paging').val(1);
			$('.btn_filter_user a').removeClass('active');
			$(tab).addClass('active');
			Tablefortwo.getListmember();
		},
		showProfiledetail: function(obj){
			var data = {
					profileid: $(obj).attr('data-profileid')
			};
			var _this = obj;
			objTable42.showEffectPopup({
				effectBoxImgLoad: true
			}, _this);

			$.ajax({
				data: data,
				type: 'GET',
				url: '/table42/Getprofiledetail',
				success: function(res){
					objTable42.unloadingInside();
										
					objTable42.showEffectPopup({
						htmlAppend: res.html,
						height: 649,
						width: 625
					}, _this);

                    var getImgPopup = setInterval(function(){
                        if($('.pic_person .wrap_img').length > 0){
                            clearInterval(getImgPopup);
                            $('.pic_person .wrap_img').colorbox({
                                rel: "wrap_img",
                                scrolling: false,
                                fixed: true,
                                innerHeight: true,
                                scalePhotos: true,
                                maxWidth: '100%',
                                maxHeight: '95%'
                            });
                        }
                    },0);
                },
				dataType: 'json'
			
			});
			return false;				
		},
		showCoupleDetail: function(obj){
			$('#popup_ketdoi').html('');
			var data = {
					requestid: $(obj).attr('data-requestid')
			};
			var _this = obj;
			objCommon.loading();
			$.ajax({
				data: data,
				type: 'GET',
				url: '/table42/Getcoupledetail',
				success: function(res){
					objCommon.unloading();
					objTable42.showEffectPopup({
						htmlAppend: res.html,
						width: 625,
                        minHeight: 716,
						popupDefault: true,
						scrollContent: '.infro_ketdoi'
					}, _this);
				},
				dataType: 'json'
			
			});
			return false;				
		},		
		showProfileChooseDating: function(obj){
			$('.popup_chose_ketdoi').html('');
			var data = {
					profileid: $(obj).attr('data-profileid')
			};
			var _this = obj;
			objTable42.showEffectPopup({
				effectBoxImgLoad: true
			}, _this);
			$.ajax({
				data: data,
				type: 'GET',
				url: '/table42/showProfileChooseDating',
				success: function(res){
					objTable42.unloadingInside();
										
					objTable42.showEffectPopup({
						htmlAppend: res.html,
						width: 625,
						height: 716,
						scrollContent: '.infro_ketdoi'
					}, _this);
					

					
				},
				dataType: 'json'
			
			});
			return false;				
		},		
		showProfileagree: function(obj){
			var data = {
					profileid: $(obj).attr('data-profileid')
			};
			var _this = obj;
			objTable42.showEffectPopup({
				effectBoxImgLoad: true
			}, _this);
			$.ajax({
				data: data,
				type: 'GET',
				url: '/table42/Getprofileagree',
				success: function(res){
					objTable42.unloadingInside();
										
					objTable42.showEffectPopup({
						htmlAppend: res.html,
						height: 649,
						width: 625
					}, _this);
				},
				dataType: 'json'
			
			});
			return false;				
		},		
		requestDating: function(profileid){
			objCommon.loading();
			var data = {
					profileid: profileid
			};
			$.ajax({
				data: data,
				type: 'POST',
				url: '/table42/requestdating',
				success: function(res){
					objCommon.unloading();
					
					if(res.status == 1){
						$('.request-button').hide();
						Util.popAlertSuccess('Bạn Đã Gởi Yêu Cầu Kết Đôi Thành Công', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					if(res.status == 0){
						Util.popAlertSuccess('Không Thể Gởi Yêu Cầu Kết Đôi Lúc Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					if(res.status == 2){
						Util.popAlertSuccess('Bạn Đã Gởi Yêu Cầu Kết Đôi Thành Công', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					
				},
				dataType: 'json'
			
			});
			return false;			
		},
		agreeDating: function(profileid){
			objCommon.loading();
			var data = {
					profileid: profileid
			};
			$.ajax({
				data: data,
				type: 'POST',
				url: '/table42/agreedating',
				success: function(res){
					objCommon.unloading();
					
					if(res.status == 1){
						$('.request-button').hide();
						Util.popAlertSuccess('Bạn Đã Đồng Ý Ghép Đôi Với Người Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					if(res.status == 0){
						Util.popAlertSuccess('Không Thể Gởi Yêu Cầu Kết Đôi Lúc Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}					
				},
				dataType: 'json'
			
			});
			return false;			
		},
		makeDating: function(profileid ){
			objCommon.loading();
			var data = {
					profileid: profileid
			};
			$.ajax({
				data: data,
				type: 'POST',
				url: '/table42/makedating',
				success: function(res){
					objCommon.unloading();
					if(res.status == 1){
						$('.profile-agree-' + profileid).addClass('active');
						$('.request-button').hide();
						Util.popAlertSuccess('Bạn Đã Đồng Ý Hẹn Hò Với Người Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					if(res.status == 2){
						Util.popAlertSuccess('Bạn Chỉ Được Chọn 1 Người để Ghép Đôi', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}					
					if(res.status == 0){
						Util.popAlertSuccess('Không Thể Gởi Yêu Cầu Kết Đôi Lúc Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}					
				},
				dataType: 'json'
			
			});
			return false;			
		},
		cancelDating: function(profileid){
			objCommon.loading();
			var data = {
					profileid: profileid
			};
			$.ajax({
				data: data,
				type: 'POST',
				url: '/table42/canceldating',
				success: function(res){
					objCommon.unloading();
					if(res.status == 1){
						$('.profile-agree-' + profileid).removeClass('active');
						$('.request-button').hide();
						Util.popAlertSuccess('Bạn Đã Hủy Hẹn Hò Với Người Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					if(res.status == 0){
						Util.popAlertSuccess('Không Thể Gởi Yêu Cầu Kết Đôi Lúc Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}					
				},
				dataType: 'json'
			
			});
			return false;			
		},		
		getListrequest: function(){
			
			objTable42.loadingInside('.wrap_img_person');
			
			var data	=	{};
			data['limit']	=	$('#numPersonShow').val();
			data['paging']	=	$('#paging').val();
			
			$.ajax({
				type: "POST",
				url: '/table42/listrequest',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objTable42.unloadingInside();
					
					
					if(response.status){
						$('.wrap_user_req .wrap_img_person').html(response.html);
						objTable42.loadListEffectOrder('.wrap_img_person ul');
						if(response.next_page){
							$('.icon_next_list').show();
						}else{
							$('.icon_next_list').hide();
						}
						
						if(parseInt($('#paging').val()) > 1){
							$('.icon_back_list').show();
						}else{
							$('.icon_back_list').hide();
						}
					}else{
						$('.wrap_user_req .wrap_img_person').html('');
						$('.icon_back_list').hide();
						$('.icon_next_list').hide();
					}
				}
			});
			return false;			
		},
		getListrequestNext: function(){
			$('#paging').val(parseInt($('#paging').val()) + 1);
			Tablefortwo.getListrequest();
		},
		getListrequestPre: function(){
			$('#paging').val(parseInt($('#paging').val()) - 1);
			Tablefortwo.getListrequest();			
		},
		getListagree: function(){
			
			objTable42.loadingInside('.wrap_img_person');
			
			var data	=	{};
			data['limit']	=	$('#numPersonShow').val();
			data['paging']	=	$('#paging').val();
			
			$.ajax({
				type: "POST",
				url: '/table42/listagree',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objTable42.unloadingInside();
					
					
					if(response.status){
						$('.wrap_user_req .wrap_img_person').html(response.html);
						objTable42.loadListEffectOrder('.wrap_img_person ul');
						if(response.next_page){
							$('.icon_next_list').show();
						}else{
							$('.icon_next_list').hide();
						}
						
						if(parseInt($('#paging').val()) > 1){
							$('.icon_back_list').show();
						}else{
							$('.icon_back_list').hide();
						}
					}else{
						$('.wrap_user_req .wrap_img_person').html('');
						$('.icon_back_list').hide();
						$('.icon_next_list').hide();
					}
				}
			});
			return false;			
		},
		getListagreeNext: function(){
			$('#paging').val(parseInt($('#paging').val()) + 1);
			Tablefortwo.getListagree();
		},
		getListagreePre: function(){
			$('#paging').val(parseInt($('#paging').val()) - 1);
			Tablefortwo.getListagree();			
		},		
		getListcouple: function(){
			
			objTable42.loadingInside('.wrap_img_person');
			
			var data	=	{};
			data['limit']	=	$('#numPersonShow').val();
			data['paging']	=	$('#paging').val();
			
			$.ajax({
				type: "POST",
				url: '/table42/listcouple',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objTable42.unloadingInside();
					
					
					if(response.status){
						$('.wrap_user_req .wrap_img_person').html(response.html);
						objTable42.loadListEffectOrder('.wrap_img_person ul');
						if(response.next_page){
							$('.icon_next_list').show();
						}else{
							$('.icon_next_list').hide();
						}
						
						if(parseInt($('#paging').val()) > 1){
							$('.icon_back_list').show();
						}else{
							$('.icon_back_list').hide();
						}
					}else{
						$('.wrap_user_req .wrap_img_person').html('');
						$('.icon_back_list').hide();
						$('.icon_next_list').hide();
					}
				}
			});
			return false;			
		},
		getListcoupleNext: function(){
			$('#paging').val(parseInt($('#paging').val()) + 1);
			Tablefortwo.getListcouple();
		},
		getListcouplePre: function(){
			$('#paging').val(parseInt($('#paging').val()) - 1);
			Tablefortwo.getListcouple();			
		},		
		voteCouple: function(requestid){
			objCommon.loading();
			var data = {
					requestid: requestid
			};
			$.ajax({
				type: "POST",
				url: '/table42/votecouple',
				data: data,
				dataType: 'json',
				success: function( response ) {		
					objCommon.unloading();
					
					if(response.status == '1'){
						$('.request-button').hide();
						Util.popAlertSuccess('Bạn Đã Bình Chọn Thành Công', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        $('.total_vote_couple').text(response.total);
		    	        return false;	
					}
					if(response.status == '3'){
						Util.popAlertSuccess('Bạn Không Thể Bình Chọn Cho Chính Mình', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}					
					if(response.status == '2'){
						Util.popAlertSuccess('Bạn Đã Bình Chọn Trước Đó', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
					if(response.status == '4'){
						Tablefortwo.openPopup('popup_login', 308, 253);
						/*
						Util.popAlertSuccess('Bạn Chưa Đăng Nhập', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        */
		    	        return false;	
					}					
					if(response.status == '0'){
						Util.popAlertSuccess('Bạn Không Thể Bình Chọn Lúc Này', 300);
		    	        setTimeout(function () {
		          	         $( ".pop-mess-succ" ).pdialog('close');
		          	        }, 1200);
		    	        return false;	
					}
				}
			});
			return false;
			
		},
		alertSignupNextround: function(){
			Util.popAlertSuccess('Hồ Sơ Của Bạn Đã Được Chuyển Vào Vòng Thi Tới', 300);
	        setTimeout(function () {
      	         $( ".pop-mess-succ" ).pdialog('close');
      	        }, 1200);
	        return false;				
		}
}