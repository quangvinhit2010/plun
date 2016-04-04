/*
 * quick search
 */
function action_find_quicksearch(){
    $('#showmore_type').val('quicksearch');
    $(".main .col-right .members .list ul").empty();
    objCommon.loading();
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
        	objCommon.unloading();
            
            $(".list_explore .suggest-user-settings ul").append(data.html);
            
            if (data.show_more) {
                $('.pagging').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.pagging').hide();
            }
            
            $('.list_explore .list_user ul li').boxResizeImg({
                wrapContent: true
            });
            $('.sticky_column').fixed_col_scroll.callbackFun();
            
            //close popup
            $("#pop-find-him").hide();
        },
        dataType: 'json'
    });
    return false;	
}
function get_find_location(data) {
	var area = '';
    var tmpArea = new Array();

    if($(".fh-district").val() != '' && $(".fh-district").val() != '0' && $(".fh-district option").length){
    	tmpArea.push($(".fh-district option:selected").text());
    }    
    if($(".fh-city").val() != '' && $(".fh-city").val() != '0' && $(".fh-city option").length){
    	tmpArea.push($(".fh-city option:selected").text());
    }
    if($(".fh-state").val() != '' && $(".fh-state").val() != '0' && $(".fh-state option").length){
    	tmpArea.push($(".fh-state option:selected").text());
    }    
    if($(".findhim-country").val() != '0'){
    	tmpArea.push($(".findhim-country option:selected").text());
    }

    if(tmpArea.length > 0 && $(".findhim-country").val() != '0'){
    	area = tmpArea.join(', ');
    }
    $.ajax({
        type: "POST",
        data: data,
        url: '/search/searchlocation',
        success: function(data) {
        	objCommon.unloading();
            //close popup
            $("#pop-find-him").hide();
            
            if(!data.search_global){
            	var findhim_str	=	tr('members in &1 &2 &3 matched your criteria', '<a href="javascript:void(0);"><ins>', area, '</ins></a>');
            }else{
            	var findhim_str	=	tr('members are online &1worldwide&2', '<a href="javascript:void(0);"><ins>', '</ins></a>');
            }
            $(".list_explore .online_num label").html('<span class="result_total">' + format_number_commas(data.total, true) + 
            		' </span>' + findhim_str);
            
            $(".list_explore .suggest-user-settings ul").append(data.html);
            if (data.show_more) {
                $('.pagging').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.pagging').hide();
            }

            //process with scrollbar
            //$(".main .col-right .members .list").mCustomScrollbar("destroy");
            //sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.limit){
    			//$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}

            $('.list_explore .list_user ul li').boxResizeImg({
                wrapContent: true
            });
            $('.sticky_column').fixed_col_scroll.callbackFun();

        },
        dataType: 'json'
    });
}
//collect data for search location
function find_location(){
	var data 	=	{};
	if($('.findhim-country').val()){
		data['country_id']	=	$('.findhim-country').val();
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
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000);
	        
            $('#weight-from').focus();
            objCommon.unloading();
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
            objCommon.unloading();
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
	        
	        objCommon.unloading();
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
	        
	        objCommon.unloading();
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
    $(".list_explore .suggest-user-settings ul").empty();
    objCommon.loading();
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
    objCommon.loading();
    $('#showmore_next_offset').val(0);
    get_find_reference();
    return false;
}
function get_find_reference() {
	objCommon.loading();
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
    objCommon.loading();
    $.ajax({
        type: "POST",
        data: data_input,
        url: '/search/SearchByUserSetting',
        success: function(data) {
            objCommon.unloading();
        	if(data){
        		$('#pop-find-him').hide();

        		var $dataAppend = $(data.html);

                $(".list_explore .suggest-user-settings ul").append($dataAppend);
        		
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
        		var findhim_str	=	tr('members in &1 &2 &3', '<a href="javascript:void(0);"><ins>', area, '</ins></a>');
                $(".list_explore .online_num label").html('<span class="result_total">' + format_number_commas(data.total, true) + 
                		'</span> ' + findhim_str);
                
        		if (data.show_more) {
        			$('.pagging').show();
        			$('#showmore_next_offset').val(data.offset);
        		} else {
        			$('.pagging').hide();
        		}
        		//process with scrollbar
        		//$(".main .col-right .members .list").mCustomScrollbar("destroy");
        		//sprScroll('.main .col-right .members .list');
        		/*
        		if($('#showmore_next_offset').val() != data.limit){
        			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
        		}
        		*/
                $('.list_explore .list_user ul li').boxResizeImg({
                    wrapContent: true
                });
                $('.sticky_column').fixed_col_scroll.callbackFun();
            }
        },
        error: function () {
        	objCommon.unloading();
        },
        dataType: 'json'
    });
    return false;
}
function showmore_searchresult() {
    var type = $('#showmore_type').val();
    switch (type) {
        case 'location':
            objCommon.loading();
            var data 	=	{};
            data	=	find_location();
        	data['offset']	=	$('#showmore_next_offset').val();
        	
            get_find_location(data);
            break;
        case 'quicksearch':
        	objCommon.loading();
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
        	objCommon.loading();
        	get_find_checkin();
        	break;
        case 'usersetting':
            action_find_usersettings();
            break;
        case 'vietpride':
            action_find_vietpride();
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
	 
	data['offset'] = $('#showmore_next_offset').val();
		
	var area = '';
    var tmpArea = new Array();

    if($(".ci-district").val() != '' && $(".ci-district").attr('class') && $(".ci-district option:selected").text() != ''){
    	tmpArea.push($(".ci-district option:selected").text());
    }    
    if($(".ci-city").val() != '' && $(".ci-city").attr('class') && $(".ci-city option:selected").text()!= ''){
    	tmpArea.push($(".ci-city option:selected").text());
    }
    if($(".ci-state").val() != '' && $(".ci-state").attr('class') && $(".ci-state option:selected").text()!=''){
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
    		
        	$('#pop-find-him').hide();

    		var $dataAppend = $(data.html);
    		
        	$(".list_explore .suggest-user-settings ul").append($dataAppend);

    		var findhim_str	=	tr('members in &1 &2 &3', '<a href="javascript:void(0);"><ins>', area, '</ins></a>');
            $(".list_explore .online_num label").html('<span class="result_total">' + format_number_commas(data.total, true) + 
            		'</span> ' + findhim_str);
            
    		if (data.show_more) {
    			$('.pagging').show();
    			$('#showmore_next_offset').val(data.offset);
    		} else {
    			$('.pagging').hide();
    		}
            
            objCommon.unloading();

            //close popup
            $('.list_explore .list_user ul li').boxResizeImg({
                wrapContent: true
            });
                        
            $('.sticky_column').fixed_col_scroll.callbackFun();
            
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
function action_find_vietpride() {
    var data_input = {};
    data_input['offset'] = $('#showmore_next_offset').val();
    objCommon.loading();
    $.ajax({
        type: "POST",
        data: data_input,
        url: '/vietpride/showmore',
        success: function(data) {
            var wRealBox = $('.main .col-right .members .list ul li').outerWidth();
            $("body").unloading();
        	if(data){
                var $dataAppend = $(data.html);
                $dataAppend.css({
                    width: wRealBox+'px',
                    height: wRealBox+'px',
                    visibility: 'visible'
                });
        		$(".main .col-right .members .list ul").append($dataAppend);
                
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
        	}
        },
        error: function () {
        	objCommon.unloading();
        },
        dataType: 'json'
    });
    return false;
}