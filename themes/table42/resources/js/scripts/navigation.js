$(function(){
	postStatus();
});

function postStatus(){
	$("#wall-status-form .btn").unbind('click').bind('click', function(){		
		if($('#WallStatus_status').val() == ''){
        	Util.popAlertSuccess(tr('Please input a status'), 300);
            setTimeout(function () {
             $( ".pop-mess-succ" ).pdialog('close');
            }, 2000);			
			return false;
		}
		objCommon.loading();
		var form = $("#wall-status-form");
		var status = $("#wall-status-form .status").val();
		var href = $(this).attr('data');
		if(status.length > 0){
			$(".pop-status").fadeOut();			
			$.ajax({
				type: "POST",
				'dataType': 'html',
				url: form.attr( 'action' ),
				data: form.serialize(),
				success: function( response ) {
					$("#wall-status-form .status").val('');
					if(href){
						window.location.href = href;
					}else{
						//var data = $(response);
						if($('.feed .content li').length > 0){
							$('.feed .content ul').prepend(response);
							//sprScroll('.news-feed .cont .feed-list-item');
							$(".post_status .status").html('');
						}else{
							$('.feed .content').html('');
							$('.feed .content').prepend('<ul>' + response + '</ul>');
						}
					}
					$(".post_status").fadeOut();
                    $('.sticky_column').fixed_col_scroll.callbackFun();
					objCommon.unloading();
				}
			});
		}
		return false; 
	});
	
	$("#wall-status-form .btn-wrap-cancel .btn").unbind('click').bind('click', function(){
		$(".post_status").fadeOut();
		$("#wall-status-form")[0].reset();
		return false;
	});
}

