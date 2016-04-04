<?php 
$user = Yii::app()->user->data();
if(!Yii::app()->user->isGuest):
?>
<script src="<?php echo Yii::app()->params['NodeJs']['socket_url'];?>/socket.io/socket.io.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {
	if (typeof io != 'undefined' ){
		var socket  = io.connect('<?php echo Yii::app()->params['NodeJs']['socket_url'];?>');	   	   
	    var userdata;
	    /** init **/
	    socket.emit("auth", '<?php echo Yii::app()->getSession()->getSessionId();?>');
	    socket.on("on_setuser", function(result){
	    	if(typeof result !== "undefined"){
	    		userdata = result;
	    	}
	    	if(typeof(userdata) !== "undefined"){	    		
	    		socket.emit('em_notifications', {user_id: userdata.uid, update_activity_time: update_activity_time});
// 	    		socket.emit('send_online_list', {user_id: userdata.uid, update_activity_time: update_activity_time});
	    	}
	    });
	    /*
	    setInterval(function() {	    	
	    	if(typeof(userdata) !== "undefined"){
	    		var unix = Math.round(+new Date().getTime()/1000);
	    		var _cal = unix - userdata.lastactivity;
	    		if(_cal > update_activity_time){
	    			socket.emit('update_last_activity', userdata.uid);
	    			console.log(_cal + '_update!');
	    		}
	    		socket.emit('em_notifications', userdata.uid);
	    	}
	 	}, 5000);
	 	
	    setInterval(function() {
			socket.emit('send_online_list', {user_id: <?php echo Yii::app()->user->id; ?>, update_activity_time: update_activity_time});
	 	}, 10000);
	 	*/
	 	
	    
	    socket.on('connect', function(){
	    	socket.on('on_notifications', function(result) {
	    		if(typeof(userdata) !== "undefined"){
		    		var unix = Math.round(+new Date().getTime()/1000);
		    		var _cal = unix - userdata.lastactivity;
		    		if(_cal > update_activity_time){
		    			socket.emit('update_last_activity', userdata.uid);
		    		}
	    		}
	    		
		        if(typeof(result) !== "undefined" && result.total_msg > 0){
			    	$('.nav-msg').find('.count').show();
			    	$('.nav-msg').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
			    	$('.nav-msg').find('.count').html(result.total_msg);
		        }
		        
		    	if(typeof(result) !== "undefined" && result.total_alert > 0){
	    	    	if( $('.col-right .alert_detail').length > 0){
	        	    	var _ids = result.ids;
	        	    	var _url = $('.col-right .alert_detail').attr('data-url');
	        	    	$.post(_url, { ids:  _ids}, function(data) {
	        	            $('.list_date ul').prepend(data);
	                    },"html");
	        	    	
	    	    	}else{
	    		    	$('.nav-alert').find('.count').show();
	    		    	$('.nav-alert').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
	    		    	$('.nav-alert').find('.count').html(result.total_alert);
	    	    	}
		    	}
	
		        if(typeof(result) !== "undefined" && result.total_friend > 0){
			    	$('.nav-friend').find('.count').show();
			    	$('.nav-friend').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
			    	$('.nav-friend').find('.count').html(result.total_friend);
		        }
		        
		    	if(typeof(result) !== "undefined" && result.total_request > 0){
			    	$('.nav-photo').find('.count').show();
			    	$('.nav-photo').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
			    	$('.nav-photo').find('.count').html(result.total_request);
		    	}
		    }); 

	    	socket.on('get_online_list', function(data) {	
		    	console.log(data);	    	
			    chat.updateOnlineListElasstic(data);
			});
	    	/*		    
		    socket.on('get_messages', function(result) {
		        if(result.length > 0 && result[0].total_msg > 0){
			    	$('.nav-msg').find('.count').show();
			    	$('.nav-msg').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
			    	$('.nav-msg').find('.count').html(result[0].total_msg);
		        }else{
		        	$('.nav-msg').find('.count').hide();
		        	$('.nav-msg').find('.count').html(0);
		        }
		    }); 
		
		    socket.on('get_alerts', function(result) {
		    	if(result.length > 0 && result[0].total_alert > 0){
	    	    	if( $('.col-right .alert_detail').length > 0){
	        	    	var _ids = result[0].ids;
	        	    	var _url = $('.col-right .alert_detail').attr('data-url');
	        	    	$.post(_url, { ids:  _ids}, function(data) {
	        	            $('.list_date ul').prepend(data);
	                    },"html");
	        	    	
	    	    	}else{
	    		    	$('.nav-alert').find('.count').show();
	    		    	$('.nav-alert').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
	    		    	$('.nav-alert').find('.count').html(result[0].total_alert);
	    	    	}
		    	}else{
		        	$('.nav-alert').find('.count').hide();
		        	$('.nav-alert').find('.count').html(0);
		        }
		    });
	
		    socket.on('get_friends', function(result) {
		        if(result.length > 0 && result[0].total_friend > 0){
			    	$('.nav-friend').find('.count').show();
			    	$('.nav-friend').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
			    	$('.nav-friend').find('.count').html(result[0].total_friend);
		        }else{
		        	$('.nav-friend').find('.count').hide();
		        	$('.nav-friend').find('.count').html(0);
		        }
		    });  
		
		    socket.on('get_online_list', function(data) {
			    chat.updateOnlineListElasstic(data);
			}); 
		
			socket.on('get_photo_alerts', function(result) {
		    	if(result.length > 0 && result[0].total_request > 0){
			    	$('.nav-photo').find('.count').show();
			    	$('.nav-photo').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
			    	$('.nav-photo').find('.count').html(result[0].total_request);
		    	} else {
		    		$('.nav-photo').find('.count').hide();
			    }
			}); 
			*/
	
		});
	    
    
	}
});



</script>

<?php 
endif;
?>