<?php 
$user = Yii::app()->user->data();
if(!Yii::app()->user->isGuest):
?>
<?php 
$friends = Friendship::model()->getAllFriendID(Yii::app()->user->id);
$lstFriends = '';
if(!empty($friends)){
    $lstFriends = implode(',', $friends);
}
?>
<script src="<?php echo Yii::app()->params['NodeJs']['socket_url'];?>/socket.io/socket.io.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {
	if (typeof io != 'undefined' ){
		var socket  = io.connect('<?php echo Yii::app()->params['NodeJs']['socket_url'];?>');
	    
	    var user = {
			user_id: <?php echo Yii::app()->user->id; ?>
	    };
	    var feedLasted = $('.col-feed .news-feed .feed-list .feedLasted');

	    /** init **/
	    socket.emit('get_messages', user);
		socket.emit('get_alerts', user);
		socket.emit('get_friends', user);
		socket.emit('send_photo_request', user);
	    /** end init **/
	    
	    
	    setInterval(function() {
	    	if(feedLasted.length > 0){
	        	var _timeAfter = feedLasted.attr('data-time');
	            var listFriend = {
	        		timestamp: _timeAfter,
	        		user_id: "<?php echo $lstFriends; ?>",
	            };
	    		socket.emit('get_newsfeed', listFriend);
	    	}
			socket.emit('get_messages', user);
			socket.emit('get_alerts', user);
			socket.emit('get_friends', user);
			socket.emit('send_photo_request', user);
	 	}, 5000);


	    setInterval(function() {
			socket.emit('get_online_list', user);
	 	}, 10000);
	
	    
	    socket.on('connect', function(){
	    	if(feedLasted.length > 0){    		
	    		var timeAlertNewsFeed;
	    	    socket.on('get_newsfeed', function(result) {
	    	        if(result.length > 0 && result[0].ids){
	    	        	$('.members.feed-hidden .btn-top.btn-open-feed').addClass('hasNewsFeed');
	    	        	timeAlertNewsFeed = NewsFeed.switchImg();       	
	        		    var _ids = result[0].ids;
	        	    	var _url = feedLasted.attr('data-url');
	        	    	var _timeAfter = feedLasted.attr('data-time');
	        	    	
	        	    	if(_url.length > 0 && _timeAfter.length > 0){
	            		    $.post(_url, { ids:  _ids}, function(data) {
	                		    var newTime = $(data).find('.feedLasted').attr('data-time');      
	                		    var html = $(data).find('.feed-list-update').html();      
	                		    if(newTime.length > 0 && html.length > 0 && newTime > _timeAfter){
	                		    	feedLasted.attr('data-time', newTime);      		    
	                	            $('.feed-list-item').prepend(html);
	                		    }else{
	                		    	feedLasted.attr('data-time', '');
	                		    }
	                        },"html");
	        	    	}
	    	        }else{
	    	        	if(typeof timeAlertNewsFeed != 'undefined'){
	    	        	    clearTimeout(timeAlertNewsFeed);
	    	        	}
	    	        }
	    	    }); 
	    	}
		    
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
			    chat.updateOnlineList(data);
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
			
	
		});
	    
    
	}
});



</script>

<?php 
endif;
?>