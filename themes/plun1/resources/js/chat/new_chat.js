var Chat = {
    connection: null,
    sync: false,
    old_title: null,
    clear_blink: [],
    clear_div_bink: [],
    need_disconnect: false,
    jid_to_id: function (jid) {
        return Strophe.getBareJidFromJid(jid).replace(/@/g, "-").replace(/\./g, "-");
    },
    getAvatar: function (avt) {
		if(avt){
			var after_avt = avt.split("/");
			if(after_avt[0] == 'http:'){
				return '<img border="" alt="" src="'+ avt +'" height="32px" width="32px" >';
			} else {
				return '<img border="" alt="" src="'+ XMPP_DOMAIN + '/'+ avt +'" height="32px" width="32px" >';
				
			}
		} else {
			return '<img border="" alt="" src="'+ XMPP_DOMAIN + '/public/images/no-user.jpg">';
		}
    },
    build_header_link: function(){
    	//var html = "<div class='top-nav'><a href='javascript:void(0);' class='btn-delete'>delete</a> | <a href='javascript:void(0);' class='btn-load-history'>show more</a></div>";
    	var html = "<div class='top-nav'><a href='javascript:void(0);' class='btn-load-history'>"+ tr('show more') +"</a></div>";
    	return html;
    },
    get_chat_history: function (jid){
    	var jid_id = Chat.jid_to_id(jid);
    	var username = Strophe.getNodeFromJid(jid);
        $('#chat-' + jid_id + ' .chat-conversation .item-wrap').html('');
    	$.getJSON('/chat/history/username/' + username,function(result){
	    		var items = [];
	    		if(result.data){
		    	    $.each(result.data, function(i, field){
		    	    	var avatar = null;
		    	    	if(field.direction == 'from') {
		    	    		avatar = Chat.getAvatar(XMPP_AVT);
		    	    	} else {
		    	    		var friend_avatar = $('#'+ Chat.jid_to_id(result.from)).find('.ava').html();
		    	    		if (typeof friend_avatar == "undefined") {
		    	            	var avatar = '<img border="" alt="" src="'+ XMPP_DOMAIN + '/public/images/no-user.jpg">';
		    	    	    } else {
		    	    	    	avatar = friend_avatar;
		    	    	    }
		    	    	}
		    	    	items.push('<li data-mid="'+ field.messageId +'" data-direction="'+ field.direction +'" class="item">' 
		    	    			+ '<a href="#" class="clearfix"><div class="ava">'+ avatar +'</div></a>'
		    	    			+ '<div class="info"> <p>'+ field.body +'</p><span class="time">'+Chat.date_time(field.time)+'</span></div></li>');
		    	    });
	    		}
	    	    $('#chat-' + jid_id + ' .chat-conversation .item-wrap').html(items.join(''));
	    	    $('#chat-' + jid_id + ' .chat-conversation .item-wrap').attr('offset', 2);
	    	    Chat.scroll_chat(jid_id);
    	 });
    },
    sync_chat: function(){
    	if(Chat.sync == true){
    		$('.chat-content').each(function(i){
    			var username = $(this).find('.chat-boxed .chat-boxed-wrap .title').text();
    			var jid = username + '@' + XMPP_SERVER;
    			var jid_id = Chat.jid_to_id(jid);
    			$.getJSON('/chat/history/username/' + username,function(result){
    				var items = [];
    				if(result.data){
    					$.each(result.data, function(i, field){
    						var avatar = null;
    						if(field.direction == 'from') {
    							avatar = Chat.getAvatar(XMPP_AVT);
    						} else {
    							var friend_avatar = $('#'+ Chat.jid_to_id(result.from)).find('.ava').html();
    							if (typeof friend_avatar == "undefined") {
    								var avatar = '<img border="" alt="" src="'+ XMPP_DOMAIN + '/public/images/no-user.jpg">';
    							} else {
    								avatar = friend_avatar;
    							}
    						}
    						items.push('<li data-mid="'+ field.messageId +'" class="item">' 
    								+ '<a href="#" class="clearfix"><div class="ava">'+ avatar +'</div></a>'
    								+ '<div class="info"> <p>'+ field.body +'</p><span class="time">'+Chat.date_time(field.time)+'</span></div></li>');
    					});
    				}
    				$('#chat-' + jid_id + ' .chat-conversation .item-wrap').html(items.join(''));
    				$('#chat-' + jid_id + ' .chat-conversation .item-wrap').attr('offset', 2);
    				Chat.scroll_chat(jid_id);
    			});
    		});
    	} 
    	return false;
    },
    on_roster: function (iq) {
        // set up presence handler and send initial presence
        Chat.connection.addHandler(Chat.on_presence, null, "presence");
        Chat.connection.send($pres());
    },

    pending_subscriber: null,

    on_presence: function (presence) {
    	
        var ptype = $(presence).attr('type');
        var from = $(presence).attr('from');
        var jid_id = Chat.jid_to_id(from);
        
        if (ptype === 'subscribe') {
            // populate pending_subscriber, the approve-jid span, and
            // open the dialog
            //Chat.pending_subscriber = from;
            $('#approve-jid').text(Strophe.getBareJidFromJid(from));
            $('#approve_dialog').dialog('open');
         
        } else if (ptype !== 'error') {
        	var contact = $('.roster-area li#' + jid_id + ' .roster-contact')
                .removeClass("online")
                .removeClass("away")
                .removeClass("offline");
        	//console.log(from + '->' + ptype);
            if (ptype === 'unavailable') {
                //contact.addClass("offline");
            	//@TODO: 
                contact.addClass("online");
            } else if (ptype === 'undefined') {
                	//contact.addClass("offline");
                	contact.addClass("online");
            } else {
                var show = $(presence).find("show").text();
                if (show === "" || show === "chat") {
                    contact.addClass("online");
                } else {
                    contact.addClass("away");
                }
            }

            //var li = contact.parent();
            //li.remove();
           // Chat.insert_contact(li);
        }

        // reset addressing for user since their presence changed
        var jid_id = Chat.jid_to_id(from);
        $('#chat-' + jid_id).data('jid', Strophe.getBareJidFromJid(from));

        return true;
    },

    on_roster_changed: function (iq) {
        $(iq).find('item').each(function () {
            var sub = $(this).attr('subscription');
            var jid = $(this).attr('jid');
            var name = $(this).attr('name') || jid;
            var jid_id = Chat.jid_to_id(jid);
            

            if (sub === 'remove') {
                // contact is being removed
                $('#' + jid_id).remove();
            } else {
                // contact is being added or modified
                var contact_html = "<li id='" + jid_id + "'>" +
                    "<div class='" + 
                    ($('#' + jid_id).attr('class') || "roster-contact offline") +
                    "'>" +
                    "<div class='roster-name'>" +
                    Strophe.getNodeFromJid(jid) +
                    "</div><div class='roster-jid'>" +
                    jid +
                    "</div></div></li>";

                if ($('#' + jid_id).length > 0) {
                    $('#' + jid_id).replaceWith(contact_html);
                } else {
                    //Chat.insert_contact($(contact_html));
                }
            }
        });

        return true;
    },
    on_message: function (message) {
        var full_jid = $(message).attr('from');
        var jid = Strophe.getBareJidFromJid(full_jid);
        var jid_id = Chat.jid_to_id(jid);
        var name = Strophe.getNodeFromJid(jid);
        var avatar = $('#'+ jid_id).find('.ava').html();
 	 
        if (typeof avatar == "undefined") {
        	var avatar = '<img border="" alt="" src="'+ XMPP_DOMAIN + '/public/images/no-user.jpg">';
	    }
        
	   	if ($('.chat-box-area #chat-' + jid_id).length == 0) {
	   		var cookieList = $.fn.cookieList(XMPP_JID);
	   		cookieList.add(jid_id);
	   		var history = Chat.get_chat_history(jid) || '<li><p class="notify_chat">'+ tr('Loading history...') +'</p></li>';
	   		$('.chat-box-area .chat-content').prepend(Chat.build_chat_window(jid_id, name, history));
	   		Chat.clear_blink[jid_id] = Chat.blink_title(tr('&1 messaged you', name), false);
	   	}
	   	
	   	if($('#chat-' + jid_id + ' .chat-conversation').is(':visible') == false){
	   		if(Chat.clear_div_bink[jid_id] == undefined) {
	   			Chat.clear_div_bink[jid_id] = setInterval(function(){
	   				Chat.blink_div_title('#chat-' + jid_id + ' .head')
	   			}, 2000);
	   		}
	   		
	   	}
	   	Chat.set_active_chat_box(jid_id);
	   	var composing = $(message).find('composing');
	   	$('#chat-' + jid_id + ' .chat-event').remove();
	   	if (composing.length > 0) {
	   		$('.chat-box-area #chat-' + jid_id + ' .item-wrap').append(
	   				"<li class='chat-event'><p class='notify_chat'>" + name + " " + tr('is typing...') + "</p></li>"); 
	   		Chat.scroll_chat(jid_id);
	   	}
	   	
	   	var body = $(message).find("html > body");
	   	
	   	if (body.length === 0) {
	   		body = $(message).find('body');
	   		if (body.length > 0) {
	   			body = body.text()
	   		} else {
	   			body = null;
	   		}
	   	} else {
	   		body = body.contents();
	   		
	   		var span = $("<span></span>");
	   		body.each(function () {
	   			if (document.importNode) {
	   				$(document.importNode(this, true)).appendTo(span);
	   			} else {
	   				// IE workaround
	   				span.append(this.xml);
	   			}
	   		});
	   		
	   		body = span;
	   	}
	   	
	   	if (body) {
	   		// remove notifications since user is now active
	   		$('#chat-' + jid_id + ' .chat-event').remove();
   			// add the new message
   			$('#chat-' + jid_id + ' .item-wrap').append(
   					'<li class="item">'
   					+ '<a href="javascript:void(0)" class="clearfix">'
   					+ '<div class="ava">'+ avatar +'</div>'
   					+ '</a>'
   					+ '<div class="info"> '
   					+ '<p>' + body + '</p>'
   					+ '<span class="time">'+Chat.date_time()+'</span>'
   					+ '</div>'
   					+ '</li>');
	   		//$('#chat-' + jid_id + ' .item-wrap .item:last .info p').append(body);
   			Chat.sync_chat();
	   		Chat.scroll_chat(jid_id);
	   	}
	   	
        return true;
    },

    scroll_chat: function (jid_id) {
        var div = $('#chat-' + jid_id + ' .chat-conversation .item-wrap').get(0);
        //console.log(div);
        div.scrollTop = div.scrollHeight;
    },


    presence_value: function (elem) {
        if (elem.hasClass('online')) {
            return 2;
        } else if (elem.hasClass('away')) {
            return 1;
        }

        return 0;
    },

    insert_contact: function (elem) {
        var jid = elem.find('.roster-jid').text();
        var pres = Chat.presence_value(elem.find('.roster-contact'));
        
        var contacts = $('#roster-area li');

        if (contacts.length > 0) {
            var inserted = false;
            contacts.each(function () {
                var cmp_pres = Chat.presence_value(
                    $(this).find('.roster-contact'));
                var cmp_jid = $(this).find('.roster-jid').text();

                if (pres > cmp_pres) {
                    $(this).before(elem);
                    inserted = true;
                    return false;
                } else if (pres === cmp_pres) {
                    if (jid < cmp_jid) {
                        $(this).before(elem);
                        inserted = true;
                        return false;
                    }
                }
            });

            if (!inserted) {
                $('#roster-area ul').append(elem);
            }
        } else {
            $('#roster-area ul').append(elem);
        }
    },
    
    open_chat_by_url: function(jid, name){
    	
        var jid_id = Chat.jid_to_id(jid);
        var history =  Chat.get_chat_history(jid) || '<li><p class="notify_chat">'+ tr('Loading history...') +'</p></li>';
        if ($('#chat-' + jid_id).length === 0) {
        	
        	$('#chat-area').tabs();
        	$('#chat-area').tabs('add', '#chat-' + jid_id, name);
        	var closeBtn = '<a href="javascript:void(0);" title="" class="close-tab">x</a>';
            $('#chat-area .head-tab li').each(function(){
            	if ($(this).find("a").hasClass("close-tab")){
            		// do nothing
            	} else {
            		 $(this).append(closeBtn);
            	}
            });
            $('#chat-' + jid_id).append(
            		Chat.build_header_link() + 
            		"<div id='block-chat'><div class='tabs'><ul class='list-chat'>" + history + "</ul></div></div>" + 
                    "<div class='block-type'><input type='text' placeholder='"+ tr('Write a reply...') +"' class='chat-input' jid='"+ jid +"'></div>");
            $('#chat-' + jid_id).data('jid', jid);
        }
        
        $('#chat-area').tabs('select', '#chat-' + jid_id);
        $('#chat-' + jid_id + ' input').focus();
    	
    },
    keep_chat_window: function(){
    	var cookieList = $.fn.cookieList(XMPP_JID);
		var open_list = cookieList.items();
		$.each(open_list, function( index, value ) {
			var name = $('#' + value +' span.name').text();
			if(name){
				Chat.need_disconnect = true;
				var jid = Chat.build_jid_from_username(name);
				var jid_id = Chat.jid_to_id(jid);
				if ($('.chat-box-area #chat-' + jid_id).length === 0) {
		        	var history = Chat.get_chat_history(jid) || '<li><p class="notify_chat">'+ tr('Loading history...') +'</p></li>';
		        	var cookieList = $.fn.cookieList(XMPP_JID);
		            cookieList.add(jid_id);
		            $('.chat-box-area .chat-content').prepend(Chat.build_chat_window(jid_id, name, history));
		            if($.cookie(jid_id) == 'false'){
		            	$('.chat-box-area #chat-' + jid_id).find('.chat-conversation').hide();
					}
				}
		        //$('#chat-' + jid_id).addClass('active_chat_box');
				Chat.set_active_chat_box(jid_id);
		        $('#chat-' + jid_id + ' input').focus();
			}
	        
		});
    },
    delete_conversation: function(){
    	$('.btn-delete').live('click', function () {
    		 var item = $(this).closest('.chat-conversation');
			 var jid = item.find('.input-wrap .chat-input').attr('jid');
    		 var answer = confirm (tr('Are you sure you want to delete this conversation?'));
    		 if (answer && jid.length){
    			 $(this).loading();
    			 $.ajax({
     				type: "POST",
     				data: {jid: jid},
     				url: '/chat/delete',
	 			        success: function(data){
	 			    		if($(data == '1')){
	 			    			item.find('.item-wrap').html('');
	 			    			$(this).unloading();
	 			    		}
	 			        },
	 			        dataType: 'html'
     			}); 
    			 
    		 }	
    	});
    },
    load_more_conversation: function(){
    	$('.btn-load-history').live('click', function () {
			 var item = $(this).closest('.chat-conversation');
			 var jid = item.find('.input-wrap .chat-input').attr('jid');
			 var offset = item.find('.item-wrap').attr('offset');
			 var username = Strophe.getNodeFromJid(jid);
    		 if (username && offset){
    			 $(this).loading();
    			 $.ajax({
     				type: "GET",
     				url: '/chat/history/username/' + username +'/offset/' + offset,
 			        success: function(result){
 			        	item.find('.item-wrap').attr('offset', result.offset);
 			        	var items = [];
 			        	if(result.data){
 			        		$.each(result.data, function(i, field){
 			        			var avatar = (field.fromJID == Chat.build_jid_from_username(XMPP_JID)) ? Chat.getAvatar(XMPP_AVT) : $('#'+ Chat.jid_to_id(field.fromJID)).find('.ava').html(); 
 				    	    	items.push('<li class="item"><a href="javascript:void(0)" class="clearfix"><div class="ava">'+ avatar +'</div></a><div class="info"> <p>'+ field.body +'</p><span class="time">'+field.sentDate+'</span></div></li>');
 			        		});
 			        		item.find('.item-wrap').prepend(items.join(''));
 			        		var div = item.find('.item-wrap').get(0);
 			        		div.scrollBottom;
 			        	}
 			        	$(this).unloading();
 			        },
 			        dataType: 'json'
     			}); 
    			 
    		 }
    		
    	});
    },
    close_conversation: function(){
    	$(".chat-boxed .btn-close").live('click',function(){
    		var parentItem = $(this).closest('.chat-content');
    		var parentId = Chat.build_jid_from_username($(this).closest('.chat-boxed').find('.title').find('a').html());
    		var jid_id = Chat.jid_to_id(parentId);
    		var cookieList = $.fn.cookieList(XMPP_JID);
    		cookieList.remove(jid_id);
    		$.cookie(jid_id, null);
    		$(this).closest('.chat-boxed').remove();
    		
    		parentItem.each(function(i){
    			var jid = $(this).find('.chat-boxed').attr('id');
    			//console.log(jid);
    			var chat_box_count = $('.chat-content .chat-boxed').length;
    			if(chat_box_count >= 5){
    				var index = 'end';
    			} else {
    				//var index = parseInt(chat_box_count) - 1;
    				var index = chat_box_count;
    			}
    			
    			////Chat.set_active_chat_box(Chat.jid_to_id(jid));
    			if(index > 0){
    				$('#' + jid).removeClass().addClass('chat-boxed pos_chat_boxed_' + index);
    				if(index == 1){
    					$('#' + jid).addClass('active_chat_box');
    				}
    			} else {
    				Chat.need_disconnect = false;
    			}
    			
    			
			});
    	});
    },
    date_time: function(date){
    	if(date){
    		return new Date.parse(date).toString("yyyy-M-dd - HH:mm:ss");
    		/*var datetime = new Date(date);
    		return datetime.toString("yyyy-M-dd - HH:mm:ss");*/
    	} else {
    		return new Date().toString("yyyy-M-dd - HH:mm:ss");
    	}
    },
    is_friend: function(from_jid_id){
    	if(from_jid_id){
    		var friend = $('#' + from_jid_id).attr('jid');
    		if(friend){
    			return true;
    		} else {
    			return false;
    		}
    	} else {
    		return false;
    	}
    	
    	
    },
    set_alert: function(count){
    	if(count){
    		$('.nav-chat').find('.count').show();
    		$('.nav-chat').find('.count').animate({backgroundColor: "#662D91"}, 200).animate({backgroundColor: "#936cb2"}, 200).animate({backgroundColor: "#662D91"}, 200);
    		$('.nav-chat').find('.count').html(count);
    	}
    },
    build_jid_from_username: function(jid){
    	if(jid){
    		return jid + '@' + XMPP_SERVER;
    	} 
    	return null;
    },
    show_hidden_chat_window: function(){
    	$(".chat-boxed .title").live('click', function () {
    		var name = $(this).text();
    		var jid_id = Chat.jid_to_id(Chat.build_jid_from_username(name));
			
    		clearInterval(Chat.clear_div_bink[jid_id]); //clear div blinks
			Chat.clear_div_bink[jid_id] = null;
    		
    		var open= $.cookie(jid_id);
        	if(open == null){
        		$.cookie(jid_id, false);
        	}
        	$(this).closest(".chat-boxed-wrap").find(".chat-conversation").slideToggle(
				function(){
					//console.log($(this).is(':visible'));
	    			$.cookie(jid_id, $(this).is(':visible'));
	    		}
    		
    		);
        	
        	
    	});
    },
    build_chat_window_with_out_open: function(jid_to_id, name, history){
    	//if(jid_to_id && Chat.connection.authenticated == true){
    	if(jid_to_id){
    		var chat_box_count = $('.chat-content .chat-boxed').length;
    		if(chat_box_count >= 5){
    			var index = 'end';
    		} else {
    			var index = parseInt(chat_box_count) + 1;
    		}
    		var html = '<div class="chat-boxed pos_chat_boxed_'+ index +'" id="chat-'+ jid_to_id +'">'
	    		+ '<div class="chat-boxed-wrap">'
		    		+ '<div class="head">'
			    		+ '<div class="title"><a href="/u/'+ name +'" target="_blank">'
			    		+ name +''
			    		+ '</a></div>'
			    		+ '<a href="javascript:void(0);" class="btn-close">X</a>'
		    		+ '</div>'
		    		+ '<div class="chat-conversation" style="display: none;">'
			    		+ Chat.build_header_link() 
			    		+ '<ul class="item-wrap">'
			    			+ history
			    		+ '</ul>'
			    		+ '<div class="sprnav">'
				    		+ '<div class="chat-search">'
					    		+ '<div class="input-wrap">'
					    			+ '<input class="chat-input" jid="'+Chat.build_jid_from_username(name)+'" type="text" placeholder="'+ tr('Write a reply...') +'" />'
					    		+ '</div>'
				    		+ '</div>'
			    		+ '</div>'
		    		+ '</div>'
	    		+ '</div>'
    		+ '</div>';
    		
    		return html;
    				
    	} else{
    		var error = '';
    		return null;
    	}
    },
    build_chat_window: function(jid_to_id, name, history){
    	//if(jid_to_id && Chat.connection.authenticated == true){
    	if(jid_to_id){
    		var chat_box_count = $('.chat-content .chat-boxed').length;
    		if(chat_box_count >= 5){
    			var index = 'end';
    		} else {
    			var index = parseInt(chat_box_count) + 1;
    		}
    		var html = '<div class="chat-boxed pos_chat_boxed_'+ index +'" id="chat-'+ jid_to_id +'">'
	    		+ '<div class="chat-boxed-wrap">'
		    		+ '<div class="head">'
			    		+ '<div class="title"><a href="/u/'+ name +'" target="_blank">'
			    		+ name +''
			    		+ '</a></div>'
			    		+ '<a href="javascript:void(0);" class="btn-close">X</a>'
		    		+ '</div>'
		    		+ '<div class="chat-conversation">'
			    		+ Chat.build_header_link() 
			    		+ '<ul class="item-wrap">'
			    			+ history
			    		+ '</ul>'
			    		+ '<div class="sprnav">'
				    		+ '<div class="chat-search">'
					    		+ '<div class="input-wrap">'
					    			+ '<input class="chat-input" jid="'+Chat.build_jid_from_username(name)+'" type="text" placeholder="'+ tr('Write a reply...') +'" />'
					    		+ '</div>'
				    		+ '</div>'
			    		+ '</div>'
		    		+ '</div>'
	    		+ '</div>'
    		+ '</div>';
    		
    		return html;
    				
    	} else{
    		var error = '';
    		return null;
    	}
    },
    blink_title: function(title, clear){
    	if(clear == true){
    		return $("title").text($("title").text());
    	}
    	var blink = true;
    	var current_title =   $("title").text();
    	Chat.old_title = current_title;
    	Chat.clear_blink = setInterval(function(){
    	    if(blink){
    	        $("title").text(title);
    	        blink = false;
    	    }else{
    	        $("title").text(current_title);
    	        blink = true;
    	    }
    	}, 1000);
    },
    limit_chat_user: function(){
    	
    },
    toggle_online_list: function(){
    	var open= $.cookie('chat_online_list');
    	if(open == null){
    		$.cookie('chat_online_list', false);
    	}
    	
    	if(open =='true') {
    	    $('.chat-list .list').show();
    	    $('.chat-box-area .btn-slide').live('click', function () {
	    		$('.chat-list .list').stop().slideToggle(function(){
	    			$.cookie('chat_online_list', $(this).is(':visible'));
	    			$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-top","none");
	    			$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-bottom","6px solid #333333");
	    		});
    	    });
    	    
    	} else {
    		  //doi icon
    		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-top","none");
    		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-bottom","6px solid #333333");
    		
    	    $('.chat-list .list').hide();
	    	$('.chat-box-area .btn-slide').live('click', function () {	
	    	        $('.chat-list .list').stop().slideToggle(function(){
	    	            $.cookie('chat_online_list', $(this).is(':visible'));
	    	            if( $(this).is(':visible') == true){
	    	            	$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-bottom","none");
		    	    		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-top","6px solid #333333");
	    	            } else {
	    	            	$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-top","none");
	    	        		$(".chat-box-area .chat-list.active .head .btn-slide .arrow").css("border-bottom","6px solid #333333");
	    	            }
	    	        });
	    	});
    	}
    },
    set_active_chat_box: function(jid_id){
    	if(jid_id){
    		var jid_id = '#chat-' + jid_id;
    		var chat_box = $('.chat-content .chat-boxed');
    		if(chat_box.length >= 1){
    			$.each(chat_box, function(){
    				$(this).removeClass('active_chat_box');
    			});
    		} 
    		$(jid_id).addClass('active_chat_box');
    	}
    	
    },
    open_chat_box: function(name){
         var jid = Chat.build_jid_from_username(name);
         var jid_id = Chat.jid_to_id(jid);

         if ($('.chat-box-area #chat-' + jid_id).length === 0) {
         	var history = Chat.get_chat_history(jid) || '<li><p class="notify_chat">'+ tr('Loading history...') +'</p></li>';
         	var cookieList = $.fn.cookieList(XMPP_JID);
             cookieList.add(jid_id);
             $('.chat-box-area .chat-content').prepend(Chat.build_chat_window(jid_id, name, history));
         }
         Chat.set_active_chat_box(jid_id);
         $('#chat-' + jid_id + ' input').focus();
    },
    can_not_chat: function(){
    	Util.popAlertFail(tr('Please add friend for chat feature'), 400);
		setTimeout(function () {
			$( ".pop-mess-fail" ).pdialog('close');
		}, 3000);
    },
    
    disconnect: function(){
    	this.connection.disconnect();
    },
    blink_div_title: function(div){
    	$(div).fadeTo(100, 0.1).fadeTo(200, 1.0);
    },
};
 

$(document).ready(function () {
	
	//open window chat from cookie
	Chat.keep_chat_window();
	Chat.delete_conversation();
	Chat.load_more_conversation();
	Chat.close_conversation();
	Chat.set_alert();
	Chat.show_hidden_chat_window();
	Chat.toggle_online_list();
	$(document).trigger('connect', {
		jid: XMPP_JID,
	    password: XMPP_JKEY
    });
     
    
    
    //$('#chat-area').tabs().find('.ui-tabs-nav').sortable({axis: 'x'});

    $('.chat-contact').live('click', function () {
    	
        var name = $(this).find("span.name").text();
        var jid = Chat.build_jid_from_username(name);
        var jid_id = Chat.jid_to_id(jid);

        if ($('.chat-box-area #chat-' + jid_id).length === 0) {
        	var history = Chat.get_chat_history(jid) || '<li><p class="notify_chat">'+ tr('Loading history...') +'</p></li>';
        	var cookieList = $.fn.cookieList(XMPP_JID);
            cookieList.add(jid_id);
            $('.chat-box-area .chat-content').prepend(Chat.build_chat_window(jid_id, name, history));
        }
        //$('#chat-' + jid_id).addClass('active_chat_box');
        Chat.set_active_chat_box(jid_id);
        $('#chat-' + jid_id + ' input').focus();
    });
    
    $('.chat-input').live('focus', function (ev) {
    	 ev.preventDefault();
    	 var jid = $(this).attr('jid');
    	 var jid_id = Chat.jid_to_id(jid);
     	 Chat.set_active_chat_box(jid_id);
    	 clearInterval(Chat.clear_blink); 
    });
    
    $('.chat-input').live('keypress', function (ev) {
    	Chat.need_disconnect = true;
    	var jid = $(this).attr('jid');
    	var jid_id = Chat.jid_to_id(jid);
    	var avatar = Chat.getAvatar(XMPP_AVT);
    	var name = Strophe.getNodeFromJid(jid);
    	var friend_avatar = $('#'+ jid_id).find('.ava').html();
    	var event = $('.chat-box-area #chat-' + jid_id + ' .item-wrap').find('.chat-event').length;
    	
		if (typeof friend_avatar == "undefined") {
			if(event == 0){
				$('.chat-box-area #chat-' + jid_id + ' .item-wrap').append(
		      			"<li class='chat-event'><p class='notify_chat'>" + tr('&1 is offline now', name) + "</p></li>");
				Chat.scroll_chat(jid_id);
			}
	      	return false;
	    }
    	  
        if (ev.which === 13) {
            ev.preventDefault();
            var body = $(this).val();
            body = $.trim(body);
            if(!body){
            	return false;
            }
            if(Chat.is_friend(jid_id) == false){
            	alert(tr('Please add friend for chat feature'));
            	return false;
            }
            
            
            var message = $msg({to: jid,"type": "chat"})
                .c('body').t(body).up()
                .c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
            
            
            Chat.connection.send(message);
            
            var chat_html = '<li class="item">'
            	+ '<a href="javascript:void(0);" class="clearfix">'
        		+ '<div class="ava">'+ avatar +'</div>'
        	+ '</a>'
        	+ '<div class="info"> '
        		+ '<p>' +body + '</p>'
        		+ '<span class="time">'+Chat.date_time()+'</span>'
        	+ '</div>'
        	+ '</li>';
            
            if(event == 0){
	            $(this).closest('#chat-' + jid_id).find('.chat-conversation .item-wrap').append(chat_html);
            } else {
            	 $(this).closest('#chat-' + jid_id).find('.chat-conversation .item-wrap li:nth-last-child(2)').append(chat_html);
            }
            Chat.scroll_chat(jid_id);
            $(this).val('');
            $(this).parent().data('composing', false);
            
        } else {
            var composing = $(this).parent().data('composing');
            if (!composing) {
                var notify = $msg({to: jid, "type": "chat"})
                    .c('composing', {xmlns: "http://jabber.org/protocol/chatstates"});
                Chat.connection.send(notify);
                $(this).parent().data('composing', true);
            }
        }
    });

    $('#disconnect').click(function () {
        Chat.connection.disconnect();
        Chat.connection = null;
    });

});


$(document).bind('connect', function (ev, data) {
    var conn = new Strophe.Connection(XMPP_BIND);
    data.jid = data.jid + '@' + XMPP_SERVER;
    conn.connect(data.jid, data.password, function (status) {
		if (status === Strophe.Status.CONNECTED) {
			$(document).trigger('connected');
			var sid_and_rid_exists = $.fn.cookieList('sid_' + XMPP_JID);
			sid_and_rid_exists.add({rid: conn.rid, sid: conn.sid});
		} else if (status === Strophe.Status.DISCONNECTED) {
			$(document).trigger('disconnected');
		}
	});
    
    /*conn.rawInput = function (data) { console.log(" IN: " + data); };
    conn.rawOutput = function (data) { console.log("OUT: " + data); };*/
   
    Chat.connection = conn;
});

$(document).bind('connected', function () {
	
    var iq = $iq({type: 'get'}).c('query', {xmlns: 'jabber:iq:roster'});
    
    Chat.connection.addHandler(Chat.on_presence,
    							null, "presence");
    
    Chat.connection.sendIQ(iq, Chat.on_roster);

    Chat.connection.addHandler(Chat.on_roster_changed,
                              "jabber:iq:roster", "iq", "set");

    Chat.connection.addHandler(Chat.on_message,
                              null, "message", "chat");
    

    /*Chat.connection.addHandler(Chat.on_history,
			null, "presence");*/
    							
});



$(document).bind('disconnected', function () {
    Chat.connection = null;
    Chat.pending_subscriber = null;

    $('#roster-area ul').empty();
    $('#chat-area ul').empty();
    $('#chat-area div').remove();
    $('#login_dialog').dialog('open');
});

$(document).bind('contact_added', function (ev, data) {
    var iq = $iq({type: "set"}).c("query", {xmlns: "jabber:iq:roster"})
        .c("item", data);
    Chat.connection.sendIQ(iq);
    
    var subscribe = $pres({to: data.jid, "type": "subscribe"});
    Chat.connection.send(subscribe);
   
});

$(window).on('beforeunload', function(e) {
    //save info somewhere
	if(Chat.need_disconnect == true){
		console.log('Bengin re-load page, disconnect Chat');
		Chat.connection.paused = true;
		Chat.connection.flush();
		Chat.connection.reset();
		Chat.connection.disconnect();
		
		//return 'are you sure you want to leave?';
	}
	return;
});


$(window).focus(function () {
    clearInterval(Chat.clear_blink);
    if(Chat.old_title != null) {
    	$("title").text(Chat.old_title);
    }
});