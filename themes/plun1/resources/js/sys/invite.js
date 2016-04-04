function getFriends(urlRequest){
	$('body').loading();
	$('#fb_friends .friends').html("");
	$('#fb_friends #paging').html("");
	$.get(urlRequest,
		function(source){
			if (source.status){
				if (source.type == "google"){
					
					$('.findfriend-google-result').html(source.html);
					$('.findfriend-google-result').fadeIn();
				}else{
					$('.findfriend-yahoo-result').html(source.html);
					$('.findfriend-yahoo-result').fadeIn();
				}
				createFriendslistScroll(source.type);
				createContactScroll(source.type);
			}
			$('body').unloading();
		},
		"json"
	);
	
}
var getting_contact	=	false;
var getting_friends	=	false;

function createFriendslistScroll(type){
	$(".content_friends").mCustomScrollbar({
		scrollInertia: "0",
		mouseWheelPixels: "auto",
		autoHideScrollbar: false,
		advanced:{
			updateOnContentResize: true,
			contentTouchScroll: true
		},
		callbacks:{
			onScroll: function() {
				if(mcs.topPct >= 90){
					viewFriendsMore(type);
				}
	  		}
		}
	});	
}
function createContactScroll(type){
	$(".content_invite").mCustomScrollbar({
		scrollInertia: "0",
		mouseWheelPixels: "auto",
		autoHideScrollbar: false,
		advanced:{
			updateOnContentResize: true,
			contentTouchScroll: true
		},	
		callbacks:{
			onScroll: function() {
				if(mcs.topPct >= 90){
					viewContactMore(type);
				}
	  		}
		}
	});	
}
function viewFriendsMore(type){
	if(!getting_friends){
		getting_friends	=	true;
		$('.findfriend-gmail-result .found_friend ul').loading();
		$.ajax({
			url : "/invitation/frontend/GetFriendslist" + type,
			type : "POST",
			dataType: 'json',
			data : {
				'offset': $('#friendslist_offset').val()
			},
			success : function(data) {
				if(data.end == '0'){
					$('.content_friends ul').append(data.html);
					$('#friendslist_offset').val(data.next_offset);
					$('.content_friends ul').unloading();
					getting_friends	=	false;
				}else{
					getting_friends	=	true;
					$('.content_friends ul').unloading();
				}
			},
			error: function (xhr, ajaxOptions, thrownError){
				getting_friends	=	true;
				$('.content_friends ul').unloading();
			}
		});	
	}	
}
function viewContactMore(type){
	if(!getting_contact){
		getting_contact	=	true;
		$('.content_invite ul').loading();
		$.ajax({
			url : "/invitation/frontend/GetContact" + type,
			type : "POST",
			dataType: 'json',
			data : {
				'offset': $('#getcontact_offset').val()
			},
			success : function(data) {
				if(data.end == '0'){
					$('.content_invite ul').append(data.html);
					$('#getcontact_offset').val(data.next_offset);
					$('.content_invite ul').unloading();
					getting_contact	=	false;
				}else{
					getting_contact	=	true;
					$('.content_invite ul').unloading();
				}
			},
			error: function (xhr, ajaxOptions, thrownError){
				getting_contact	=	true;
				$('.content_invite ul').unloading();
			}
		});	
	}
}

function openWindow(oauthUrl){
	window.open(oauthUrl,  'newwindow', 'height=600, width=940, top=' +(screen.height-400)/2+ ', left=' +(screen.width-700)/2+ ', toolbar=no, menubar=no, scrollbars=no, resizable=no,location=yes, status=no');
}

$(function(){
	$('.find-friend').click(function(){
		$('#friendslist_offset').val($('#standard_limit').val());
		$('#getcontact_offset').val($('#standard_limit').val());
		getting_contact	=	false;
		getting_friends	=	false;
		
		if($(this).attr('openid') == 'google'){
			$('.findfriend-google-result').fadeOut();
			
			$('.findfriend-yahoo-result *').remove();
		}else{
			$('.findfriend-yahoo-result').fadeOut();
			$('.findfriend-google-result *').remove();
		}
		openWindow($(this).attr('action'));
	});
	
	$("#fb_friends").on("click", "li.page a", function(event){
		getFriends($(this).attr('href'));
		return false;
	});
	
	$(".invite").live('click',function(e) {
		$(".pop-invite .invite_email").val($(this).parent().attr('invited_email'));
		$(".pop-invite .invite_type").val($(this).parent().attr('type'));
		$.colorbox({inline: true, href: $(".pop-invite")});
		return false;
	});
	
	$(".btnInvite").live('click',function() {
		$('body').loading();
		$.ajax({
			url : "/invitation/frontend/invite",
			type : "POST",
			context: this,
			data : {
				'InviteForm[email]': $(this).attr('email')
			},
			success : function(data) {
				$(this).text(tr('Invited'));
				$( ".popup-alert.find-friend" ).pdialog({
					title: tr('Notice'),
				});				
				$('body').unloading();
			}
		});
		return false;
	});
});