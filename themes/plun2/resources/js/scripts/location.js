var Location = {
		getStateListSettings: function(){
			$('.setting-state-list').hide();
			$('.setting-state-list select').html('');
			$('.setting-city-list').hide();
			$('.setting-city-list select').html('');
			$('.setting-district-list').hide();
			$('.setting-district-list select').html('');
			
			objCommon.loading();
			var data = {
		        country_id: $('.setting-country option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getstatelist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.setting-state-list select').html(data);
		    			$('.setting-state-list').show();
		    			$('.state_setting_text').text('--Any--');
		    		}else{
		    			$('.setting-state-list').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getCityListSettings: function(){
			$('.setting-city-list').hide();
			$('.setting-city-list select').html('');
			$('.setting-district-list').hide();
			$('.setting-district-list select').html('');
			
			objCommon.loading();
			var data = {
		        state_id: $('.setting-state option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getcitylist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.setting-city-list select').html(data);
		    			$('.setting-city-list').show();
		    			$('.city_setting_text').text('--Any--');
		    		}else{
		    			$('.setting-city-list').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getDistrictListSettings: function(){
			$('.setting-district-list').hide();
			$('.setting-district-list select').html('');
			
			objCommon.loading();
			var data = {
		        city_id: $('.setting-city option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getdistrictlist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.setting-district-list select').html(data);
		    			$('.setting-district-list').show();
		    			$('.district_setting_text').text('--Any--');
		    		}else{
		    			$('.setting-district-list').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getStateListFindHim: function(){
			$('.fh-row-state-contain').hide();
			$('.fh-row-state-contain select').html('');
			$('.fh-row-citydistrict-contain').hide();
			
			$('.fh-row-city').hide();
			$('.fh-row-city select').html('');
			$('.fh-row-district').hide();
			$('.fh-row-district select').html('');
			
			objCommon.loading();
			var data = {
		        country_id: $('.findhim-country option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getstatelist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.fh-row-state-contain select').html(data);
		    			$('.fh-row-state-contain').show();
		    			$('.state_findhim_text').text('--Any--');
		    		}else{
		    			$('.fh-row-state-contain').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getCityListFindHim: function(){

			$('.fh-row-citydistrict-contain').hide();
			
			$('.fh-row-city').hide();
			$('.fh-row-city select').html('');
			$('.fh-row-district').hide();
			$('.fh-row-district select').html('');
			
			objCommon.loading();
			var data = {
		        state_id: $('.fh-state option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getcitylist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.fh-row-city select').html(data);
		    			$('.fh-row-city').show();
		    			$('.fh-row-citydistrict-contain').show();
		    			$('.city_findhim_text').text('--Any--');
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getDistrictListFindHim: function(){
			$('.fh-row-district').hide();
			$('.fh-row-district select').html('');
			
			objCommon.loading();
			var data = {
		        city_id: $('.fh-city option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getdistrictlist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.fh-row-district select').html(data);
		    			$('.fh-row-district').show();
		    			$('.fh-row-citydistrict-contain').show();
		    			$('.district_findhim_text').text('--Any--');
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getStateListRegister: function(){
			$('.register-state').hide();
			$('.register-state').html('');
			$('.register-city').hide();
			$('.register-city').html('');
			$('.register-district').hide();
			$('.register-district').html('');
			
			objCommon.loading();
			var data = {
		        country_id: $('.register-country option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getstatelist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.register-state').html(data);
		    			$('.register-state').show();
		    		}else{
		    			$('.register-state').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getCityListRegister: function(){
			$('.register-city').hide();
			$('.register-city').html('');
			$('.register-district').hide();
			$('.register-district').html('');
			
			objCommon.loading();
			var data = {
		        state_id: $('.register-state option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getcitylist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.register-city').html(data);
		    			$('.register-city').show();
		    		}else{
		    			$('.register-city').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},		
		getDistrictListRegister: function(){
			$('.register-district').hide();
			$('.register-district').html('');
			
			objCommon.loading();
			var data = {
		        city_id: $('.register-city option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getdistrictlist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.register-district').html(data);
		    			$('.register-district').show();
		    		}else{
		    			$('.register-district').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},		
		getStateListCheckin: function(){
			$('.check-in-state').hide();
			$('.check-in-state select').html('');
			$('.check-in-city').hide();
			$('.check-in-city select').html('');
			$('.check-in-district').hide();
			$('.check-in-district select').html('');
			
			objCommon.loading();
			var data = {
		        country_id: $('.ci-country option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getstatelist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.check-in-state select').html(data);	
		    			$('.text-check-in-state').text('--Any--');
		    			$('.check-in-state').show();
		    		}else{
		    			$('.check-in-state').hide();
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getCityListCheckin: function(){
			$('.check-in-city').hide();
			$('.check-in-city select').html('');
			$('.check-in-district').hide();
			$('.check-in-district select').html('');
			
			objCommon.loading();
			var data = {
		        state_id: $('.ci-state option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getcitylist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.check-in-city select').html(data);
		    			$('.check-in-city').show();
		    			$('.text-check-in-city').text('--Any--');
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		getDistrictListCheckin: function(){
			$('.check-in-district').hide();
			$('.check-in-district select').html('');
			
			objCommon.loading();
			var data = {
		        city_id: $('.ci-city option:selected').val()
		    };
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/location/Getdistrictlist',
		        success: function(data) {
		    		if(data != ''){
		    			$('.check-in-district select').html(data);
		    			$('.check-in-district').show();
		    			$('.text-check-in-district').text('--Any--');
		    		}
		    		objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		saveCheckin: function(){
			objCommon.loading();
			var data 	=	{};
			if($('.ci-country').val()){
				data['country_id']	=	$('.ci-country').val();
			}
			if($('.ci-state').val()){
				data['state_id']	=	$('.ci-state').val();
			}
			if($('.ci-city').val()){
				data['city_id']	=	$('.ci-city').val();
			}	
			if($('.ci-district').val()){
				data['district_id']	=	$('.ci-district').val();
			}	
			if($('#online_lookingfor').val()){
				data['looking_for']	=	$('#online_lookingfor').val();
			}
			if($('#check_in_checklocation').val()){
				data['text_venue']	=	$('#check_in_checklocation').val();
				data['id_venue']	=	$('#suggest_id_venue').val();
			}
		    $.ajax({
		        type: "POST",
		        data: data,
		        url: '/location/saveCheckin',
		        success: function(data_return) {
		        	$('.pop-checkin').toggle();
		    		$('.current_location').html(data_return);
		    	    data['offset']	=	0;
		    	    if($(".suggest-user-settings").attr('class')){
		    	    	$('#showmore_next_offset').val(0)
		    	    	$('#showmore_type').val('checkin');
		    	    	$(".list_explore .suggest-user-settings ul").empty();
		    	    	get_find_checkin(data);

		    	    	Location.closeCheckinform();
		    	    	location.reload();
		    	    }else{
		    	    	//$("body").unloading();
		    	    	window.location.href = '/';
		    	    }
		        },
		        dataType: 'html'
		    });	
		},
		resetCheckinform: function(){
			$('.text-check-in-state').text('--Any--');
			$('.check-in-city').hide();
			$('.check-in-city select').html('');
			$('.check-in-district').hide();
			$('.check-in-district select').html('');
			
			$('.ci-state option').each(function() {
		    	if($(this).attr("selected") == 'selected'){
		    		$(this).attr("selected",false);
		    	}
			});
			$('.ci-state option').each(function() {
		    	if($(this).val() == ''){
		    		$(this).attr("selected",true);
		    	}
			});			
		},
		clearVenue: function(curr){
			$('.select2-container span').html('');
			$("#suggest_text_venue").val("");
			$("#suggest_id_venue").val(0);
			$(curr).hide();

		},
		closeCheckinform: function(){
	    	//close popup checkin
	    	$('.check_in').removeClass('activeShowBox');
	    	$('.check_in').hide();			
		},
		showMoreCheckin: function(){
			objCommon.loading();
			var offset =	parseInt($('#whocheckin_offset').val());
			var limit  =	parseInt($('#whocheckin_limit').val());
			var total	=	parseInt($('#whocheckin_total').val());
			
			var data = {
				venue_id: $('#whocheckin_venue_id').val(),
				offset: offset,
				limit: limit,
				showmore: 1
			};
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/venues/whocheckin',
		        success: function(data_return) {
					if(offset + limit >= total){
						$('.whocheckinshowmore').hide();
					}
					
		        	if(data_return != ''){
			        	$('#popup_listUserLoca .listwhocheckin').append(data_return);
			        	$('#whocheckin_offset').val(offset + limit);
		        	}
		        	objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},
		showMoreDetailCheckin: function(){
			objCommon.loading();
			
			var offset =	parseInt($('#whocheckin_offset').val());
			var limit  =	parseInt($('#whocheckin_limit').val());
			var total	=	parseInt($('#whocheckin_total').val());

			var data = {
				venue_id: $('#whocheckin_venue_id').val(),
				offset: offset,
				limit: limit,
				showmore: 1
			};
		    $.ajax({
		        type: "GET",
		        data: data,
		        url: '/venues/GetVenueDetail',
		        success: function(data_return) {
					if(offset + limit >= total){
						$('.footer_loadmore').hide();
					}
		        	if(data_return != ''){
			        	$('.listwhocheckin').append(data_return);
			        	$('#whocheckin_offset').val(offset + limit);
		        	}
		        	objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		},		
		CheckinFromDetail: function(venue_id){
			objCommon.loading();
			var data = {
				venue_id: venue_id
			};
		    $.ajax({
		        type: "POST",
		        data: data,
		        url: '/venues/checkinfromdetail',
		        success: function(data_return) {
		        	$('.checkinfromdetail').html('<i class="icon_common"></i>' + tr('Checked-in here'));
		        	$('.checkinfromdetail').attr('onclick', 'void(0);');
		        	objCommon.unloading();
		        },
		        dataType: 'html'
		    });				
		}
};