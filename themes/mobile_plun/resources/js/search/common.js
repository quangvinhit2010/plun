$(function(){
	
	if($('ul.explode').attr('class')){
		action_find_usersettings();
	}	
	//quicksearch
	if($('ul.quicksearch').attr('class')){
		action_find_quicksearch();
	}
	
	//getLocation();
	
});
/*
 * quick search
 */
function action_find_quicksearch(){
    $('#showmore_type').val('quicksearch');
    $("ul.list_user").empty();
    $("body").loading();
    var data = {
    		offset: 0,
    		q:	$("#quicksearch_keyword").val()
    };
    get_find_quicksearch(data);
    return false;    
}
function get_find_quicksearch(data){
    $.ajax({
        type: "POST",
        data: data,
        url: '/search/quicksearch',
        success: function(data) {
        	$("body").unloading();
        	
        	 $("ul.list_user").append(data.html);
            
            var findhim_str	=	tr('members are matching &1 your requirements &2', '<a title="" class="area" href="javascript:void(0);" onclick="openSearchPopup();">', '<span></span></a>');
            
            $(".search_result p").html('<span class="result_total">'+data.total+
            		'</span> ' + findhim_str);
            
            if (data.show_more) {
                $('.show-more-searchresult').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.show-more-searchresult').hide();
            }
            //process with scrollbar
            //$(".main .col-right .members .list").mCustomScrollbar("destroy");
            //sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.offset){
    			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}

            //close popup
            $('.find-him-pop').hide();
        },
        dataType: 'json'
    });
    return false;	
}
function get_find_location(data) {
	var area = '';
    var tmpArea = new Array();
    $("#wrapper_header").show();
    if($(".fh-district").val() != '' && $(".fh-district").attr('class')){
    	tmpArea.push($(".fh-district option:selected").text());
    }    
    if($(".fh-city").val() != '' && $(".fh-city").attr('class')){
    	tmpArea.push($(".fh-city option:selected").text());
    }
    if($(".fh-state").val() != '' && $(".fh-state").attr('class')){
    	tmpArea.push($(".fh-state option:selected").text());
    }    
    if($(".fh-country").val() != ''){
    	tmpArea.push($(".fh-country option:selected").text());
    }

    if(tmpArea.length > 0 && $(".fh-country").val() != '0'){
    	area = tmpArea.join(', ');
    }
	
	$('.popup_search_location').hide();
	
	$.ajax({
        type: "POST",
        data: data,
        url: '/search/searchlocation',
        success: function(data) {
            //$(".main .col-right .members .list").html(data.html);
            $("body").unloading();
            //$(".main .col-right").html(data);
            $("ul.list_user").append(data.html);
            
            if(!data.search_global){                
            	var findhim_str	=	tr('members in &1 &2 &3 matched your criteria', '<a title="" class="area" href="javascript:void(0);" onclick="openSearchPopup();">', area, '<span></span></a>');
            }else{
            	var findhim_str	=	tr('members are online &1worldwide&2','<a title="" class="area" href="javascript:void(0);" onclick="openSearchPopup();">', '<span></span></a>');

            }
            
            $(".search_result p").html('<span class="result_total">'+format_number_commas(data.total, true)+
            		'</span> ' + findhim_str);
            
            if (data.show_more) {
                $('.show-more-searchresult').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.show-more-searchresult').hide();
            }
            	
            $('.search_result').show();
            
            
            
            //process with scrollbar
            //$(".main .col-right .members .list").mCustomScrollbar("destroy");
            //sprScroll('.main .col-right .members .list');
            /*
            if($('#showmore_next_offset').val() != data.limit){
    			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}
			*/
            //close popup
            $('.find-him-pop').hide();
        },
        dataType: 'json'
    });
}
//collect data for search location
function find_location(){
	var data 	=	{};
	if($('.fh-country').val()){
		data['country_id']	=	$('.fh-country').val();
	}
	if($('.fh-state').val()){
		data['state_id']	=	$('.fh-state').val();
	}
	if($('.fh-city').val()){
		data['city_id']	=	$('.fh-city').val();
	}	
	if($('.fh-district').val()){
		data['district_id']	=	$('.fh-district').val();
	} 
	//have avatar
	
    if ($('#with_profile_pic').is(':checked')) {
    	data['have_avatar']	=	1;
    }
    //add sexuality
    var sexuality = new Array();
    $('.sexuality').each(function(k, v) {
        if ($(v).is(':checked')) {
        	sexuality.push($(v).val());
        }
    });     
    //add sex roles (top center)
    var sex_role = new Array();
    $('.sex-role').each(function(k, v) {
        if ($(v).is(':checked')) {
            sex_role.push($(v).val());
        }
    });
    var looking_for	=	new Array();
    $('.looking-for').each(function(k, v) {
        if ($(v).is(':checked')) {
        	looking_for.push($(v).val());
        }
    });   
    
    var looking_foronline	=	new Array();
    $('.looking-for-online').each(function(k, v) {
        if ($(v).is(':checked')) {
        	looking_foronline.push($(v).val());
        }
    }); 
    
    if (sexuality.length > 0) {
        data['sexuality'] = sexuality;
    }    
    if (sex_role.length > 0) {
        data['sex_role'] = sex_role;
    }
    if (looking_for.length > 0) {
        data['looking_for'] = looking_for;
    }
    if (looking_foronline.length > 0) {
        data['looking_foronline'] = looking_foronline;
    }    
    data['offset'] = $('#showmore_next_offset').val();
    var patt_age = /^[0-9]{2,3}$/;
    //add age
    if ($('#txt-age-from').val() != '') {
        if (!patt_age.test($('#txt-age-from').val())) {
            
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000); 
	        
            $("body").unloading();
            return false;
        }
        $('#txt-age-from').focus();
        data['txt-age-from'] = $('#txt-age-from').val();
    }
    if ($('#txt-age-to').val() != '') {
        if (!patt_age.test($('#txt-age-to').val())) {
            
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000); 
	        
            $("body").unloading();
            return false;
        }
        $('#txt-age-to').focus();
        data['txt-age-to'] = $('#txt-age-to').val();
    }

    var patt_size = /^[0-9,\.]{1,5}$/;

    //add weight
    if ($('#weight-from').val() != '') {
        //validate input weight from
        if (!patt_size.test($('#weight-from').val())) {
            
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000); 
	        
            $('#weight-from').focus();
            $("body").unloading();
            return false;
        }
        data['weight-from'] = $('#weight-from').val();
        data['weight-from'] = data['weight-from'].replace(",", ".");
    }
    if ($('#weight-to').val() != '') {
        if (!patt_size.test($('#weight-to').val())) {
            
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000); 
	        
            $('#weight-to').focus();
            $("body").unloading();
            return false;
        }
        data['weight-to'] = $('#weight-to').val();
        data['weight-to'] = data['weight-to'].replace(",", ".");
    }
    
    data['weight-unit'] = $('#txt-weight-unit').val();
    
    //add height
    if ($('#height-from').val() != '') {
        if (!patt_size.test($('#height-from').val())) {
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000);            
            
            $("body").unloading();
            return false;
        }
        data['height-from'] = $('#height-from').val();
        data['height-from'] = data['height-from'].replace(",", ".");
    }

    if ($('#height-to').val() != '') {
        if (!patt_size.test($('#height-to').val())) {
            
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000); 
	        
	        
            $("body").unloading();
            return false;
        }
        data['height-to'] = $('#height-to').val();
        data['height-to'] = data['height-to'].replace(",", ".");
    }
    data['height-unit'] = $('#txt-height-unit').val();
    
    if ($('#txt-relationship').val() != '') {
        data['txt-relationship'] = $('#txt-relationship').val();
    }    
    if ($('#txt-safe-sex').val() != '') {
        data['txt-safe-sex'] = $('#txt-safe-sex').val();
    }
    if ($('#txt-ethnics').val()) {
        data['txt-ethnics'] = $('#txt-ethnics').val();
    }
    if ($('#txt-body').val()) {
        data['txt-body'] = $('#txt-body').val();
    }    
    return data;
}
//when user click on search location buttom
function action_find_location() {
    $('#showmore_type').val('location');
    $(".main .col-right .members .list ul").empty();
    $("body").loading();
    $('#showmore_next_offset').val(0);
	var data 	=	{};
	data	=	find_location();
	data['offset']	=	0;
    get_find_location(data);
    return false;
}
function action_find_reference() {
    $('#showmore_type').val('reference');
    $(".main .col-right .members .list ul").empty();
    $("body").loading();
    $('#showmore_next_offset').val(0);
    get_find_reference();
    return false;
}
function get_find_reference() {
	$("body").loading();
    var data = {};

    //add sex roles (top center)
    var sex_role = new Array();
    $('.sex-role').each(function(k, v) {
        if ($(v).is(':checked')) {
            sex_role.push($(v).val());
        }
    });
    var looking_for	=	new Array();
    $('.looking-for').each(function(k, v) {
        if ($(v).is(':checked')) {
        	looking_for.push($(v).val());
        }
    });   
    
    var looking_foronline	=	new Array();
    $('.looking-for-online').each(function(k, v) {
        if ($(v).is(':checked')) {
        	looking_foronline.push($(v).val());
        }
    }); 
    
    if (sex_role.length > 0) {
        data['sex_role'] = sex_role;
    }
    if (looking_for.length > 0) {
        data['looking_for'] = looking_for;
    }
    if (looking_foronline.length > 0) {
        data['looking_foronline'] = looking_foronline;
    }    
    data['offset'] = $('#showmore_next_offset').val();
    var patt_age = /^[0-9]{2,3}$/;
    //add age
    if ($('#txt-age-from').val() != '') {
        if (!patt_age.test($('#txt-age-from').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $("body").unloading();
            return false;
        }
        $('#txt-age-from').focus();
        data['txt-age-from'] = $('#txt-age-from').val();
    }
    if ($('#txt-age-to').val() != '') {
        if (!patt_age.test($('#txt-age-to').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $("body").unloading();
            return false;
        }
        $('#txt-age-to').focus();
        data['txt-age-to'] = $('#txt-age-to').val();
    }

    var patt_size = /^[0-9,\.]{1,5}$/;

    //add weight
    if ($('#weight-from').val() != '') {
        //validate input weight from
        if (!patt_size.test($('#weight-from').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $('#weight-from').focus();
            $("body").unloading();
            return false;
        }
        data['weight-from'] = $('#weight-from').val();
        data['weight-from'] = data['weight-from'].replace(",", ".");
        data['weight-from-unit'] = $('#weight-from-unit').val();
    }
    if ($('#weight-to').val() != '') {
        if (!patt_size.test($('#weight-to').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $('#weight-to').focus();
            $("body").unloading();
            return false;
        }
        data['weight-to'] = $('#weight-to').val();
        data['weight-to'] = data['weight-to'].replace(",", ".");
        data['weight-to-unit'] = $('#weight-to-unit').val();
    }
    //add height
    if ($('#height-from').val() != '') {
        if (!patt_size.test($('#height-from').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $("body").unloading();
            return false;
        }
        data['height-from'] = $('#height-from').val();
        data['height-from'] = data['height-from'].replace(",", ".");
        data['height-from-unit'] = $('#height-from-unit').val();
    }

    if ($('#height-to').val() != '') {
        if (!patt_size.test($('#height-to').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $("body").unloading();
            return false;
        }
        data['height-to'] = $('#height-to').val();
        data['height-to'] = data['height-to'].replace(",", ".");
        data['height-to-unit'] = $('#height-to-unit').val();
    }

    if ($('#txt-relationship').val() != '') {
        data['txt-relationship'] = $('#txt-relationship').val();
    }    
    if ($('#txt-safe-sex').val() != '') {
        data['txt-safe-sex'] = $('#txt-safe-sex').val();
    }
    if ($('#txt-ethnics').val()) {
        data['txt-ethnics'] = $('#txt-ethnics').val();
    }

    $.ajax({
        type: "POST",
        data: data,
        url: '/search/searchreference',
        success: function(data) {
        	$("body").unloading();

            $(".main .col-right .members .list ul").append(data.html);
            var findhim_str	=	tr('Members are matching&1 your preferences &2', '<a title="" class="area" href="javascript:void(0);">', '</a>');
            
            $(".main .col-right .members h3 p").html('<span class="result_total">'+data.total+
            		'</span> ' + findhim_str + ' <a href="javascript:void(0);" class="reference" title=""></a>');
            if (data.show_more) {
                $('.show-more-searchresult').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.show-more-searchresult').hide();
            }
            //process with scrollbar
            //$(".main .col-right .members .list").mCustomScrollbar("destroy");
            //sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.limit){
    			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}

            //close popup
            $(".pop-cont").hide();
        },
        dataType: 'json'
    });
    return false;
}
function action_find_usersettings() {
    var data_input = {};
    data_input['offset'] = $('#showmore_next_offset').val();
    $("body").loading();
    $.ajax({
        type: "POST",
        data: data_input,
        url: '/search/SearchByUserSetting',
        success: function(data) {
        	$("body").unloading();
        	if(data){
        		$("ul.list_user").append(data.html);
        		
        		var area = '';
        	    var tmpArea = new Array();

        	    if(data.current_district_name != 0){
        	    	tmpArea.push(data.current_district_name);
        	    }
        	    if(data.current_city_name != 0){
        	    	tmpArea.push(data.current_city_name);
        	    } 
        	    if(data.current_state_name != 0){
        	    	tmpArea.push(data.current_state_name);
        	    }
        	    if(data.current_country_name != 0){
        	    	tmpArea.push(data.current_country_name);
        	    }         	    
        	       
        	    if(tmpArea.length > 0){
        	    	area = tmpArea.join(', ');
        	    } 
        	    
        		var findhim_str	=	tr('members in &1 &2 &3', '<a title="" class="area" href="javascript:void(0);" onclick="openSearchPopup();">', area, '<span></span></a>');
                
        	    $(".search_result p").html('<span class="result_total">'+format_number_commas(data.total, true)+
                		'</span> ' + findhim_str);
                
        	   // $(".search_result p").html('<a title="" class="area" href="javascript:void(0);" onclick="openSearchPopup();">' + tr('Nearby') + '<span></span></a>');
        	    
        		if (data.show_more) {
        			$('.show-more-searchresult').show();
        			$('#showmore_next_offset').val(data.offset);
        		} else {
        			$('.show-more-searchresult').hide();
        		}
        		//process with scrollbar
        		//$(".main .col-right .members .list").mCustomScrollbar("destroy");
        		//sprScroll('.main .col-right .members .list');
        		/*
        		if($('#showmore_next_offset').val() != data.limit){
        			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
        		}
        		*/
        	}
        },
        error: function () {
        	$("body").unloading();
        },
        dataType: 'json'
    });
    return false;
}
function showmore_searchresult() {
    var type = $('#showmore_type').val();
    switch (type) {
        case 'location':
        	$("body").loading();
            var data 	=	{};
            data	=	find_location();
        	data['offset']	=	$('#showmore_next_offset').val();
        	
            get_find_location(data);
            break;
        case 'quicksearch':
        	$("body").loading();
            var data = {
            		offset: $('#showmore_next_offset').val(),
            		q:	$("#quicksearch_keyword").val()
            };
            get_find_quicksearch(data);
            break;            
        case 'reference':
            get_find_reference();
            break;
        case 'checkin':
        	$("body").loading();
        	get_find_checkin();
        	break;
        case 'usersetting':
            action_find_usersettings();
            break;
    }
}
function get_find_checkin(){
	
	var data = {};
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
	data['offset'] = $('#showmore_next_offset').val();
		
	var area = '';
    var tmpArea = new Array();

    if($(".ci-district").val() != '' && $(".ci-district").attr('class')){
    	tmpArea.push($(".ci-district option:selected").text());
    }    
    if($(".ci-city").val() != '' && $(".ci-city").attr('class')){
    	tmpArea.push($(".ci-city option:selected").text());
    }
    if($(".ci-state").val() != '' && $(".ci-state").attr('class')){
    	tmpArea.push($(".ci-state option:selected").text());
    }    
    if($(".ci-country").val() != ''){
    	tmpArea.push($(".ci-country option:selected").text());
    }
       
    if(tmpArea.length > 0){
    	area = tmpArea.join(', ');
    }
    $.ajax({
        type: "POST",
        data: data,
        url: '/search/searchcheckin',
        success: function(data) {
            //$(".main .col-right .members .list").html(data.html);
            $("body").unloading();
            //$(".main .col-right").html(data);
            $(".main .col-right .members .list ul").append(data.html);
            
            var findhim_str	=	tr('members in &1 &2 &3', '<a title="" class="area" href="javascript:void(0);">', area, '</a>');
            $(".main .col-right .members h3 p").html('<span class="result_total">'+format_number_commas(data.total, true)+
            		'</span> ' + findhim_str + ' <a href="javascript:void(0);" class="reference" title=""></a>');
            
            if (data.show_more) {
                $('.show-more-searchresult').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.show-more-searchresult').hide();
            }

            //process with scrollbar
            //$(".main .col-right .members .list").mCustomScrollbar("destroy");
            //sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.limit){
    			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}

            //close popup
            $('.find-him-pop').hide();
        },
        dataType: 'json'
    });	
}
/**
 * change active status of search form
 * @param {object} curr_tab current tab
 * @param {string} class name
 */
function find_him_change(curr_tab, class_blockcontent) {
    $('.find_him_changetab li').removeClass('active');
    $(curr_tab).parent().addClass('active');
    $('.tab-cont').hide();
    $('.' + class_blockcontent).fadeIn();
}

function openSearchPopup(){
	$("ul.list_user").html('');
	$('.search_result').hide();
	$('.popup_search_location').show();
	$('.show-more-searchresult').hide();
}
/**
 * Geo Location
 */
function getLocation() {
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(setPositionToHiddenField, function showError(error) {
			switch(error.code) {
				case error.PERMISSION_DENIED:
					console.log("User denied the request for Geolocation.");
					break;
				case error.POSITION_UNAVAILABLE:
					alert("Location information is unavailable.");
					break;
				case error.TIMEOUT:
					alert("The request to get user location timed out.");
					break;
				case error.UNKNOWN_ERROR:
					alert("An unknown error occurred.");
					break;
			}
	  });
	navigator.geolocation.getCurrentPosition(setPositionToHiddenField);
 	} else {
 		alert("Geolocation is not supported by this browser.");
 	}
}

function setPositionToHiddenField(position) {
	updatePosition(position.coords.latitude, position.coords.longitude);
	$('#lat').val(position.coords.latitude);
	$('#lng').val(position.coords.longitude);
}
function updatePosition(latitude, longitude){
	var data	=	{
			'UsrProfileSettings[latitude]': latitude,
			'UsrProfileSettings[longitude]': longitude
	};
    $.ajax({
    	data: data,
        type: "POST",
        url: '/search/UpdatePosition',
        success: function(data) {
        },
        dataType: 'html'

    });
}
function format_number_commas(somenum,usa){
	  var dec = String(somenum).split(/[.,]/)
	     ,sep = usa ? ',' : '.'
	     ,decsep = usa ? '.' : ',';

	  return xsep(dec[0],sep) + (dec[1] ? decsep+dec[1] :'');

	  function xsep(num,sep) {
	    var n = String(num).split('')
	       ,i = -3;
	    while (n.length + i > 0) {
	        n.splice(i, 0, sep);
	        i -= 4;
	    }
	    return n.join('');
	  }
}