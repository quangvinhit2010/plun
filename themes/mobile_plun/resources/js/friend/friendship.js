$(document).ready(function(){
    jQuery("time.timeago").timeago();
    //sprScroll('.news-feed .list-request-addfriend');
});

/** add friend **/
function accept_friend(inviter_id, alias){
	$('body').loading();
    var data = {
        inviter_id : inviter_id
    };
    $.ajax({
        type: "POST",
        url: '/friend/addfriend?alias=' + alias,
        data: data,
        success: function(data){
            $('.accept_bt_' + inviter_id).text(tr('Accepted'));
            $('.accept_bt_' + inviter_id).addClass('btn-blue');
            $('.accept_bt_' + inviter_id).attr('disabled', true);
            $('.decline_bt_' + inviter_id).hide();
            var showmore_offset = parseInt($('#showmore_offset').val());
            if(showmore_offset > 0){
                $('#showmore_offset').val(showmore_offset - 1);
            }
            $('body').unloading();
        },
        dataType: 'text'
    });    
}
function decline_friend(inviter_id){
	$('body').loading();
    var data = {
        inviter_id : inviter_id
    };
    $.ajax({
        type: "POST",
        url: '/friend/declinefriend',
        data: data,
        success: function(data){
            $('.decline_bt_' + inviter_id).text(tr('Declined'));
            $('.decline_bt_' + inviter_id).addClass('btn-blue');
            $('.decline_bt_' + inviter_id).attr('disabled', true);
            $('.accept_bt_' + inviter_id).hide();
            var showmore_offset = parseInt($('#showmore_offset').val());
            if(showmore_offset > 0){
                $('#showmore_offset').val(showmore_offset - 1);
            }     
            $('body').unloading();
        },
        dataType: 'text'
    });    
}

function unfriend(friend_id, alias){
	
	var answer = confirm (tr("Are you sure you want to remove friend?"));
	if(answer){
		var data    =   {
				"YumFriendship[friend_id]":  friend_id       
		};    
		$('.members .list .'+friend_id).remove();
		$('body').loading();
		$.ajax({
			type: "POST",
			data: data,
			url: '/friend/unFriendRequest?alias=' + alias,
			success: function(data) {
				$('body').unloading();
			},
			dataType: 'json'
		});
	}
    return false;    
}
function show_more_request(){
	$('body').loading();
    var data = {
        offset: $('#showmore_offset').val()
    };
    if($('#showmore_offset_after').attr('id')){
        var limit   =   $('#showmore_offset_after').val();
    }else{
        var limit   =   $('#showmore_offset_first').val();
    }
    $.ajax({
        type: "POST",
        data: data,
        //url: '/friend/showmore_request',
        url: window.location.href,
        success: function(response){
            $('.feed-list-item').append(response);
			var showmore_offset = parseInt($('#showmore_offset').val());
            if(showmore_offset > 0){
                $('#showmore_offset').val(showmore_offset + parseInt(limit));
            }  
			
            var is_show_more =   $(response).filter('#is_show_more').val();
            if(is_show_more != true){
            	$('.showmore-request-addfriends').remove();
            }
            //proccess scroll
            $(window).scrollTop($(document).height());
            $('body').unloading();
        },
        dataType: 'html'
    });    
}
function hide_showmore_bt(){
    $('.show-more').remove();
}
function showmore_friendlist(){
	
	$('body').loading();
	var offset = $('#offset').val();
	var limit = $('#limit').val();
    var data = {
        offset: offset
    };
    $.ajax({
        type: "POST",
        data: data,
        url: window.location.href,
        success: function(response){
        	$('.list_friend_request .list_user').append(response);
        	var after_ajax = parseInt(offset) + parseInt(limit);
        	$('#offset').val(after_ajax);
        	var show_more =   $(response).filter('#is_show_more').val();
        	if(show_more == 0){
        		$('.block_loading').remove();
        	}
        	
        	$('body').unloading();
        	
           /* $('.show-more-myfriendlist').remove();
            $('.members ul').append(data.html);
            
            $('.item_friendlist_showmore').fadeIn(800);
            
            var showmore_offset = parseInt($('#showmore_friendlist_offset').val());
            if(showmore_offset > 0){
                $('#showmore_friendlist_offset').val(showmore_offset + parseInt(limit));
            }  
            
            if(!data.show_more_myfriendlist){
            	$('.showmore-friendlist').remove();
            }
            $('body').unloading();*/
        },
        dataType: 'html'
    });    
}