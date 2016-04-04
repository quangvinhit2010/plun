var Friend = {
	request: function(friend_id, alias){
		$(document.body).on('click', '.show_num', function(event) {
			objCommon.loading();
			$.ajax({
				type: "POST",
				url: _url,
				data: {'page': _next},
				dataType: 'html',
				success: function( response ) {					
					objCommon.sprScroll(".friend_request_popup .content");
					$(".friend_request_popup").toggle("fast");
					console.log(4);
					objCommon.unloading();
				}
			});
			return false;
		});
	},
	show_more_request: function(){
		objCommon.loading();
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
	        url: '/friend/showmore_request',
	        success: function(data){
	            //$('.show-more').remove();
	            $('.friend_request .content ul').append(data.html);
	            
	            //process with scrollbar	            
	            var showmore_offset = parseInt($('#showmore_offset').val());
	            if(showmore_offset > 0){
	                $('#showmore_offset').val(showmore_offset + parseInt(limit));
	            }            
	            if(!data.show_more_request_addfriends){
	            	$('.showmore-request-addfriends').remove();
	            }           
	            $('.friend_request .content .item_showmore').fadeIn();
	            objCommon.unloading();
	        },
	        dataType: 'json'
	    });     
	},	
	/** add friend **/
	accept_friend: function(inviter_id, alias){
		var total_request	=	parseInt($('.show_num ins').text());
		objCommon.loading();
	    var data = {
	        inviter_id : inviter_id
	    };
	    $.ajax({
	        type: "POST",
	        url: '/friend/addfriend?alias=' + alias,
	        data: data,
	        success: function(data){
	        	var html	=	'<a href="javascript:void(0);" class="active">' + tr('Accepted') + '</a>';
	            $('.addfriendrequest-result-' + inviter_id).html(html);
	            if(total_request > 0){
	            	total_request--;
	            	$('.show_num ins').text(total_request)
	            }
	            objCommon.unloading();
	        },
	        dataType: 'text'
	    });    
	},
	decline_friend: function(inviter_id){
		var total_request	=	parseInt($('.show_num ins').text());
		objCommon.loading();
	    var data = {
	        inviter_id : inviter_id
	    };
	    $.ajax({
	        type: "POST",
	        url: '/friend/declinefriend',
	        data: data,
	        success: function(data){
	        	var html	=	'<a href="javascript:void(0);">' + tr('Declined') + '</a>';
	            $('.addfriendrequest-result-' + inviter_id).html(html);
	            if(total_request > 0){
	            	total_request--;
	            	$('.show_num ins').text(total_request)
	            }
	            objCommon.unloading();
	        },
	        dataType: 'text'
	    });    
	},	
	showmore_friendlist: function(){
		objCommon.loading();
	    var data = {
	        offset: $('#showmore_friendlist_offset').val()
	    };
	    if($('#showmore_friendlist_offset_after').attr('id')){
	        var limit   =   $('#showmore_friendlist_offset_after').val();
	    }else{
	        var limit   =   $('#showmore_friendlist_offset_first').val();
	    }	    
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/showmore_friendlist',
	        success: function(data){
	        	
	            $('.list_user ul').append(data.html);
	      	            
	            var showmore_offset = parseInt($('#showmore_friendlist_offset').val());
	            if(showmore_offset > 0){
	                $('#showmore_friendlist_offset').val(showmore_offset + parseInt(limit));
	            }  
	            
	            if(!data.show_more_myfriendlist){
	            	$('.pagging').remove();
	            }
	            $('.list_user ul li').boxResizeImg({
	                wrapContent: true
	            });
	            
	            $('.item_friendlist_showmore').fadeIn();
	            objCommon.unloading();
	        },
	        dataType: 'json'
	    });    
	},	
	togglePopup: function(){
		$('.friend_request_popup').toggle();
	},
	confirm_unfriend_infriendlist: function(friend_id, alias){
		$( ".popup-alert.remove_status .frame_content" ).html(tr('Do you want to unfriend?'));
		$( ".popup-alert.remove_status" ).pdialog({
            open: function(){
                objCommon.outSiteDialogCommon(this);
            },
				resizable: false,
				position: 'middle',
				draggable: false,
				autoOpen: false,
				center: true,
				modal: true,
				buttons: {				
					"Submit": {
						text: tr("Yes"),
						class: 'active',
						click: function(){
							$( this ).dialog( "close" );
							Friend.unfriend_infriendlist(friend_id, alias);	
						}
					},
					"Cancel": {
						text: tr("No"),
						click: function(){
							$( this ).dialog( "close" );
						}
					}
				}		
			});		
	},
	unfriend_infriendlist: function(friend_id, alias){
		objCommon.loading();
	    var data    =   {
	        "YumFriendship[friend_id]":  friend_id       
	    };    
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/unFriendRequest?alias=' + alias,
	        success: function(data) {
	        	objCommon.unloading();
	        	$('.friendlist-item-' + friend_id).fadeOut(800, function(){
	        		$(this).remove();
	        	});
	        },
	        dataType: 'html'
	    });
	    return false; 
	},
	confirm_unfriend: function(friend_id, alias){
		$( ".popup-alert.remove_status .frame_content" ).html(tr('Do you want to unfriend?'));
        $( ".popup-alert.remove_status" ).pdialog({
                open: function(){
                    objCommon.outSiteDialogCommon(this);
                },
                title: 'Friends',
                buttons: {
					"Cancel": {
						text: tr("No"),
						class: 'active',
						click: function(){
                            $( this ).dialog( "close" );
						}
					},
					"Submit": {
						text: tr("Yes"),
						click: function(){
							$( this ).dialog( "close" );
							Friend.unfriend_request(friend_id, alias);	
						}
					}

				}		
			});
        objCommon.have_title();
	},
	confirm_cancel_request: function(friend_id, alias){
		$( ".popup-alert.remove_status .frame_content" ).html(tr('Do you want to cancel request?'));
		$( ".popup-alert.remove_status" ).pdialog({
                open: function(){
                    objCommon.outSiteDialogCommon(this);
                },
                title: 'Friends',
				buttons: {
					"Submit": {
						text: tr("Yes"),
						click: function(){
							$( this ).dialog( "close" );
							Friend.cancel_request(friend_id, alias);
							
						}
					},						
					"Cancel": {
						text: tr("No"),
						class: 'active',
						click: function(){
							$( this ).dialog( "close" );
						}
						
					}
				}		
		});			
	},	
	confirm_decision_request: function(friend_id, alias){
		$( ".popup-alert.remove_status .frame_content" ).html(tr('you agree to your request of him?'));
		$( ".popup-alert.remove_status" ).pdialog({
                open: function(){
                    objCommon.outSiteDialogCommon(this);
                },
                title: 'Friends',
				buttons: {
					"Submit": {
						text: tr("Agree"),
						class: 'active',
						click: function(){
							$( this ).dialog( "close" );
							Friend.agree_request(friend_id, alias);	
						}
					},
					"Decline": {
						text: tr("Decline"),
						click: function(){
							$( this ).dialog( "close" );
							Friend.decline_request(friend_id, alias);	
						}
					},
					"Cancel": {
						text: tr("Cancel"),
						
						click: function(){
							$( this ).dialog( "close" );
						}
					}


				}		
			});			
	},		
	//add friends
	add_friend: function(friend_id, alias){
		objCommon.loading();
	    var data    =   {
	        "YumFriendship[friend_id]": friend_id        
	    };
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/FriendShipAdd?alias=' + alias,
	        success: function(data) {
	    		if(data == '1'){
		            $('.addfriend').addClass('pending_friend');
		            $('.addfriend').removeClass('addfriend');

			    	Util.popAlertSuccess(tr('Your Friend Request has been sent!'), 300);
			    	
			        setTimeout(function () {
			         $( ".pop-mess-succ" ).pdialog('close');
			        }, 2000);
			        
			        objCommon.unloading();
			        $('.pending_friend').html(Friend._build_cancelrequest_icon(friend_id, alias));
	    		}
	        },
	        dataType: 'json'
	    });
	    return false;    
	},
	_build_addfriend_icon: function(friend_id, alias){
		return '<a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.add_friend(\'' + friend_id + '\', \'' + alias + '\');"></a>';
	},
	_build_unfriend_icon: function(friend_id, alias){
		return '<a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.confirm_unfriend(\'' + friend_id + '\', \'' + alias + '\');"></a>';
	},
	_build_cancelrequest_icon: function(friend_id, alias){
		return '<a class="icon_common" class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.confirm_cancel_request(\'' + friend_id + '\', \'' + alias + '\');"></a>';
	},
	_build_decisionrequest_icon: function(friend_id, alias){
		return '<a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.confirm_decision_request(\'' + friend_id + '\', \'' + alias + '\');"></a>';
	},		
	//cancel request
	cancel_request: function(friend_id, alias){
		objCommon.loading();
	    var data    =   {
	        "YumFriendship[friend_id]": friend_id        
	    };    
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/CancelRequest',
	        success: function(data) {
	        	objCommon.unloading();
	            $('.pending_friend').addClass('addfriend');
	            $('.pending_friend').removeClass('pending_friend');
	            $('.addfriend').html(Friend._build_addfriend_icon(friend_id, alias));
	           		        
	        },
	        dataType: 'html'
	    });
	    return false;     
	},
	agree_request: function(friend_id , alias){
		objCommon.loading();
	    var data    =   {
	        "YumFriendship[friend_id]":  friend_id       
	    };    
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/AgreeRequest',
	        success: function(data) {
	        	objCommon.unloading();
	            $('.pending_friend').addClass('hasfriend');
	            $('.pending_friend').removeClass('pending_friend');
	            $('.hasfriend').html(Friend._build_unfriend_icon(friend_id, alias));
	        },
	        dataType: 'html'
	    });
	    return false;     
	},
	decline_request: function(friend_id){
		objCommon.loading();
	    var data    =   {
	        "YumFriendship[friend_id]":  friend_id       
	    };    
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/DeclineRequest',
	        success: function(data) {
	        	objCommon.unloading();
	            $('.pending_friend').addClass('addfriend');
	            $('.pending_friend').removeClass('pending_friend');
	            $('.addfriend').html(Friend._build_addfriend_icon(friend_id, alias));
	        },
	        dataType: 'html'
	    });
	    return false;    
	},
	unfriend_request: function(friend_id, alias){
		objCommon.loading();
	    var data    =   {
	        "YumFriendship[friend_id]":  friend_id       
	    };    
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/friend/unFriendRequest?alias=' + alias,
	        success: function(data) {
	        	objCommon.unloading();
	            $('.hasfriend').addClass('addfriend');
	            $('.hasfriend').removeClass('hasfriend');
	            $('.addfriend').html(Friend._build_addfriend_icon(friend_id, alias));
	        },
	        dataType: 'html'
	    });
	    return false;    
	}
};


