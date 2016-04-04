$(function(){
	Register.checkBoxAction();
});

function save_fill_out(){
    var patt_size = /^[0-9,\.]{1,5}$/;
    var data = $('#frmFillOut').serialize();
    
    //add height
    if ($('#UsrProfileSettings_height').val() != '') {
        //validate input weight from
        if (!patt_size.test($('#UsrProfileSettings_height').val())) {
        	
	    	Util.popAlertSuccess(tr('Please input a integer with correct format(0-9)'), 300);
	        setTimeout(function () {
	         $( ".pop-mess-succ" ).pdialog('close');
	        }, 2000);
	        
            $('#UsrProfileSettings_height').focus();
            change_step_register('update_profile_step4', 'update_profile_step2');
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
            change_step_register('update_profile_step4', 'update_profile_step2');
            return false;
        }
        var weight = $('#UsrProfileSettings_weight').val().replace(",", ".");
        $('#UsrProfileSettings_weight').val(weight);
        
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
    
	$('body').loading();
	$.ajax({
	  type: "POST",
	  url: $('#frmFillOut').attr('action'),
	  data: data,
	  dataType: 'json',
	  success: function( response ) {
		  $('body').unloading();
		  window.location.href='/register/stepAvatar';
      }
	});
	return false;
}

function scropAvatar(){
	checkCoords();
	$('body').loading();
	$.ajax({
		type: "POST",
		url: $('#frmCropAvatar').attr('action'),
		data: $('#frmCropAvatar').serialize(),
		dataType: 'json',
		success: function( response ) {
			$('body').unloading();
			window.location.href='/register/stepFindFriends';
		},
		error: function () {
        	$("body").unloading();
        }
	});
	return false;
}

function checkCoords()
{
    if (parseInt($('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
};
function change_step_register(previous_class, next_class){
	if(previous_class){
		$('.' + previous_class).fadeOut(500, function(){
			if(next_class){
				$('.' + next_class).fadeIn();
			}			
		});
	}
}

var Register = {
	checkBoxAction: function () {
		$("label[for='s_and_m_yes']").live("click", function(event){
			$('.settings_s_and_m').attr('checked', false);
			$(this).parent().find('.settings_s_and_m').attr('checked', true);
		});
		$("label[for='s_and_m_no']").live("click", function(event){
			$('.settings_s_and_m').attr('checked', false);
			$(this).parent().find('.settings_s_and_m').attr('checked', true);
		});
		$("label[for='tattoo_yes']").live("click", function(event){
			$('.settings_tattoo').attr('checked', false);
			$(this).parent().find('.settings_tattoo').attr('checked', true);
		});
		$("label[for='tattoo_no']").live("click", function(event){
			$('.settings_tattoo').attr('checked', false);
			$(this).parent().find('.settings_tattoo').attr('checked', true);
		});
	}
}