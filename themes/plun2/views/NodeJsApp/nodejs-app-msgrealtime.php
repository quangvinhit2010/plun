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

	    /**MSG Realtime***/
	    socket.emit("auth", '<?php echo Yii::app()->getSession()->getSessionId();?>');
	    socket.on("gettoken", function(username){
	    	var from_user = username;
			$(document.body).on('keydown', '.replyMsg', function(event) {
				var group =  $('.list_message').attr('data-gr');
				var txtMsg = $(this);
			    var msg = $.trim(txtMsg.val());
			    if (event.which == 13 && !event.shiftKey){
					var to_user = $('.message_send .list_message').attr('data-to');
			        socket.emit("send", msg, from_user, group);
			        socket.emit("send", msg, to_user, group);
			        event.preventDefault();
					txtMsg.val('');
		        }
		    });
	    });
	    socket.on("chat", function(who, group, msg){		    
		    if(who == '<?php echo $user->getAliasName();?>'){
			    var user_url = $('.main_menu_avatar').find('a').attr('href');
			    var avatar_url = $('.main_menu_avatar').find('a img').attr('src');
		    }else{
		    	var user_url = $('.list_message').find('li[data-from="'+who+'"] .avatar a').attr('href');
			    var avatar_url = $('.list_message').find('li[data-from="'+who+'"] .avatar img').attr('src');
		    }
		    var time = '1 second ago';
		    var html = '<div class="left avatar">';
				html += '<a width="50" title="" href="'+ user_url +'"><img alt="avatar" src="'+ avatar_url +'" onerror="$(this).attr(\'src\',\'/public/images/no-user.jpg\');"></a>';
				html += '</div>';
				html += '<div class="left info">';
				html += '<p class="nick">';
				html += '<a href="/u/panda"><b class="left">'+ who +'</b></a>';
				html += '<label class="right">'+ time +'</label>';
				html += '</p>';
				html += '<p>'+ msg +'</p>';
				html += '</div>';
            $('.message_send .list_message[data-gr="'+group+'"]').append("<li>" + html + "</li>");
	    });
	    /**MSG Realtime***/

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
	 	}, 2000);


	    setInterval(function() {
// 			socket.emit('get_online_list', user);
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
		    	var html = '<ul class="item-wrap">';
				$.each(data, function( index, value ) {
					if($.isNumeric(value.avatar) == true) {
						var avatar = '/'+value.photo_avatar;
					} else {
						if(value.avatar == null){
							var avatar = '/public/images/no-user.jpg';
						} else {
							var avatar = '/uploads/avatar/p150x0/' + value.avatar;
						}
					}
					 html += '<li class="item chat-contact" id="'+ value.username + '-' + XMPP_SERVER.replace(/\./g, '-') +'" jid="'+ value.username +'">'
		                + '<a href="javascript:void(0);" class="clearfix">'
		                +   '<div class="ava">'
		                +   '<img width="32px" border="" height="32px" src="'+avatar+'" alt="">'
		                +   '</div>'
		                +   '<div class="info">'
		                +        '<span class="name">' + value.username + '</span>'
		                +        '<span class="status">'
		                +            '<i class="status-online"></i>'
		                +        '</span>'
		                +    '</div>'
		                +	'</a>'
		            	+'</li>';							
				});
				html += '</ul>';
				$(".chat-list .list .chat-online-list").html(html);
				$('.chat-boxed').each(function(){
					var jid = chat.idToJid($(this).attr('id'));
					var chatItem = jid + '-' + XMPP_SERVER.replace(/\./g, '-');
					var chatInput = $(this).find('.chat-input');
					if($('#'+chatItem).length == 0) {
						chatInput.prop('readonly', true);
						chatInput.val('');
						chatInput.attr('placeholder', tr("&1 is offline now", jid));
					} else {
						chatInput.prop('readonly', false);
						chatInput.attr('placeholder', tr('Write a reply...'));
					}
				});
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