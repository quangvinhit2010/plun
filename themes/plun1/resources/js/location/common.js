function save_my_checkin(){
	$('body').loading();
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
    	    	$(".main .col-right .members .list ul").empty();
    	    	get_find_checkin(data);
    	    }else{
    	    	//$("body").unloading();
    	    	window.location.href = '/';
    	    }
        },
        dataType: 'html'
    });	
}
//get state list for register form
function getStateRegister(){
	$('body').loading();
	$('.rs-state').hide();
	$('.rs-city').hide();
	$('.rs-district').hide();
	var data = {
        country_id: $('.re-country option:selected').val()
    };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/getStateListRegister',
        success: function(data) {
    		
    		if(data != ''){
    			$('.rs-state').html(data);
    			$('.rs-state').show();
    		}else{
    			$('.rs-state').hide();
    			$('.rs-state').html('');
    		}
			$('.rs-city').html('');
			$('.rs-district').html('');
    		$('body').unloading();
        },
        dataType: 'html'
    });	
}
function getCityRegister(){
	$('body').loading();
	$('.rs-city').hide();
	$('.rs-district').hide();	
	var data = {
            state_id: $('.re-state option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getcitylistRegister',
            success: function(data) {
        		if(data != ''){
        			$('.rs-city').html(data);
        			$('.rs-city').show();
        			$('.setting_city_row').show();
        		}else{
        			$('.rs-city').hide();
        			$('.setting_city_row').hide();
        			$('.rs-city').html('');
        			
        		}
        		$('.rs-district').html('');
        		$('body').unloading();
            },
            dataType: 'html'
        });	
}
function getDistrictRegister(){
	$('body').loading();
	$('.rs-district').hide();
	var data = {
            city_id: $('.re-city option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getdistrictlistRegister',
            success: function(data) {
        		if(data != ''){
        			$('.rs-district').show();
        			$('.rs-district').html(data);
        		}else{
        			$('.rs-district').html('');
        			$('.rs-district').hide();
        		}
        		$('body').unloading();
            },
            dataType: 'html'
        });	
}
//get state list for Checkin form
function getStateCheckIn(){
	$('.check-in-state').hide();
	$('.check-in-city').hide();
	$('.check-in-city').html('');
	$('.check-in-district').hide();
	$('.check-in-district').html('');
	$('body').loading();
	var data = {
        country_id: $('.ci-country option:selected').val()
    };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/getStateListCheckin',
        success: function(data) {
    		if(data != ''){
    			$('.check-in-state').html(data);
    			$('.check-in-state').show();
    		}else{
    			$('.check-in-state').hide();
    			$('.check-in-state').html('');
    		}
    		$('body').unloading();
        },
        dataType: 'html'
    });	
}
function getCityCheckIn(){
	$('body').loading();
	$('.check-in-city').hide();
	$('.check-in-district').hide();	
	$('.check-in-district').html('');
	var data = {
            state_id: $('.ci-state option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getcitylistCheckin',
            success: function(data) {
        		if(data != ''){
        			$('.check-in-city').html(data);
        			$('.check-in-city').show();
        		}else{
        			$('.check-in-city').hide();
        			$('.check-in-city').html('');
        		}
        		$('body').unloading();
            },
            dataType: 'html'
        });	
}
function getDistrictCheckIn(){
	
	$('.check-in-district').hide();	
	if($('.ci-city option:selected').val() != ''){
		var city_id	=	$('.ci-city option:selected').val();
	}else{
		var city_id	=	0;
	}
		$('body').loading();
		var data = {
	            city_id: city_id
	        };
	        $.ajax({
	            type: "GET",
	            data: data,
	            url: '/location/getdistrictlistCheckin',
	            success: function(data) {
	        		if(data != ''){
	        			$('.check-in-district').show();
	        			$('.check-in-district').html(data);
	        		}else{
	        			$('.check-in-district').hide();
	        			$('.check-in-district').html('');
	        		}
	        		$('body').unloading();
	            },
	            dataType: 'html'
	        });	

}
//get state list for settings
function getStateSettings(){
	var data = {
        country_id: $('.sl-country option:selected').val()
    };
    $.ajax({
        type: "GET",
        data: data,
        url: '/location/getStateListSettings',
        success: function(data) {
            $('.ps-state').html(data);
            getCitySettings();
        },
        dataType: 'html'
    });	
}
function getCitySettings(){
	var data = {
            state_id: $('.sl-state option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getcitylistSettings',
            success: function(data) {
                $('.ps-city').html(data);
                getDistrictSettings();
            },
            dataType: 'html'
        });	
}
function getDistrictSettings(){
	var data = {
            city_id: $('.sl-city option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getdistrictlistSettings',
            success: function(data) {
                $('.ps-district').html(data);
            },
            dataType: 'html'
        });	
}
//get state list for findhim popup
function getStateFindHim(){
		$('body').loading();
		$('.fh-row-city').empty();
		$('.fh-row-district').empty();
		$('.fh-row-citydistrict-contain').hide();
    	var data = {
            country_id: $('.fh-country option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getstatelist',
            success: function(data) {
            	if(data != ''){
	        		$('.fh-row-state-contain').show();
	                $('.fh-row-state').html(data);
	                
            	}else{
            		$('.fh-row-state').empty();
            		$('.fh-row-state-contain').hide();
            		$('.fh-row-city').empty();            		
            	}
            	$('body').unloading();
            },
            dataType: 'html'
        });	
}
function getCityFindHim(){
	$('.fh-row-district').empty();
	$('body').loading();
	var data = {
            state_id: $('.fh-state option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getcitylist',
            success: function(data) {
            	if(data != ''){
            		$('.fh-row-citydistrict-contain').show();
	        		$('.fh-row-city').show();
	                $('.fh-row-city').html(data);
            	}else{
            		$('.fh-row-citydistrict-contain').hide();
            		$('.fh-row-city').hide();
            	}
                $('body').unloading();
            },
            dataType: 'html'
        });	
}
function getDistrictFindHim(){
	$('body').loading();
	var data = {
            city_id: $('.fh-city option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getdistrictlist',
            success: function(data) {
            	if(data != ''){
	        		$('.fh-row-district').show();
	                $('.fh-row-district').html(data);
            	}else{
            		$('.fh-row-district').hide();
            	}
            	$('body').unloading();
            },
            dataType: 'html'
        });	
}