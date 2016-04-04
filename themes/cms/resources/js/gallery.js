function ShowPicture(responseJSON){
	var is_edit = false;
	$('#photo li').each(function(){
		if ($(this).attr('id') == responseJSON.photo_id){
			$('img', $(this)).attr('src', responseJSON.url);
			is_edit = true;
		}
	});
	
	if (!is_edit)
		$('#photo').append(HtmlDecode(responseJSON.html));
	
}

function previewthumDialog(selection) {
	$('#cimgx').val(selection.x);
	$('#cimgy').val(selection.y);
	$('#cimgw').val(selection.w);
	$('#cimgh').val(selection.h);
}

$(function(){
	$("#tabs").tabs();
	
	$('.uploaded').on("click", ".delete-photo", function(){
		var obj = $(this);
		if (confirm('Confirm delete this photo')){
			$.get(obj.attr('rel'),
				function(data){
					obj.parent().remove();
				}
				
			);
		}
		return false;
	});
	
	$('.uploaded').on("click", ".set_setting", function(){
		var obj = $(this);
		$.get(obj.attr('rel'),
			function(data){
				
				if (data.type != ''){
					$('a', obj.parent()).removeClass('thumb');
					$('a', obj.parent()).removeClass('popup');
					
					$('.item a').removeClass(data.type);
					obj.addClass(data.type);
				}
			},
			"json"
		);
			
		return false;
	});
	
	$('#update_video').click(function(){
		var form = $(this).parent();
		$.ajax({
		      type: "POST",
		      url: form.attr( 'action' ),
		      data: form.serialize(),
		      success: function( response ) {
		    	  alert("Cập nhật thành công");
		      }
		});
	});
});