var Avatar = {
	set_main_action: function(photo_id){
		if(photo_id > 0){
			$(this).loading();
			var action = '/my/photosSetAvatar'
			 $.ajax({
			        type: "POST",
			        data: { "photo_id" : photo_id},
			        url: action,
			        success: function(data){
			    		if($(data == '1')){
			    			window.location = '/';
			    		}
			    		$(this).unloading();
			        },
			        dataType: 'html'
		    });
		}		
	}
};


$(window).resize(function() {
	Util.imgSize($('.pics img'));
});

