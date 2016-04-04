var Avatar = {
	viewPhotoDetail: function(curr_photo){
		$(this).loading();
		if($('body').hasClass('active_panel_left')){
			$('body').removeClass('active_panel_left');
			$('#block_data_center').css('position','static');			
		}
		/*
		else{
			$('body').addClass('active_panel_left');
			$('#block_data_center').css('position','fixed');		
		}
		*/
		var url_photos = $('.ava').attr('lurlphoto');
		var it_my_photo = $(curr_photo).attr('lis_me');
		var set_main_html = (it_my_photo != undefined) ? '<a href="'+ url_photos +'" class="setmain">'+ tr('Edit avatar') + '</a>' : '';
		var photo_detail_html = '<div class="photo_detail">' +
                    	'<div class="but_close"><a href="javascript:Avatar.close_photo_detail(this);"></a></div>' +
                    	'<div class="pics"><img id="show-photo-detail-img" src="'+$(curr_photo).attr('limg')+'" align="absmiddle"></div>' +
                        '<div class="set_main">' + set_main_html + '</div>' +
                        '<div class="desc_pics"></div>' +
                    '</div>';
		
		if($('.photo_detail').length == 0){
			$('#col2').prepend(photo_detail_html);
		} else {
			$('.pics img').attr('src', $(curr_photo).attr('limg'));
		}
		
		
		if($(curr_photo).attr('lcaption').length > 0){
			$('.desc_pics').html($(curr_photo).attr('lcaption'));
		} else {
			$('.desc_pics').hide();
			$('.desc_pics').html('');
		}
		
		
		$('#show-photo-detail-img').load(function(){
			$('#container').hide();
			$(this).unloading();
			Util.imgSize($(this));
			
		});
		$('.photo_detail').css('height',$(window).height() - 60);

	},
	close_photo_detail: function(photo){
		
		if($('.photo_detail').length > 0){
			$('.photo_detail').remove();
			$('#container').show();
		}
	},
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

