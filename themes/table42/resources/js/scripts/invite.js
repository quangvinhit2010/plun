function getFriends(urlRequest){
	objCommon.loading();
	$('.plun_friends').html("");
	$.get(urlRequest,
		function(source){
			if (source.status){
				$('.plun_friends').show();
				$('.plun_friends').html(source.html);
				
				objCommon.sprScroll(".plun_friends .has_join .list");
				objCommon.sprScroll(".plun_friends .invite_join .list");
			}
			objCommon.unloading();
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
					objCommon.unloading();
					getting_friends	=	false;
				}else{
					getting_friends	=	true;
					objCommon.unloading();
				}
			},
			error: function (xhr, ajaxOptions, thrownError){
				getting_friends	=	true;
				objCommon.unloading();
			}
		});	
	}	
}
function viewContactMore(type){
	if(!getting_contact){
		getting_contact	=	true;
		objCommon.loading();
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
					objCommon.unloading();
					getting_contact	=	false;
				}else{
					getting_contact	=	true;
					objCommon.unloading();
				}
			},
			error: function (xhr, ajaxOptions, thrownError){
				getting_contact	=	true;
				objCommon.unloading();
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
	
	$(document.body).on('click',".invite", function() {
		$(".pop-invite .invite_email").val($(this).parent().attr('invited_email'));
		$(".pop-invite .invite_type").val($(this).parent().attr('type'));
		$.colorbox({inline: true, href: $(".pop-invite")});
		return false;		
	});
	
	
	$(document.body).on('click',".btnInvite", function() {
		objCommon.loading();
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
				objCommon.unloading();
			}
		});
		return false;		
	});
});