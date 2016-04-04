$(document).ready(function() {
	
});

function changeMeasurement(){
	$('.measurement').click(function(){
		$('.measurement').prop('checked', false); 
		$(this).prop('checked', true); 
	});
}
function getAccountSettings(menu){
	
	$("body").loading();
	$('.profile_setting_page .sub_main li').removeClass('active');
	$('.profile_setting_page .profile_settings').removeClass('active');
	$('.profile_setting_page .account_settings').addClass('active');
	if(!$(menu).parent().hasClass('active')){
		$(menu).parent().addClass('active');
	}
	var href = $(menu).attr('link');
    $.ajax({
        type: "GET",
        url: href,
        success: function(data) {
    		$('.edit_profile_form').html(data);
    		$("body").unloading();
        },
        dataType: 'html'
    });	
}

function languageSettings(menu){
	$("body").loading();
	$('.profile_setting_page .sub_main li').removeClass('active');
	$('.profile_setting_page .profile_settings').removeClass('active');
	$('.profile_setting_page .account_settings').addClass('active');
	if(!$(menu).parent().hasClass('active')){
		$(menu).parent().addClass('active');
	}
	var href = $(menu).attr('link');
	$.ajax({
		type: "GET",
		url: href,
		success: function(data) {
			$('.edit_profile_form').html(data);
			$("body").unloading();
			changeMeasurement();
		},
		dataType: 'html'
	});	
}

function saveLanguageSettings(){
	$("body").loading();
	var measurement	=	$("input[class='measurement']:checked");
    var data    =   {
        'UsrProfileSettings[default_language]': $('#UsrProfileSettings_default_language').val(),
        'UsrProfileSettings[measurement]': $(measurement).val()
    };
       
    $.ajax({
        type: "POST",
        data: data,
        url: '/settings/languageSettings',
        success: function(data) {
        	$("body").unloading();
            if(data){
    	    	Util.popAlertSuccess(tr('Data saved successfully!'), 300);
    	        setTimeout(function () {
    	         $( ".pop-mess-succ" ).pdialog('close');
    	        }, 2000);     	        
            }
        },
        dataType: 'html'
    });

    return false;
}
function getExtraForm(menu){
	
	$("body").loading();
	var href = $(menu).attr('link');
	$('.profile_setting_page .sub_main li').removeClass('active');
	$('.profile_setting_page .profile_settings').addClass('active');
	$('.profile_setting_page .account_settings').removeClass('active');
	
	if(!$(menu).parent().hasClass('active')){
		$(menu).parent().addClass('active');
	}
    $.ajax({
        type: "GET",
        url: href,
        success: function(data) {
    		$('.edit_profile_form').html(data);
    		$("body").unloading();
    		sprScroll('.setting_col1');
    		sprScroll('.setting_col2');
        },
        dataType: 'html'
    });	
}
function getWhatYouSeeForm(menu){
	$("body").loading();
	$('.profile_setting_page .sub_main li').removeClass('active');
	$('.profile_setting_page .profile_settings').addClass('active');
	$('.profile_setting_page .account_settings').removeClass('active');
	if(!$(menu).parent().hasClass('active')){
		$(menu).parent().addClass('active');
	}
	var href = $(menu).attr('link');
    $.ajax({
        type: "GET",
        url: href,
        success: function(data) {
    		$('.edit_profile_form').html(data);
    		$("body").unloading();
    		
        },
        dataType: 'html'
    });	
}
function avatarSettings(menu){
	
	$("body").loading();
	var href = $(menu).attr('link');
	$('.profile_setting_page .sub_main li').removeClass('active');
	$('.profile_setting_page .profile_settings').addClass('active');
	$('.profile_setting_page .account_settings').removeClass('active');
	if(!$(menu).parent().hasClass('active')){
		$(menu).parent().addClass('active');
	}
    $.ajax({
        type: "GET",
        url: href,
        success: function(data) {
    		$('.edit_profile_form').html(data);
    		$("body").unloading();
    		sprScroll('.setting_col1');
        },
        dataType: 'html'
    });	
}
function getBasicForm(menu){
	$("body").loading();
	var href = $(menu).attr('link');
	$('.profile_setting_page .sub_main li').removeClass('active');
	$('.profile_setting_page .profile_settings').addClass('active');
	$('.profile_setting_page .account_settings').removeClass('active');
	if(!$(menu).parent().hasClass('active')){
		$(menu).parent().addClass('active');
	}
    $.ajax({
        type: "GET",
        url: href,
        success: function(data) {
    		$('.edit_profile_form').html(data);
    		$("body").unloading();
    		sprScroll('.setting_col1');
        },
        dataType: 'html'
    });	
}

function save_settings_extra(){
	$("body").loading();
    var data = {
            'UsrProfileSettings[occupation]': $('#UsrProfileSettings_occupation').val(),
            'UsrProfileSettings[religion]': $('#UsrProfileSettings_religion').val(),
            'UsrProfileSettings[mannerism]': $('#UsrProfileSettings_mannerism').val(),
            'UsrProfileSettings[smoke]': $('#UsrProfileSettings_smoke').val(),
            'UsrProfileSettings[about_me]': $('#about_me').val(),
            'UsrProfileSettings[drink]': $('#UsrProfileSettings_drink').val(),
            'UsrProfileSettings[safer_sex]': $('#UsrProfileSettings_safer_sex').val(),
            'UsrProfileSettings[club]': $('#UsrProfileSettings_club').val(),
            'UsrProfileSettings[public_information]': $('#UsrProfileSettings_public_information').val(),
            'UsrProfileSettings[live_with]': $('#UsrProfileSettings_live_with').val()
    };
    //for attributes
    var my_attributes = new Array();
    $('.my_attributes').each(function(k, v) {
    	if($(this).is(':checked')){
    		my_attributes.push($(this).val());
    	}
    });
    if (my_attributes.length > 0) {
        data['UsrProfileSettings[my_attributes]'] = my_attributes;
    }  
    
    //for my types
    var my_types = new Array();
    $('.my_types').each(function(k, v) {
    	if($(this).is(':checked')){
    		my_types.push($(this).val());
    	}
    });
    if (my_types.length > 0) {
        data['UsrProfileSettings[my_types]'] = my_types;
    }
    
    //for my Stuff
    var my_stuff = new Array();
    $('.my_stuff').each(function(k, v) {
    	if($(this).is(':checked')){
    		my_stuff.push($(this).val());
    	}
    });
    if (my_stuff.length > 0) {
        data['UsrProfileSettings[into_stuff]'] = my_stuff;
    }    
    
    //for hobbies
    var hobbies = new Array();
    $('.hobbies_tag a').each(function(k, v) {
        hobbies.push($(v).find('label').text());
    });
    if (hobbies.length > 0) {
        data['UsrProfileSettings[hobbies]'] = hobbies;
    }
    
    $.ajax({
        type: "POST",
        data: data,
        url: '/settings/SaveExtra',
        success: function(dt) {
	    	Util.popAlertSuccess(tr('Data saved successfully!'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000);   	
        	$("body").unloading();
        },
        dataType: 'json'
    });

    return false;     
}
function save_settings_whatyousee(){
	$("body").loading();
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
            $("body").unloading();
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
            $("body").unloading();
            return false;
        }
        var weight = $('#UsrProfileSettings_weight').val().replace(",", ".");
        $('#UsrProfileSettings_weight').val(weight);
    }
    
    var data = {
            'UsrProfileSettings[body]': $('#UsrProfileSettings_body').val(),
            'UsrProfileSettings[body_hair]': $('#UsrProfileSettings_body_hair').val(),
            'UsrProfileSettings[tattoo]': $('#UsrProfileSettings_tattoo').val(),
            'UsrProfileSettings[piercings]': $('#UsrProfileSettings_piercings').val(),
            'UsrProfileSettings[dick_size]': $('#UsrProfileSettings_dick_size').val(),
            'UsrProfileSettings[height]': $('#UsrProfileSettings_height').val(),
            'UsrProfileSettings[weight]': $('#UsrProfileSettings_weight').val(),  
            'UsrProfileSettings[measurement]': $('#UsrProfileSettings_measurement').val()
    };	
    $.ajax({
        type: "POST",
        data: data,
        url: '/settings/SaveWhatYouSee',
        success: function(dt) {
	    	Util.popAlertSuccess(tr('Data saved successfully!'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000);   	
        	$("body").unloading();
        },
        dataType: 'json'
    });

    return false;    
}
function save_settings_basicinfo(){
	$("body").loading();
    var data = {
            'UsrProfileSettings[sex_role]': $('#UsrProfileSettings_sex_role').val(),
            'UsrProfileSettings[country_id]': $('#UsrProfileSettings_country_id').val(),
            'UsrProfileSettings[state_id]': $('#UsrProfileSettings_state_id').val(),
            'UsrProfileSettings[city_id]': $('#UsrProfileSettings_city_id').val(),
            'UsrProfileSettings[district_id]': $('#UsrProfileSettings_district_id').val(),
            'UsrProfileSettings[sex_role]': $('#UsrProfileSettings_sex_role').val(),
            'UsrProfileSettings[sexuality]': $('#UsrProfileSettings_sexuality').val(),
            'UsrProfileSettings[birthday_month]': $('#UsrProfileSettings_birthday_month').val(),
            'UsrProfileSettings[birthday_day]': $('#UsrProfileSettings_birthday_day').val(),
            'UsrProfileSettings[birthday_year]': $('#UsrProfileSettings_birthday_year').val(),
            'UsrProfileSettings[relationship]': $('#UsrProfileSettings_relationship').val(),            
            'UsrProfileSettings[ethnic_id]': $('#UsrProfileSettings_ethnic_id').val()
    };	
    
    var languages = new Array();
    //for language
    $('.languages').each(function(k, v) {
    	if($(this).is(':checked')){
    		languages.push($(this).val());
    	}
    });
    if (languages.length > 0) {
        data['UsrProfileSettings[languages]'] = languages;
    } 
    
    var looking_for = new Array();
    //for looking for
    $('.looking_for').each(function(k, v) {
    	if($(this).is(':checked')){
    		looking_for.push($(this).val());
    	}
    });
    if (looking_for.length > 0) {
        data['UsrProfileSettings[looking_for]'] = looking_for;
    }    
    $.ajax({
        type: "POST",
        data: data,
        url: '/settings/SaveBasicInfo',
        success: function(dt) {
	    	Util.popAlertSuccess(tr('Data saved successfully!'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000);   	
        	$("body").unloading();
        },
        dataType: 'json'
    });

    return false;    
}

function save_settings() {
	$("body").loading();
    var patt_size = /^[0-9,\.]{1,5}$/;

    //add height
    if ($('#UsrProfileSettings_height').val() != '') {
        //validate input weight from
        if (!patt_size.test($('#UsrProfileSettings_height').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $('#UsrProfileSettings_height').focus();
            $("body").unloading();
            return false;
        }
        var height = $('#UsrProfileSettings_height').val().replace(",", ".");
        $('#UsrProfileSettings_height').val(height);
    }

    //add weight
    if ($('#UsrProfileSettings_weight').val() != '') {
        //validate input weight from
        if (!patt_size.test($('#UsrProfileSettings_weight').val())) {
            alert(tr('Please input a integer with correct format(0-9)'));
            $('#UsrProfileSettings_weight').focus();
            $("body").unloading();
            return false;
        }
        var weight = $('#UsrProfileSettings_weight').val().replace(",", ".");
        $('#UsrProfileSettings_weight').val(weight);
    }

    var data = {
        'UsrProfileSettings[sex_role]': $('#UsrProfileSettings_sex_role').val(),
        'UsrProfileSettings[smoke]': $('.settings_smoke:checked').val(),
        'UsrProfileSettings[s_and_m]': $('.settings_s_and_m:checked').val(),
        'UsrProfileSettings[tattoo]': $('.settings_s_and_m:checked').val(),
        'UsrProfileSettings[unit_weight]': $('#UsrProfileSettings_unit_weight').val(),
        'UsrProfileSettings[unit_height]': $('#UsrProfileSettings_unit_height').val(),
        'UsrProfileSettings[birthday_month]': $('#UsrProfileSettings_birthday_month').val(),
        'UsrProfileSettings[birthday_day]': $('#UsrProfileSettings_birthday_day').val(),
        'UsrProfileSettings[birthday_year]': $('#UsrProfileSettings_birthday_year').val(),
        'UsrProfileSettings[height]': $('#UsrProfileSettings_height').val(),
        'UsrProfileSettings[weight]': $('#UsrProfileSettings_weight').val(),
        'UsrProfileSettings[safer_sex]': $('#UsrProfileSettings_safer_sex').val(),
        'UsrProfileSettings[country_id]': $('#UsrProfileSettings_country_id').val(),
        'UsrProfileSettings[state_id]': $('#UsrProfileSettings_state_id').val(),
        'UsrProfileSettings[city_id]': $('#UsrProfileSettings_city_id').val(),
        'UsrProfileSettings[district_id]': $('#UsrProfileSettings_district_id').val(),
        'UsrProfileSettings[ethnic_id]': $('#UsrProfileSettings_ethnic_id').val(),
        'UsrProfileSettings[body]': $('#UsrProfileSettings_body').val(),
        'UsrProfileSettings[dick_size]': $('#UsrProfileSettings_dick_size').val(),
        'UsrProfileSettings[about_me]': $('#UsrProfileSettings_about_me').val(),
        'UsrProfileSettings[sexuality]': $('#UsrProfileSettings_sexuality').val(),
        'UsrProfileSettings[relationship]': $('#UsrProfileSettings_relationship').val(),
        'UsrProfileSettings[drink]': $('#UsrProfileSettings_drink').val(),
        'UsrProfileSettings[club]': $('#UsrProfileSettings_club').val(),
        'UsrProfileSettings[piercings]': $('#UsrProfileSettings_piercings').val(),
        'UsrProfileSettings[occupation]': $('#UsrProfileSettings_occupation').val(),
        'UsrProfileSettings[religion]': $('#UsrProfileSettings_religion').val(),
        'UsrProfileSettings[cut]': $('#UsrProfileSettings_cut').val(),
        'UsrProfileSettings[live_with]': $('#UsrProfileSettings_live_with').val(),
        'UsrProfileSettings[body_hair]': $('#UsrProfileSettings_body_hair').val(),
        'UsrProfileSettings[public_information]': $('#UsrProfileSettings_public_information').val(),
        'UsrProfileSettings[mannerism]': $('#UsrProfileSettings_mannerism').val()
    };

    var hobbies = new Array();
    var languages = new Array();
    var looking_for	=	new Array();
    var attributes = new Array();
    var my_types	=	new Array();
    var my_stuff = new Array();
    
    //for my_stuff
    $('.my_stuff .items div').each(function(k, v) {
    	my_stuff.push($(this).attr('data-value'));
    });
    if (my_stuff.length > 0) {
        data['UsrProfileSettings[into_stuff]'] = my_stuff;
    }
    
    //for my_types
    $('.my_types .items div').each(function(k, v) {
    	my_types.push($(this).attr('data-value'));
    });
    if (my_types.length > 0) {
        data['UsrProfileSettings[my_types]'] = my_types;
    }
    
    //for attributes
    $('.attributes .items div').each(function(k, v) {
    	attributes.push($(this).attr('data-value'));
    });
    if (attributes.length > 0) {
        data['UsrProfileSettings[my_attributes]'] = attributes;
    }    
    //for hobbies
    $('.hobbies_tag a').each(function(k, v) {
        hobbies.push($(v).find('label').text());
    });
    if (hobbies.length > 0) {
        data['UsrProfileSettings[hobbies]'] = hobbies;
    }
    
    //for language
    $('.demo_i_understand .items div').each(function(k, v) {
    	languages.push($(this).attr('data-value'));
    });
    if (languages.length > 0) {
        data['UsrProfileSettings[languages]'] = languages;
    }    
    
    //for looking for
    $('.looking_for .items div').each(function(k, v) {
    	looking_for.push($(this).attr('data-value'));
    });
    if (looking_for.length > 0) {
        data['UsrProfileSettings[looking_for]'] = looking_for;
    }
    

    $.ajax({
        type: "POST",
        data: data,
        url: '/settings/save',
        success: function(dt) {
        	$("body").unloading();
        },
        dataType: 'json'
    });

    return false;
}
function save_account_settings(){ 
	$("body").loading();
    if($('#password').val() == ''){
    	Util.popAlertSuccess(tr('please input password'), 300);
        setTimeout(function () {
         $( ".pop-mess-succ" ).pdialog('close');
        }, 2000); 
        $("body").unloading();
        return false;
    }
    if($('#new_password').val() != '' || $('#re_new_password').val() != ''){
        if($('#new_password').val() != $('#re_new_password').val()){
        	Util.popAlertSuccess(tr('Passwords do not match'), 300);
            setTimeout(function () {
             $( ".pop-mess-succ" ).pdialog('close');
            }, 2000);
            $("body").unloading();
            return false;
        }
    }
    var data    =   {
        'YumProfile[email]':    $('#YumProfile_email').val(),
        'Member[password]': $('#password').val(),
        'Member[new_password]': $('#new_password').val()
    };
       
    $.ajax({
        type: "POST",
        data: data,
        url: '/settings/SaveAccountSettings',
        success: function(data) {
        	$("body").unloading();
            if(data.status == '0'){ 
    	    	Util.popAlertSuccess(data.msg.join("\n"), 300);
    	        setTimeout(function () {
    	         $( ".pop-mess-succ" ).pdialog('close');
    	        }, 2000);            	
            }else{
    	    	Util.popAlertSuccess(tr('Data saved successfully!'), 300);
    	        setTimeout(function () {
    	         $( ".pop-mess-succ" ).pdialog('close');
    	        }, 2000);     	        
            }
        },
        dataType: 'json'
    });

    return false;    
}

function changeUnit(select){
	var index_height = $('#UsrProfileSettings_height option:selected').index();
	var index_weight = $('#UsrProfileSettings_weight option:selected').index();
	
	_updateListUnitValue($(select).val(), index_height, index_weight);
}
function _updateListUnitValue(type, index_height, index_weight){
	if(type == '1'){
		//update weight 
		$('.unit_weight_kg option').eq(index_weight).attr('selected', 'selected');
		var html_weight	=	$('.unit_weight_kg').html();
		//update height
		$('.unit_height_cm option').eq(index_height).attr('selected', 'selected');
		var html_height =   $('.unit_height_cm').html();
	}else{
		//update weight 
		$('.unit_weight_lbs option').eq(index_weight).attr('selected', 'selected');
		var html_weight	=	$('.unit_weight_lbs').html();
		//update height
		$('.unit_height_ft option').eq(index_height).attr('selected', 'selected');
		var html_height =   $('.unit_height_ft').html();
	}
	$('#UsrProfileSettings_weight').html(html_weight);
	$('#UsrProfileSettings_height').html(html_height);
	
	
	$('.height_text').text($('#UsrProfileSettings_height option:selected').val());
	$('.weight_text').text($('#UsrProfileSettings_weight option:selected').val());
	
	$('#UsrProfileSettings_unit_weight [value=' + type + ']').prop('selected', true);
	$('#UsrProfileSettings_unit_height [value=' + type + ']').prop('selected', true);
	
	$('.unit_weight_text').text($('#UsrProfileSettings_unit_weight option:selected').text());
	$('.unit_height_text').text($('#UsrProfileSettings_unit_height option:selected').text());
	$('#UsrProfileSettings_measurement').val(type);
}
