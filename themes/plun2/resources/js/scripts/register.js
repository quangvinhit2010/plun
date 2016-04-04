$(function(){
	if($('.upload_avatar').attr('class')){
		Register.registerUploadAvatar();
	}
});

$(window).resize(function() {
});

var Register = {
		save_fill_out: function(){
			objCommon.loading();
		    var patt_size = /^[0-9,\.]{1,5}$/;
		    
		    //add height
		    if ($('#UsrProfileSettings_height').val() != '') {
		        //validate input weight from
		        if (!patt_size.test($('#UsrProfileSettings_height').val())) {
			    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
			        setTimeout(function () {
			         $( ".pop-mess-succ" ).pdialog('close');
			        }, 2000);	        
		            $('#UsrProfileSettings_height').focus();
		            objCommon.unloading();
		            return false;
		        }
		        var height = $('#UsrProfileSettings_height').val().replace(",", ".");
		        $('#UsrProfileSettings_height').val(height);
		    }

		    //add weight
		    if ($('#UsrProfileSettings_weight').val() != '') {
		        //validate input weight from
		        if (!patt_size.test($('#UsrProfileSettings_weight').val())) {
			    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
			        setTimeout(function () {
			         $( ".pop-mess-succ" ).pdialog('close');
			        }, 2000);	        
		            $('#UsrProfileSettings_weight').focus();
		            objCommon.unloading();
		            return false;
		        }
		        var weight = $('#UsrProfileSettings_weight').val().replace(",", ".");
		        $('#UsrProfileSettings_weight').val(weight);
		    }		    
		    
		    var data = $('#frmFillOut').serialize();
		    
		    //for types
		    var my_types = new Array();
		    $('.my_types').each(function(k, v) {
		    	if($(this).is(':checked')){
		    		my_types.push($(this).val());
		    	}
		    });
		    if (my_types.length > 0) {
		        data += '&UsrProfileSettings[my_types]=' + my_types;
		    }    
		    //for attributes
		    var my_attributes = new Array();
		    $('.my_attributes').each(function(k, v) {
		    	if($(this).is(':checked')){
		    		my_attributes.push($(this).val());
		    	}
		    });
		    if (my_attributes.length > 0) {
		        data += '&UsrProfileSettings[my_attributes]=' + my_attributes;
		    } 
		    
		    //for languages
		    var languages = new Array();
		    $('.languages').each(function(k, v) {
		    	if($(this).is(':checked')){
		    		languages.push($(this).val());
		    	}
		    });
		    if (languages.length > 0) {
		        data += '&UsrProfileSettings[languages]=' + languages;
		    }
		    
		    //for looking_for
		    var looking_for = new Array();
		    $('.looking_for').each(function(k, v) {
		    	if($(this).is(':checked')){
		    		looking_for.push($(this).val());
		    	}
		    });
		    if (looking_for.length > 0) {
		        data += '&UsrProfileSettings[looking_for]=' + looking_for;
		    }
		    
		    
			$.ajax({
			  type: "POST",
			  url: $('#frmFillOut').attr('action'),
			  data: data,
			  dataType: 'json',
			  success: function( response ) {
				  objCommon.unloading();
				  window.location.href='/register/stepAvatar';
		      }
			});
			return false;
	},		
	actionRegister: function(){
		/**
		 * Register
		 */
		$(".btn_submit").click(function () {
			$("#chk_tos").closest('.policy').removeClass('color_error');
			if($("#chk_tos").is(':checked') == true){
				$('#register-form').submit();
			}else{
				$("#chk_tos").closest('.policy').addClass('color_error');
			}
			return false;
		});		
		/**
		 * 
		 */
		var select_cbox = '.select_style select';
        Register.cboxSelected(select_cbox);
        $('.select_style select').css('opacity','0');
	},
	cboxSelected: function(classSelect){
		var i = classSelect;
        $(i).on('change',function(){
            var item_select = $(this).find(':selected').text();
            $(this).parent().find('.txt_select span').text(item_select);
        });
	},
	openDialogUpload: function(){
		$('#uploadImage').trigger( 'click' ); 
		return false;
	},
	scropAvatar: function(){
		if(Register.checkCoords()){
			objCommon.loading();
			$.ajax({
				type: "POST",
				url: $('#frmCropAvatar').attr('action'),
				data: $('#frmCropAvatar').serialize(),
				dataType: 'json',
				success: function( response ) {
					objCommon.unloading();
					window.location.href='/register/stepFindFriends';
				},
				error: function () {
					objCommon.unloading();
		        }
			});
		}else{
			window.location.href='/register/stepFindFriends';
			return false;
		}
	},
	checkCoords: function()
	{
	    if (parseInt($('#w').val())) return true;
	    return false;
	},
	registerUploadAvatar: function(){
		$('#uploadImage').change(function(){
		    var formdata = false;
		    if (window.FormData) {
		        formdata = new FormData();
		    }
	        var i = 0, len = this.files.length, img, reader, file;
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
	                    formdata.append("image", file);
	                    objCommon.loading();
	
	                    jQuery.ajax({
	                    	url: $('#upload_url').val(),
	                        type: "POST",
	                        dataType: 'json',
	                        data: formdata,
	                        processData: false,
	                        contentType: false,
	                        success: function (res) {
	                        	objCommon.unloading();
	                        	if(res.status){
	                        		$('.frame').html(res.html);
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
	}
}

