var Gab = {
    connection: null,

    jid_to_id: function (jid) {
        return Strophe.getBareJidFromJid(jid).replace(/@/g, "-").replace(/\./g, "-");
    },
    
    build_header_link: function(){
    	var html = "<div class='top-nav'><a href='javascript:void(0);' class='btn-delete'>delete conversation</a> | <a href='javascript:void(0);' class='btn-load-history'>load more chat</a></div>";
    	return html;
    },
    
    get_chat_history: function (jid){
    	var jid_id = Gab.jid_to_id(jid);
    	var username = Strophe.getNodeFromJid(jid);
        $('#chat-' + jid_id + ' .list-chat').html('');
    	$.getJSON('/chat/history/username/' + username,function(result){
	    		var items = [];
	    		if(result.data){
		    	    $.each(result.data, function(i, field){
		    	        var avatar = (field.fromJID ==  XMPP_JID + '@' + XMPP_SERVER) ? '<img border="" alt="" src="' + XMPP_AVT + '">' : $('#'+ Gab.jid_to_id(field.fromJID)).find('.ava').html();
		    	        var real_name = (field.fromJID ==  XMPP_JID + '@' + XMPP_SERVER) ? XMPP_NAME : $('#'+ Gab.jid_to_id(field.fromJID)).find('.roster-name h4').html();
		    	    	items.push('<li><div class="item-chat"><span class="line"></span><span class="time">'+field.sentDate+'</span><a class="ava" title="" href="#">'+ avatar +'</a><h5><span>' + real_name + '</span></h5><p>'+ field.body +'</p></div></li>');
		    	    });
	    		}
	    	    $('#chat-' + jid_id + ' .list-chat').html(items.join(''));
	    	    $('#chat-' + jid_id + ' .list-chat').attr('offset', 2);
    	 });
    },

    on_roster: function (iq) {
       /* $(iq).find('item').each(function () {
            var jid = $(this).attr('jid');
            var name = $(this).attr('name') || jid;
            
            // transform jid into an id
            var jid_id = Gab.jid_to_id(jid);

            var contact = $("<li id='" + jid_id + "'>" +
                            "<div class='roster-contact offline'>" +
                            "<div class='roster-name'>" +
                            Strophe.getNodeFromJid(jid) +
                            "</div><div class='roster-jid'>" +
                            jid +
                            "</div></div></li>");
            console.log(contact);
            //Gab.insert_contact(contact);
        });*/

        // set up presence handler and send initial presence
        Gab.connection.addHandler(Gab.on_presence, null, "presence");
        Gab.connection.send($pres());
    },

    pending_subscriber: null,

    on_presence: function (presence) {
    	
        var ptype = $(presence).attr('type');
        var from = $(presence).attr('from');
        var jid_id = Gab.jid_to_id(from);
        
        if (ptype === 'subscribe') {
            // populate pending_subscriber, the approve-jid span, and
            // open the dialog
            //Gab.pending_subscriber = from;
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
           // Gab.insert_contact(li);
        }

        // reset addressing for user since their presence changed
        var jid_id = Gab.jid_to_id(from);
        $('#chat-' + jid_id).data('jid', Strophe.getBareJidFromJid(from));

        return true;
    },

    on_roster_changed: function (iq) {
        $(iq).find('item').each(function () {
            var sub = $(this).attr('subscription');
            var jid = $(this).attr('jid');
            var name = $(this).attr('name') || jid;
            var jid_id = Gab.jid_to_id(jid);
            

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
                    //Gab.insert_contact($(contact_html));
                }
            }
        });

        return true;
    },
    on_message: function (message) {
        var full_jid = $(message).attr('from');
        var jid = Strophe.getBareJidFromJid(full_jid);
        var jid_id = Gab.jid_to_id(jid);
        var name = Strophe.getNodeFromJid(jid);
        if(Gab.is_friend(jid_id) == true){
        	var real_name = $('#'+ jid_id).find('.roster-name h4').html();
        	var avatar = $('#'+ jid_id).find('.ava').html();
        } else {
        	alert('Vui lòng kết bạn trước khi chat');
        	return false;
        	/*$(this).loading();
        	 $.ajax({
  				type: "GET",
  				url: '/chat/getuser/username/' + name,
	 			success: function(result){
	 				real_name = result.name;
	            	avatar = '<img border="" alt="" src="' + result.avatar + '">';
	        		$( "#chat-area ul li" ).each(function( index ) {
	        			  if($( this ).find('a.ui-tabs-anchor').attr('href') == '#chat-' + jid_id){
	        				  $( this ).find('a.ui-tabs-anchor').html('<span>' + real_name + '</span>');
	        				  
	        			  }
	    			})
	    			$("#chat-area #chat-" + jid_id).find("h5 span").html(real_name);
	        		$("#chat-area #chat-" + jid_id).find(".ava").html(avatar);
	        		$(this).unloading();
	 			},
	 			dataType: 'html'
  			}); */
        	
        	/*
        	alert('Vui lòng kết bạn trước khi chat');
        	return false;
        	var real_name = 'Loading';
        	var avatar = '<img border="" alt="" src="../public/images/no-user.jpg">';
        	$.getJSON('/chat/getuser/username/' + name,function(result){
        		real_name = result.name;
            	avatar = '<img border="" alt="" src="' + result.avatar + '">';
        		$( "#chat-area ul li" ).each(function( index ) {
        			  if($( this ).find('a.ui-tabs-anchor').attr('href') == '#chat-' + jid_id){
        				  $( this ).find('a.ui-tabs-anchor').html('<span>' + real_name + '</span>');
        				  
        			  }
    			})
    			$("#chat-area #chat-" + jid_id).find("h5 span").html(real_name);
        		$("#chat-area #chat-" + jid_id).find(".ava").html(avatar);
        		
        	 });*/
        }
        
        if ($('#chat-' + jid_id).length === 0) {
        	var cookieList = $.fn.cookieList(XMPP_JID);
            cookieList.add(jid_id);
            $('#chat-area').tabs('add', '#chat-' + jid_id, real_name);
            var closeBtn = '<a href="javascript:void(0);" title="" class="close-tab">x</a>';
            $('#chat-area .head-tab li').each(function(){
            	if ($(this).find("a").hasClass("close-tab")){
            		// do nothing
            	} else {
            		 $(this).append(closeBtn);
            	}
            });
            $('#chat-' + jid_id).append(
            	Gab.build_header_link() + 
                "<div id='block-chat'><div class='tabs'><ul class='list-chat'></ul></div></div>" +
                "<div class='block-type'><input type='text' placeholder='Write a reply....' class='chat-input' jid='"+ jid +"'></div>");
        }
        
        $('#chat-' + jid_id).data('jid', full_jid);

        $('#chat-area').tabs('select', '#chat-' + jid_id);
        $('#chat-' + jid_id + ' input').focus();

        var composing = $(message).find('composing');
        if (composing.length > 0) {
            $('#chat-' + jid_id + ' .list-chat').append(
                "<div class='chat-event'>" +
                real_name + 
                " is typing...</div>");

            Gab.scroll_chat(jid_id);
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
            var alert_count = $('ul.head-tab li').size();
            Gab.set_alert(alert_count);
            // add the new message
            $('#chat-' + jid_id + ' .list-chat').append(
            	'<li><div class="item-chat">' +
            		'<span class="line"></span>' +
            		'<span class="time">' + Gab.date_time() + '</span>' +
                	'<a class="ava" title="" href="#">'+ avatar +'</a>' +
                    '<h5><span>' + real_name + '</span></h5>' + 
                    '<p></p>' + 
                '</div></li>');
                
            $('#chat-' + jid_id + ' .list-chat .item-chat:last p')
                .append(body);

            Gab.scroll_chat(jid_id);
        }
        return true;
    },

    scroll_chat: function (jid_id) {
        var div = $('#chat-' + jid_id + ' #block-chat .tabs').get(0);
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
        var pres = Gab.presence_value(elem.find('.roster-contact'));
        
        var contacts = $('#roster-area li');

        if (contacts.length > 0) {
            var inserted = false;
            contacts.each(function () {
                var cmp_pres = Gab.presence_value(
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
    	
        var jid_id = Gab.jid_to_id(jid);
        var history =  Gab.get_chat_history(jid) || 'Loading history';
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
            		Gab.build_header_link() + 
            		"<div id='block-chat'><div class='tabs'><ul class='list-chat'>" + history + "</ul></div></div>" + 
                    "<div class='block-type'><input type='text' placeholder='Write a reply....' class='chat-input' jid='"+ jid +"'></div>");
            $('#chat-' + jid_id).data('jid', jid);
        }
        
        $('#chat-area').tabs('select', '#chat-' + jid_id);
        $('#chat-' + jid_id + ' input').focus();
    	
    },
    keep_chat_window: function(){
    	var cookieList = $.fn.cookieList(XMPP_JID);
		var open_list = cookieList.items();
		
		$.each(open_list, function( index, value ) {
			var jid = $('#' + value +' .roster-contact').find(".roster-jid").text();
	        var name = $('#' + value +' .roster-contact').find(".roster-name").text();
	        var jid_id = Gab.jid_to_id(jid);
	        var history = Gab.get_chat_history(jid) || 'Loading history';
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
	            	Gab.build_header_link() + 
	                "<div id='block-chat'><div class='tabs'><ul class='list-chat'>" + history + "</ul></div></div>" +
	                "<div class='block-type'><input type='text' placeholder='Write a reply....' class='chat-input' jid='"+ jid +"'></div>");
	            $('#chat-' + jid_id).data('jid', jid);
	        }
	        //$('#chat-area').tabs('select', '#chat-' + jid_id);

	        $('#chat-' + jid_id + ' input').focus();
	        Gab.scroll_chat(jid_id);
		});
    },
    delete_conversation: function(){
    	$('.btn-delete').live('click', function () {
    		 var item = $(this).closest('.ui-tabs-panel');
    		 var jid = item.find('.block-type .chat-input').attr('jid');
    		 var answer = confirm ("Are you sure you want to delete this conversation?");
    		 if (answer && jid.length){
    			 $(this).loading();
    			 $.ajax({
     				type: "POST",
     				data: {jid: jid},
     				url: '/chat/delete',
	 			        success: function(data){
	 			    		if($(data == '1')){
	 			    			item.find('.tabs .list-chat').html('');
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
    		 var item = $(this).closest('.ui-tabs-panel');
    		 var jid = item.find('.block-type .chat-input').attr('jid');
    		 var offset = item.find('.tabs .list-chat').attr('offset');
    		 var username = Strophe.getNodeFromJid(jid);
    		 if (username && offset){
    			 $(this).loading();
    			 $.ajax({
     				type: "GET",
     				url: '/chat/history/username/' + username +'/offset/' + offset,
 			        success: function(result){
 			        	item.find('.tabs .list-chat').attr('offset', result.offset);
 			        	var items = [];
 			        	if(result.data){
 			        		$.each(result.data, function(i, field){
 			        			var avatar = (field.fromJID ==  XMPP_JID + '@' + XMPP_SERVER) ? '<img border="" alt="" src="' + XMPP_AVT + '">' : $('#'+ Gab.jid_to_id(field.fromJID)).find('.ava').html();
 			        			var real_name = (field.fromJID ==  XMPP_JID + '@' + XMPP_SERVER) ? XMPP_NAME : $('#'+ Gab.jid_to_id(field.fromJID)).find('.roster-name h4').html();
 			        			items.push('<li><div class="item-chat"><span class="line"></span><span class="time">'+field.sentDate+'</span><a class="ava" title="" href="#">'+ avatar +'</a><h5><span>' + real_name + '</span></h5><p>'+ field.body +'</p></div></li>');
 			        		});
 			        		item.find('.tabs .list-chat').prepend(items.join(''));
 			        	}
 			        	$(this).unloading();
 			        },
 			        dataType: 'json'
     			}); 
    			 
    		 }	
    		
    	});
    },
    close_conversation: function(){
    	$('.close-tab').live('click',function(){
    		var parentId = $(this).prev("a").attr("href");
    		$(this).closest("li").remove();
    		$(parentId).remove();
    		var cookieList = $.fn.cookieList(XMPP_JID);
    		jid_id = parentId.replace('#chat-', '');
    		cookieList.remove(jid_id);
    		$('#chat-area').tabs( "refresh" );
    	});
    },
    date_time: function(date){
    	if(date){
    		return date;
    	} else {
    		return new Date().toString("yyyy-d-M - HH:mm:ss");
    	}
    },
    is_friend: function(from_jid_id){
    	if(from_jid_id){
    		var friend = $('#' + from_jid_id +' .roster-contact').find(".roster-jid").text();
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
};


$(document).ready(function () {
	//open window chat from cookie
	Gab.keep_chat_window();
	Gab.delete_conversation();
	Gab.load_more_conversation();
	Gab.close_conversation();
	Gab.set_alert();
	$(document).trigger('connect', {
		jid: XMPP_JID,
	    password: XMPP_JKEY
    });
     
    
    /*$('#approve_dialog').dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        title: 'Subscription Request',
        buttons: {
            "Deny": function () {
                Gab.connection.send($pres({
                    to: Gab.pending_subscriber,
                    "type": "unsubscribed"}));
                Gab.pending_subscriber = null;

                $(this).dialog('close');
            },

            "Approve": function () {
                Gab.connection.send($pres({
                    to: Gab.pending_subscriber,
                    "type": "subscribed"}));

                Gab.connection.send($pres({
                    to: Gab.pending_subscriber,
                    "type": "subscribe"}));
                
                Gab.pending_subscriber = null;

                $(this).dialog('close');
            }
        }
    });*/
    
    $('#chat-area').tabs().find('.ui-tabs-nav').sortable({axis: 'x'});

    $('.roster-contact').live('click', function () {
        var jid = $(this).find(".roster-jid").text();
        var name = $(this).find(".roster-name").text();
        var jid_id = Gab.jid_to_id(jid);
        var cookieList = $.fn.cookieList(XMPP_JID);
        cookieList.add(jid_id);
        var history = Gab.get_chat_history(jid) || 'Loading history';
        
        if ($('#chat-' + jid_id).length === 0) {
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
            	Gab.build_header_link() + 
                "<div id='block-chat'><div class='tabs'><ul class='list-chat'>" + history + "</ul></div></div>" +
                "<div class='block-type'><input type='text' placeholder='Write a reply....' class='chat-input' jid='"+ jid +"'></div>");
            $('#chat-' + jid_id).data('jid', jid);
        }
        $('#chat-area').tabs('select', '#chat-' + jid_id);

        $('#chat-' + jid_id + ' input').focus();
    });
    

    $('.chat-input').live('keypress', function (ev) {
    	var jid = $(this).attr('jid');
    	var jid_id = Gab.jid_to_id(jid);
    	var avatar = '<img border="" alt="" src="' + XMPP_AVT + '">';
        
        if (ev.which === 13) {
            ev.preventDefault();
            var body = $(this).val();
            body = $.trim(body);
            if(!body){
            	return false;
            }
            if(Gab.is_friend(jid_id) == false){
            	alert('Vui lòng kết bạn để sử dụng tính năng Chat!');
            	return false;
            }
            
            var message = $msg({to: jid,"type": "chat"})
                .c('body').t(body).up()
                .c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
            Gab.connection.send(message);
            
            $(this).closest('#chat-' + jid_id).find('.tabs .list-chat').append(
                	'<li><div class="item-chat me">' +
                		'<span class="line"></span>' +
                		'<span class="time">' + Gab.date_time() + '</span>' +
                    	'<a class="ava" title="" href="#">'+ avatar +'</a>' +
                        '<h5><span>' + XMPP_NAME + '</span></h5>' + 
                        '<p>' +body + '</p>' + 
                    '</div></li>');
            
            Gab.scroll_chat(jid_id);

            $(this).val('');
            $(this).parent().data('composing', false);
        } else {
            var composing = $(this).parent().data('composing');
            if (!composing) {
                var notify = $msg({to: jid, "type": "chat"})
                    .c('composing', {xmlns: "http://jabber.org/protocol/chatstates"});
                Gab.connection.send(notify);

                $(this).parent().data('composing', true);
            }
        }
    });

    $('#disconnect').click(function () {
        Gab.connection.disconnect();
        Gab.connection = null;
    });

    $('#chat_dialog').dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        title: 'Start a Chat',
        buttons: {
            "Start": function () {
                var jid = $('#chat-jid').val().toLowerCase();
                var jid_id = Gab.jid_to_id(jid);

                $('#chat-area').tabs('add', '#chat-' + jid_id, jid);
                $('#chat-' + jid_id).append(
                    "<div class='chat-messages'></div>" +
                    "<input type='text' class='chat-input'>");
            
                $('#chat-' + jid_id).data('jid', jid);
            
                $('#chat-area').tabs('select', '#chat-' + jid_id);
                $('#chat-' + jid_id + ' input').focus();
            
            
                $('#chat-jid').val('');
                
                $(this).dialog('close');
            }
        }
    });

});


$(document).bind('connect', function (ev, data) {
    var conn = new Strophe.Connection(XMPP_BIND);
    data.jid = data.jid + '@' + XMPP_SERVER;
    conn.connect(data.jid, data.password, function (status) {
        if (status === Strophe.Status.CONNECTED) {
            $(document).trigger('connected');
        } else if (status === Strophe.Status.DISCONNECTED) {
            $(document).trigger('disconnected');
        }
    });
    //conn.rawInput = function (data) { console.log(" IN: " + data); };
    //conn.rawOuput = function (data) { console.log("OUT: " + data); };
   
    Gab.connection = conn;
});

$(document).bind('connected', function () {
    var iq = $iq({type: 'get'}).c('query', {xmlns: 'jabber:iq:roster'});
    Gab.connection.addHandler(Gab.on_presence,
    							null, "presence");
    
    Gab.connection.sendIQ(iq, Gab.on_roster);

    Gab.connection.addHandler(Gab.on_roster_changed,
                              "jabber:iq:roster", "iq", "set");

    Gab.connection.addHandler(Gab.on_message,
                              null, "message", "chat");
    
    
    
    							
});

$(document).bind('disconnected', function () {
    Gab.connection = null;
    Gab.pending_subscriber = null;

    $('#roster-area ul').empty();
    $('#chat-area ul').empty();
    $('#chat-area div').remove();
    $('#login_dialog').dialog('open');
});

$(document).bind('contact_added', function (ev, data) {
    var iq = $iq({type: "set"}).c("query", {xmlns: "jabber:iq:roster"})
        .c("item", data);
    Gab.connection.sendIQ(iq);
    
    var subscribe = $pres({to: data.jid, "type": "subscribe"});
    Gab.connection.send(subscribe);
   
});
