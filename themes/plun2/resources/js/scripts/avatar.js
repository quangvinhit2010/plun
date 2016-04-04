var Avatar = {
	set_main_action: function(photo_id, src){
		if(photo_id > 0){
			objCommon.loading();
			var action = '/my/photosSetAvatar'
			 $.ajax({
			        type: "POST",
			        data: { "photo_id" : photo_id},
			        url: action,
			        success: function(data){
			    		if($(data == '1')){
			    			$('.main_menu_avatar img').attr('src', src);
			    		}
			    		objCommon.unloading();
			        },
			        dataType: 'html'
		    });
		}		
	}
};


$(window).resize(function() {
	Util.imgSize($('.pics img'));
});

