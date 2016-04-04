//add friends
function add_friend(friend_id, alias){
    var data    =   {
        "YumFriendship[friend_id]": friend_id        
    };
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/FriendShipAdd?alias=' + alias,
        success: function(data) {
    		if(data == '1'){
	            $('.add').addClass('waiting');
	            $('.add').html(tr('Waiting'));
	            $('.add').attr('onclick',null);
	            setTimeout(function() {
	    			$('.pop-addfriend').remove();
	    		}, 1500);
    		}
        },
        dataType: 'json'
    });
    return false;    
}
//cancel request
function cancel_request(friend_id){
    var data    =   {
        "YumFriendship[friend_id]": friend_id        
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/CancelRequest',
        success: function(data) {
            $('.waiting').addClass('add');
            $('.add').html(tr('Add'));
            $('.waiting').removeClass('waiting');
            $('.waiting').attr('onclick',null);
            setTimeout(function() {
    			$('.pop-waiting').remove();
    		}, 1500);
        },
        dataType: 'json'
    });
    return false;     
}
function agree_request(friend_id ){
    var data    =   {
        "YumFriendship[friend_id]":  friend_id       
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/AgreeRequest',
        success: function(data) {
            $('.waiting').addClass('friend');
            $('.add').html(tr('Friend'));
            $('.waiting').removeClass('waiting');
            $('.waiting').attr('onclick',null);
            $('.pop-waiting').remove();
        },
        dataType: 'json'
    });
    return false;     
}
function decline_request(friend_id){
    var data    =   {
        "YumFriendship[friend_id]":  friend_id       
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/DeclineRequest',
        success: function(data) {
            $('.waiting').addClass('add');
            $('.add').html(tr('Add'));
            $('.waiting').removeClass('waiting');
            $('.waiting').attr('onclick',null);
            $('.pop-waiting').remove();
        },
        dataType: 'json'
    });
    return false;    
}
function unfriend_request(friend_id, alias){
    var data    =   {
        "YumFriendship[friend_id]":  friend_id       
    };    
    $.ajax({
        type: "POST",
        data: data,
        url: '/friend/unFriendRequest?alias=' + alias,
        success: function(data) {
            $('.friend').addClass('add');
            $('.add').html(tr('Add'));
            $('.friend').removeClass('friend');
            $('.waiting').attr('onclick',null);
            $('.pop-waiting').remove();
        },
        dataType: 'json'
    });
    return false;    
}
