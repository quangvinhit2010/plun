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
    $.ajax({
        type: "POST",
        data: data,
        url: '/location/saveCheckin',
        success: function(data_return) {
	    	Util.popAlertSuccess(tr('Data saved successfully!'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	         window.location.href = '/';
	        }, 2000);         	
        	$("body").unloading();
        },
        dataType: 'html'
    });	
    return false;
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
	$('.check-in-city div').html('');
	$('.check-in-district').hide();
	$('.check-in-district div').html('');
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
    			$('.check-in-state div').html(data);
    			$('.check-in-state').show();
    		}else{
    			$('.check-in-state').hide();
    			$('.check-in-state div').html('');
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
	$('.check-in-district div').html('');
	var data = {
            state_id: $('.ci-state option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getcitylistCheckin',
            success: function(data) {
        		if(data != ''){
        			$('.check-in-city div').html(data);
        			$('.check-in-city').show();
        		}else{
        			$('.check-in-city').hide();
        			$('.check-in-city div').html('');
        		}
        		$('body').unloading();
            },
            dataType: 'html'
        });	
}
function getDistrictCheckIn(){
	$('body').loading();
	$('.check-in-district').hide();	
	$('.check-in-district div').html('');
	var data = {
            city_id: $('.ci-city option:selected').val()
        };
        $.ajax({
            type: "GET",
            data: data,
            url: '/location/getdistrictlistCheckin',
            success: function(data) {
        		if(data != ''){
        			$('.check-in-district').show();
        			$('.check-in-district div').html(data);
        		}else{
        			$('.check-in-district').hide();
        			$('.check-in-district div').html('');
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
		$('.fh-row-city-contain').hide();
		$('.fh-row-city-contain div').empty();
		
		$('.fh-row-district-contain').hide();
		$('.fh-row-district-contain div').empty();		
		
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
	                $('.fh-row-state-contain div').html(data);
	                
            	}else{
            		$('.fh-row-state-contain').hide();
            		$('.fh-row-state-contain div').empty();
            	}
            	$('body').unloading();
            },
            dataType: 'html'
        });	
}
function getCityFindHim(){
	$('.fh-row-district-contain').hide();
	$('.fh-row-district-contain div').empty();	
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
            		$('.fh-row-city-contain').show();
	                $('.fh-row-city-contain div').html(data);
            	}else{
            		$('.fh-row-city-contain').hide();
            		$('.fh-row-city-contain div').empty();
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
	        		$('.fh-row-district-contain').show();
	                $('.fh-row-district-contain div').html(data);
            	}else{
            		$('.fh-row-district-contain').hide();
            		$('.fh-row-district-contain div').empty();
            	}
            	$('body').unloading();
            },
            dataType: 'html'
        });	
}