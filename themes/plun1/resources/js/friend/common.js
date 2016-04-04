$(function(){
	if($('.main .col-right .members .suggest-user-settings').attr('class')){
		action_find_usersettings();
	}
	if($('.main .col-right .members .quick-search-user').attr('class')){
		action_find_quicksearch();
	}	
});

/*
 * quick search
 */
function action_find_quicksearch(){
    $('#showmore_type').val('quicksearch');
    $(".main .col-right .members .list ul").empty();
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

            $(".main .col-right .members .list ul").append(data.html);
            $(".main .col-right .members h3 p").html('<span class="result_total">'+data.total+'</span> members are matching <a title="" class="area" href="#">your requirements</a> <a href="#" class="reference" title=""></a>');
            if (data.show_more) {
                $('.show-more-searchresult').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.show-more-searchresult').hide();
            }
            //process with scrollbar
            $(".main .col-right .members .list").mCustomScrollbar("destroy");
            sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.offset){
    			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}

            //close popup
            $(".pop-cont").hide();
        },
        dataType: 'json'
    });
    return false;	
}
/*
 * 
 * @param {object} obj object of selectbox city list
 * @returns {undefined}
 */
function getDistrict(city_class, district_class) {
    var data = {
        city_id: $('.' + city_class + ' option:selected').val()
    };
    $.ajax({
        type: "GET",
        data: data,
        url: '/my/getdistrictlist',
        success: function(data) {
            $('.' + district_class).html(data);
        },
        dataType: 'html'
    });
}
function get_find_location(data) {
	var area = 'your area';
    var tmpArea = [];
    var district	=	$("#findhim-district").find(':selected');

    /*
    if(district.length > 0){
    	$(district).each(function(val, el){
    		if(val <= 3 && $(el).val() != ''){
    			if(val == 3){
    				tmpArea.push($(el).text() + '...');
    			}else{
    				tmpArea.push($(el).text());
    			}
    		}
    	});
    }
	*/
    if($("#findhim-district").val() != '' && $("#findhim-district").attr('id')){
    	tmpArea.push($("#findhim-district option:selected").text());
    }    
    if($("#findhim-city").val() != '' && $("#findhim-city").attr('id')){
    	tmpArea.push($("#findhim-city option:selected").text());
    }
    if($("#findhim-state").val() != '' && $("#findhim-state").attr('id')){
    	tmpArea.push($("#findhim-state option:selected").text());
    }    
    if($("#findhim-country").val() != ''){
    	tmpArea.push($("#findhim-country").find(':selected').text());
    }
    if(tmpArea.length > 0){
    	area = tmpArea.join(', ');
    }
    $.ajax({
        type: "POST",
        data: data,
        url: '/search/searchlocation',
        success: function(data) {
            //$(".main .col-right .members .list").html(data.html);
            $("body").unloading();
            //$(".main .col-right").html(data);
            $(".main .col-right .members .list ul").append(data.html);
            $(".main .col-right .members h3 p").html('<span class="result_total">'+data.total+'</span> members are in <a title="" class="area" href="#">'+area+'</a> <a href="#" class="reference" title=""></a>');
            if (data.show_more) {
                $('.show-more-searchresult').show();
                $('#showmore_next_offset').val(data.offset);
            } else {
                $('.show-more-searchresult').hide();
            }

            //process with scrollbar
            $(".main .col-right .members .list").mCustomScrollbar("destroy");
            sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.offset){
    			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
    		}

            //close popup
            $(".pop-cont").hide();
        },
        dataType: 'json'
    });
}
function action_find_location() {
    $('#showmore_type').val('location');
    $(".main .col-right .members .list ul").empty();
    $("body").loading();
    var data = {
        district_id: $("#findhim-district").val(),
        city_id: $("#findhim-city").val(),
        country_id: $("#findhim-country").val(),
        state_id: $("#findhim-state").val(),
        offset: 0
    };
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
            $(".main .col-right .members .list").mCustomScrollbar("destroy");
            sprScroll('.main .col-right .members .list');
            if($('#showmore_next_offset').val() != data.offset){
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
    var data = {};
    data['offset'] = $('#showmore_next_offset').val();
    $("body").loading();
    $.ajax({
        type: "POST",
        data: data,
        url: '/search/SearchByUserSetting',
        success: function(data) {
        	$("body").unloading();
        	if(data){
        		$(".main .col-right .members .list ul").append(data.html);
        		$(".main .col-right .members .result_total").html(data.total);
        		if (data.show_more) {
        			$('.show-more-searchresult').show();
        			$('#showmore_next_offset').val(data.offset);
        		} else {
        			$('.show-more-searchresult').hide();
        		}
        		//process with scrollbar
        		$(".main .col-right .members .list").mCustomScrollbar("destroy");
        		sprScroll('.main .col-right .members .list');
        		if($('#showmore_next_offset').val() != data.offset){
        			$('.main .col-right .members .list').mCustomScrollbar("scrollTo", "bottom");
        		}
        		
        		//close popup
        		$('.pop-status .btn-close').trigger('click');
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
            var data = {
                district_id: $("#findhim-district").val(),
                city_id: $("#findhim-city").val(),
                state_id: $("#findhim-state").val(),
                country_id: $("#findhim-country").val(),
                offset: $('#showmore_next_offset').val()
            };
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
        case 'usersetting':
            action_find_usersettings();
            break;
    }
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

var suggestbox = null;
var list_hobbies = null;
$(document).ready(function() {
    //getHobbies();
});
function getHobbies() {
    $.ajax({
        type: "GET",
        url: '/hobby/gethobbies',
        success: function(data) {
            list_hobbies = data;
            suggestbox = $(".hobbies_tag").autoCompletefb({urlLookup: list_hobbies});
        },
        dataType: 'json'
    });
}
function collect_hobbies() {
    var hobbies = $('#txt_hobbies_input').val();
    if (hobbies.length >= 3) {
        var hobby_arr = hobbies.split(',');
        var html = '';
        $(hobby_arr).each(function(k, v) {
            html += '<a href="javascript:void(0);" title="' + v + '"><label class="text">' + $.trim(v) + '</label><span class="p">X</span></a>';
        });
        $('#txt_hobbies_input').before(html);
        $('#txt_hobbies_input').val('');
        $('.p').live('click', function() {
            suggestbox.removeFind(this);
        });
    }
}


function save_settings() {
	$("body").loading();
	$('.squaredCheck label').each(function(e, v){
		console.log(e);
	});
	return false;
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
