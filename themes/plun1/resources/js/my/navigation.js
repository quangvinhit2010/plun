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
		$('body').loading();
		var form = $("#wall-status-form");
		var status = $("#wall-status-form .status").val();
		var href = $(this).attr('data');
		if(status.length > 0){
			$(".pop-status").fadeOut();			
			$.ajax({
				type: "POST",
				url: form.attr( 'action' ),
				data: form.serialize(),
				success: function( response ) {
					$("#wall-status-form .status").val('');
					if(href){
						window.location.href = href;
					}else{
						var data = $(response);
						$('.feed .content ul').prepend(data);
						//sprScroll('.news-feed .cont .feed-list-item');
						$(".pop-status .status").html('');
					}
					$('body').unloading();
				}
			});
		}
		return false; 
	});
	
	$("#wall-status-form .btn-wrap-cancel .btn").unbind('click').bind('click', function(){
		$(".pop-status").fadeOut();
		$("#wall-status-form").reset();
		return false;
	});
}

