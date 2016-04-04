$(document).ready(function(){
	$('li.add a').live('click', function(){
		
		var class_name	=	$(this).attr('class').split(' ')[1];
		var user_id		=	$(this).attr('user_id');
		var alias		=	$(this).attr('alias');
		switch(class_name){
			case 'addfriend':
				add_friend(user_id, alias);
			break;
			case 'waiting_me':
				$( ".cancelrequest_confirm" ).pdialog({
					title: tr('Message'),
					buttons: [
								{
								  text: tr("OK"),
								  click: function() {
									  cancel_request(user_id);
								  }
								},
								{
								  text: tr("Cancel"),
								  click: function() {
									  $( ".cancelrequest_confirm" ).pdialog('close');
								  }
								},
							  ],
				});
			break;	
			case 'waiting_you':
				$( ".accepted_request_confirm" ).pdialog({
					title: tr('Message'),
					buttons: [
								{
								  text: tr("Agree"),
								  click: function() {
									  agree_request(user_id);
								  }
								},
								{
									text: tr("Decline"),
									  click: function() {
										  decline_request(user_id);
									  }
								}
							  ],
				});
			break;	
			case 'unfriend': 
			$( ".unfriend_confirm" ).pdialog({
					title: tr('Message'),
					buttons: [
								{
								  text: tr("OK"),
								  click: function() {
									  unfriend_request(user_id, alias);
								  }
								},
								{
								  text: tr("Cancel"),
								  click: function() {
									  $( ".unfriend_confirm" ).pdialog('close');
								  }
								},
							  ],
			});				
			break;
		}
	});
});
//add friends
function add_friend(friend_id, alias){
	$(window).loading();
    var data    =   {
        "YumFriendship[friend_id]": friend_id        
    };
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/FriendShipAdd?alias=' + alias,
        success: function(data) {
    		if(data == '1'){
	            $('a.addfriend').addClass('waiting');
	            $('a.addfriend').addClass('waiting_me');
	            
	            $('a.waiting_me').removeClass('addfriend');
	            $('a.waiting_me').removeClass('add');
	            
	            $(window).unloading();
		    	Util.popAlertSuccess(tr('Your Friend Request has been sent!'), 300);
		        setTimeout(function () {
		         $( ".pop-mess-succ" ).pdialog('close');
		        }, 2000);	 
		        
    		}
        },
        dataType: 'json'
    });
    return false;    
}
//cancel request
function cancel_request(friend_id){
	$( ".cancelrequest_confirm" ).pdialog('close');
	$(window).loading();	
    var data    =   {
        "YumFriendship[friend_id]": friend_id        
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/CancelRequest',
        success: function(data) {
            $('a.waiting_me').addClass('add');
            $('a.waiting_me').addClass('addfriend');
            
            $('a.addfriend').removeClass('waiting_me');
            $('a.addfriend').removeClass('waiting');
            
            $(window).unloading();
        },
        dataType: 'json'
    });
    return false;     
}
function agree_request(friend_id ){
	$( ".accepted_request_confirm" ).pdialog('close');
	$(window).loading();	
    var data    =   {
        "YumFriendship[friend_id]":  friend_id       
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/AgreeRequest',
        success: function(data) {

            $('a.waiting_you').addClass('friend');
            $('a.waiting_you').addClass('unfriend');
            
            $('a.unfriend').removeClass('waiting_you');
            $('a.unfriend').removeClass('waiting');
        	
            $(window).unloading();	
            
        },
        dataType: 'json'
    });
    return false;     
}
function decline_request(friend_id){
	$( ".accepted_request_confirm" ).pdialog('close');
	$(window).loading();		
    var data    =   {
        "YumFriendship[friend_id]":  friend_id       
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/DeclineRequest',
        success: function(data) {

            $('a.waiting_me').addClass('add');
            $('a.waiting_me').addClass('addfriend');
            
            $('a.addfriend').removeClass('waiting_me');
            $('a.addfriend').removeClass('waiting');
            
            $(window).unloading();	
        },
        dataType: 'json'
    });
    return false;    
}
function unfriend_request(friend_id, alias){
	$( ".unfriend_confirm" ).pdialog('close');
	$(window).loading();
    var data    =   {
        "YumFriendship[friend_id]":  friend_id       
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/unFriendRequest?alias=' + alias,
        success: function(data) {
            $('a.unfriend').addClass('add');
            $('a.unfriend').addClass('addfriend');
            $('a.unfriend').removeClass('friend');
            $('a.unfriend').removeClass('unfriend');            
        },
        dataType: 'json'
    });
    $(window).unloading();
    return false;    
}
