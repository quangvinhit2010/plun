var chat = {
	defaultAvatar: '/public/images/no-user.jpg',
	conn: null,
	toggleChatName: null,
	init: function() {
		chat.attachEvent();
		chat.buildPatterns();
		var data = {
			bindServer: XMPP_BIND,
			jid: XMPP_JID + '@' + XMPP_SERVER,
			pwd: XMPP_JKEY
		}
		if(isIE()) {
			data.bindServer = XMPP_BIND.replace('ws', 'http').replace('5290', '5280');
		}
		chat.toggleChatName = 'toggleChat-' + XMPP_JID;
		chat.connect(data);
	},
	initialChatWindowFromCookie: function() {
		var toggleChat = getCookie(chat.toggleChatName);
		if(toggleChat != '') {
			toggleChat = toggleChat.split(" | ");
			var length = toggleChat.length;
			for(i=0; i<length; i++) {
				var chatWindowProp = toggleChat[i].split(" ");
				var jid = chatWindowProp[0];
				var toggle = chatWindowProp[1];
				
				var chatWindow = chat.openChat(jid);
				if(toggle == 'false')
					chatWindow.find('.chat-conversation').hide();
			}
			chat.setPosition();
		}
	},
	initialListWindowFromCookie: function() {
		var toggleList = getCookie('toggleList');
		if(toggleList=='false') {
			chat.toggleList();
			$('.chat-list .list').hide();
		}
	},
	attachEvent: function() {
		$(document).on('click', '.profile-tool .chat', function(e){
			if($(this).hasClass('is-not-friend')) {
				Util.popAlertFail(tr('Please add friend for chat feature'), 400);
				setTimeout(function () {
					$( ".pop-mess-fail" ).pdialog('close');
				}, 3000);
			} else {
				var jid = $(this).data('jid');
				chat.openOrToggleChat(jid);
			}
			e.preventDefault();
		});
		$(document).on('click', '.btn-slide', function(){
			chat.toggleList();
			$('.chat-list .list').slideToggle();
			
			var bareJid = chat.jidToBareJid(XMPP_JID);
			var message = $msg({to: bareJid, "type": "headline"}).c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'toggleList', to: ''});
			chat.conn.send(message);
		});
		$(document).on('click', '.chat-contact a', function(){
			var jid = $(this).parent().attr('jid');
			chat.openOrToggleChat(jid);
		});
		$(document).on('click', '.btn-close', function(){
			var id = $(this).closest('.chat-boxed').attr('id');
			chat.closeChat(id);
			
			var jid = chat.idToJid(id);
			
			chat.updateToggleChatCookie(jid);
			
			var bareJid = chat.jidToBareJid(XMPP_JID);
			var message = $msg({to: bareJid, "type": "headline"}).c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'gone', to: jid});
			chat.conn.send(message);
		});
		$(document).on('click', '.chat-boxed .title', function(){
			var id = $(this).closest('.chat-boxed').attr('id');
			chat.toggleChat(id);
			
			var jid = chat.idToJid(id);
			var bareJid = chat.jidToBareJid(XMPP_JID);
			var message = $msg({to: bareJid, "type": "headline"}).c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'inactive', to: jid});
			chat.conn.send(message);
		});
		$(document).on('focus', '.chat-boxed .chat-input', function(){
			$('.chat-boxed').removeClass('active_chat_box');
			$(this).closest('.chat-boxed').addClass('active_chat_box');
		});
		$(document).on('keydown', '.chat-boxed .chat-input', function(e){
			var chatWindow = $(this).closest('.chat-boxed');
			var id = chatWindow.attr('id');
			var bareJid = chat.idToBareJid(id);
			if(e.which === 13) {
				e.preventDefault();
				var body = $(this).val();
				
				if(body != '') {
					var message = $msg({to: bareJid, "type": "chat"}).c('body').t(body).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
					chat.conn.send(message);
					
					var jid = chat.idToJid(id);
					var selfBareJid = chat.jidToBareJid(XMPP_JID);
					var message = $msg({to: selfBareJid, "type": "headline"}).c('body').t(body).up().c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'message', to: jid});
					chat.conn.send(message);
					
					if(chatWindow.data('step') >= '3')
						chat.sendMessage(chatWindow, body);
					
					$(this).val('');
					$(this).data('composing', false);
				}
			} else {
				var composing = $(this).data('composing');
				if (!composing) {
					var notifyComposing = $msg({to: bareJid, "type": "chat"}).c('composing', {xmlns: "http://jabber.org/protocol/chatstates"});
					chat.conn.send(notifyComposing);
					$(this).data('composing', true);
				}
			}
		});
		$(document).on('keyup', '.chat-boxed .chat-input', function(e){
			var id = $(this).closest('.chat-boxed').attr('id');
			var bareJid = chat.idToBareJid(id);
			if(e.which != 13 && $(this).val() === '') {
				var notifyUnComposing = $msg({to: bareJid, "type": "chat"}).c('uncomposing', {xmlns: "http://jabber.org/protocol/chatstates"});
				chat.conn.send(notifyUnComposing);
				$(this).data('composing', false);
			}
		});
		window.onbeforeunload = function(e) {
			chat.disconnect();
		};
		window.onunload = function(e) {
			chat.disconnect();
		};
	},
	connect: function(data) {
		chat.conn = new Strophe.Connection(data.bindServer);
		chat.conn.connect(data.jid, data.pwd, function(status){
			if (status === Strophe.Status.CONNECTED) {
				chat.connected();
			} else if (status === Strophe.Status.DISCONNECTED) {
				chat.disconnected();
			}
		});
		chat.conn.addHandler(chat.onMessage, null, "message", "chat");
		chat.conn.addHandler(chat.onPrefer, null, "message", "headline");
		chat.conn.addHandler(chat.onErrorGetConversation, "urn:xmpp:archive", "iq", "error");
	},
	attach: function(data) {
		var bindServer = data.bindServer;
		var fullJid = data.fullJid;
		var rid = Number(data.rid) + 1;
		var sid = data.sid;
		
		chat.conn = new Strophe.Connection(bindServer);
		chat.conn.attach(fullJid, data.sid, rid, function(status){
			if (status === Strophe.Status.ATTACHED) {
				chat.connected();
			} else if (status === Strophe.Status.DISCONNECTED) {
				chat.disconnected();
			}
		});
		
		chat.conn.addHandler(chat.onMessage, null, "message", "chat");
		chat.conn.addHandler(chat.onPrefer, null, "message", "headline");
		chat.conn.addHandler(chat.onErrorGetConversation, "urn:xmpp:archive", "iq", "error");
	},
	connected: function() {
		$('.chat-box-area').show();
		
		chat.conn.send($pres());
		chat.conn.send($iq({ type: 'set', id: 'autoSave' }).c('auto', { save: 'true', xmlns: 'urn:xmpp:archive' }));
		
		chat.initialListWindowFromCookie();
		chat.initialChatWindowFromCookie();
	},
	disconnect: function() {
		if(chat.conn != null) {
			chat.conn.flush();
			chat.conn.options.sync = true
			chat.conn.disconnect();
			
			chat.conn = null;
		}
	},
	openChat: function(jid) {
		var chatWindow = $(chat.buildChatWindow(jid));
		chatWindow.data('avatar', chat.getAvatar(jid));
		$('.chat-content').append(chatWindow);
		chat.getConversation(jid);
		chat.setPosition();
		
		return chatWindow;
	},
	openOrToggleChat: function(jid) {
		var id = chat.jidToId(jid);
		var chatWindow = $('#'+id);
		
		if(chatWindow.length == 0) {
			chatWindow = chat.openChat(jid);
			chat.addToggleChatCookie(jid, 'true');
		} else {
			chatWindow.find('.chat-conversation').slideDown();
			chat.updateToggleChatCookie(jid, 'true');
		}
		
		chatWindow.find('.chat-input').focus();
		
		var bareJid = chat.jidToBareJid(XMPP_JID);
		var message = $msg({to: bareJid, "type": "headline"}).c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'active', to: jid});
		chat.conn.send(message);
	},
	attachWindowChatScroll: function(chatWindow) {
		$('.item-wrap', chatWindow).scroll(function(){
			gettingChat = chatWindow.data('getting-chat');
			if($(this).scrollTop() <= 1 && gettingChat == '0') {
				
				chatWindow.data('getting-chat', '1');
				
				var conversation = chatWindow.data('conversation');
				var page = Number(chatWindow.data('page'));
				if(page >= 1) {
					var start = conversation[page-1];
					var end = conversation[page];

					end = chat.Iso8601ToUnix(end) - 1000;
					end = chat.unixToIso8601(end);
					
					var id = chatWindow.attr('id');
					var jid = chat.idToJid(id);
					
					var height = chatWindow.find('.item-wrap')[0].scrollHeight;
					chatWindow.data('height', height);
					chat.getChat(jid, start, end);
					chatWindow.data('page', page-1);
				}
			}
		});
	},
	closeChat: function(id) {
		$('#'+id).remove();
		chat.setPosition();
	},
	buildChatWindow: function(jid) {
		var textInput = '<input class="chat-input" type="text" placeholder="'+ tr('Write a reply...') +'" />';
		var onlineChatItem = $('#'+jid+'-'+XMPP_SERVER.replace(/\./g, '-'));
		if(onlineChatItem.length == 0) {
			textInput = '<input readonly class="chat-input" type="text" placeholder="'+ tr("&1 is offline now", jid) +'" />';
		}
		var chatWindow =
			'<div data-getting-chat="0" data-step="0" class="chat-boxed" id="'+ chat.jidToId(jid) +'">' +
				'<div class="chat-boxed-wrap">' +
					'<div class="head">' +
						'<div class="title"><a href="/u/'+ jid +'" target="_blank">' + jid + '</a></div>' +
						'<a href="javascript:void(0);" class="btn-close">X</a>' +
					'</div>' +
					'<div class="chat-conversation">' +
						'<ul class="item-wrap"></ul>' +
						'<div class="sprnav">' +
							'<div class="chat-search">' +
								'<div class="input-wrap">' +
									textInput +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>';
		return chatWindow;
	},
	buildChatItem: function(avatar, body, time, itemClass) {
		var chatItem =
			'<li class="item '+itemClass+'">' +
				'<a href="javascript:void(0)" class="clearfix">' +
					'<div class="ava"><img width="32px" border="" height="32px" src="'+avatar+'" alt=""></div>' +
				'</a>' +
				'<div class="info">' +
					'<p>'+Strophe.xmlescape(body)+'</p>' +
					'<span class="time">'+time+'</span>' +
				'</div>' +
			'</li>';
		return chat.replaceEmoticons(chatItem);
	},
	buildLoadingItem: function() {
		return '<li class="loading-history"><p class="notify_chat" style="padding: 0px; padding-left: 10px; display: block; height: 30px; line-height: 30px;">'+ tr('Loading history...') +'</p></li>';
	},
	getConversation: function(jid) {
		
		var chatWindow = $('#'+chat.jidToId(jid));
		chatWindow.find('.item-wrap').prepend(chat.buildLoadingItem());
		
		var bareJid = chat.jidToBareJid(jid);
		var iq = $iq({
			type: 'get',
			id: jid
		}).c('list', {
			xmlns: 'urn:xmpp:archive',
			'with': bareJid
		}).c('set', {
			xmlns: 'http://jabber.org/protocol/rsm'
		}).c('set', {xmlns: 'http://jabber.org/protocol/rsm'}).c('max').t('1000');
		chat.conn.sendIQ(iq, chat.onGetConversation);
		chatWindow.data('step', '1');
	},
	onGetConversation: function(iq) {
		iq = $(iq);
		var jid = iq.attr('id');
		var chatWindow = $('#'+chat.jidToId(jid));
		var conversation = iq.find('chat').map(function() { return $(this).attr("start"); });

		if(conversation.length > 0) {
			chatWindow.data('step', '2');
			var last = Number(iq.find('count').text()) - 1;
			chat.getChat(jid, conversation[last]);
			if(conversation.length > 1) {
				chatWindow.data('conversation', conversation);
				chat.attachWindowChatScroll(chatWindow);
				chatWindow.data('page', last);
			}
		} else {
			chatWindow.find('.item-wrap > .loading-history').remove();
			chatWindow.data('step', '4');
		}
	},
	getChat: function(jid, start, end) {
		var bareJid = chat.jidToBareJid(jid);
		var iq = $iq({
			type: 'get',
			id: jid
		}).c('retrieve', {
			xmlns: 'urn:xmpp:archive',
			'with': bareJid,
			start: start
		}).c('set', {xmlns: 'http://jabber.org/protocol/rsm'}).c('max').t('100000');
		if(typeof end !== 'undefined') {
			iq.up().up().attrs({end: end});
		}
		
		chat.conn.sendIQ(iq, chat.onGetChat);
		var chatWindow = $('#'+chat.jidToId(jid));
		chatWindow.data('step', '3');
		
		var itemWrap = chatWindow.find('.item-wrap');
		if(itemWrap.find('.loading-history').length == 0)
			itemWrap.prepend(chat.buildLoadingItem());
	},
	onGetChat: function(iq) {
		iq = $(iq);
		
		var jid = iq.attr('id');
		var chatWindow = $('#'+chat.jidToId(jid));
		chatWindow.data('step', '4');
		
		var child = iq.children().children();
		var length = child.length - 1;
		
		var itemWrap = chatWindow.find('.item-wrap');
		itemWrap.find('> .loading-history').remove();
		
		var avatar = chatWindow.data('avatar');
		var start = iq.children().attr('start');
		start = chat.Iso8601ToUnix(start);
		
		var chatList = '';
		for(i=0;i<length;i++) {
			var item = child.eq(i);
			var body = item.text();
			var time = start + (Number(item.attr('secs')) * 1000);
			time = chat.unixToClientTime(time);
			if(item.is('to'))
				chatList = chatList + chat.buildChatItem(XMPP_AVT, body, time, 'sent');
			else 
				chatList = chatList + chat.buildChatItem(avatar, body, time, 'receive');
		}
		itemWrap.prepend(chatList);
		
		if(chatWindow.data('getting-chat') == '0') {
			chat.scrollChat(chat.jidToId(jid));
			if($(chatList).length < 8)
				itemWrap.trigger('scroll');
		} else {
			var height = chatWindow.data('height');
			itemWrap.scrollTop((itemWrap[0].scrollHeight - height) + itemWrap.scrollTop() - 30);
		}
		chatWindow.data('getting-chat', '0');
	},
	toggleList: function() {
		var chatList = $('.chat-list');
		chatList.toggleClass('active');
		setCookie('toggleList', chatList.hasClass('active'), 10);
	},
	toggleChat: function(id) {
		var chatConversation = $('#'+id).find('.chat-conversation');
		chatConversation.stop(true, true).slideToggle(function(){
			var jid = chat.idToJid(id);
			if($(this).css('display') == 'none')
				chat.updateToggleChatCookie(jid, 'false');
			else
				chat.updateToggleChatCookie(jid, 'true');
		});
	},
	idToJid: function(id) {
		return id.replace("chat-", "");
	},
	jidToId: function(jid) {
		var id = 'chat-'+jid;
		return id;
	},
	jidToBareJid: function(jid) {
		return jid + '@' + XMPP_SERVER;
	},
	bareJidToJid: function(bareJid) {
		var jid = bareJid.replace('@' + XMPP_SERVER, "");
		return jid;
	},
	idToBareJid: function(id) {
		var jid = chat.idToJid(id);
		var bareJid = chat.jidToBareJid(jid);
		return bareJid;
	},
	setPosition: function() {
		
		var chatWindowWidth = 252;
		var listWindowWidth = $('.chat-list').width() + 3;
		var right = listWindowWidth + 5;
		
		$('.chat-content').children().each(function(){
			$(this).css('right', right + 'px');
			right += (chatWindowWidth+5);
		});
	},
	addToggleChatCookie: function(jid, toggle) {
		var toggleChat = getCookie(chat.toggleChatName);
		if(toggleChat == '')
			toggleChat = jid + " " + toggle;
		else {
			if(toggleChat.search(jid) == -1)
				toggleChat = toggleChat + " | " + jid + " " + toggle;
		}
		setCookie(chat.toggleChatName, toggleChat);
	},
	updateToggleChatCookie: function(jid, toggle) {
		var toggleChat = getCookie(chat.toggleChatName);
		toggleChat = toggleChat.split(" | ");
		var length = toggleChat.length;
		for(i=0; i<length; i++) {
			var chatWindowProp = toggleChat[i].split(" ");
			if(chatWindowProp[0] == jid) {
				if(typeof toggle === 'undefined') {
					toggleChat[i] = null;
				} else {
					chatWindowProp[1] = toggle;
					toggleChat[i] = chatWindowProp.join(" ");
				}
				break;
			}
		}
		
		toggleChat = toggleChat.filter(function(e){return e}).join(" | ");
		setCookie(chat.toggleChatName, toggleChat);
	},
	onErrorGetConversation: function(iq) {
		var jid = $(iq).attr('id');
		var chatWindow = $('#'+chat.jidToId(jid));
		chatWindow.data('step', '4');
		
		chatWindow.find('.item-wrap > .loading-history').remove();
		return true;
	},
	onMessage: function(message) {
		
		message = $(message);
		var fullJid = message.attr('from');
		var bareJid = Strophe.getBareJidFromJid(fullJid);
		var jid = chat.bareJidToJid(bareJid);
		var id = chat.jidToId(jid);
		
		if(message.find('composing').length > 0) {
			var chatWindow = $('#'+id);
			if(chatWindow.length > 0 ) {
				var itemWrap = chatWindow.find('.item-wrap');
				if(itemWrap.find('.composing').length == 0) {
					itemWrap.append('<li class="composing"><p class="notify_chat">'+jid+' '+tr('is typing...')+'</p></li>');
					if((itemWrap.get(0).scrollHeight - 500) <= itemWrap.scrollTop())
						chat.scrollChat(id);
				}
			}
		} else if(message.find('active').length > 0) {
			var chatWindow = $('#'+id);
			
			if(chatWindow.length == 0) {
				chatWindow = chat.openChat(jid);
				chat.addToggleChatCookie(jid, 'true');
			} else {
				chatWindow.find('.chat-conversation').slideDown();
				chat.updateToggleChatCookie(jid, 'true');
			}
			
			if(chatWindow.data('step') == '4') {
				var body = message.find('> body').text();
				chat.receiveMessage(chatWindow, body);
			}
		} else if(message.find('uncomposing').length > 0) {
			var chatWindow = $('#'+id);
			if(chatWindow.length > 0 ) {
				var itemWrap = chatWindow.find('.item-wrap');
				itemWrap.find('.composing').remove();
			}
		}
		return true;
	},
	onPrefer: function(message) {
		message = $(message);
		var fullJid = message.attr('from');
		
		if(Strophe.getResourceFromJid(fullJid) != Strophe.getResourceFromJid(chat.conn.jid)) {
			var prefer = message.find('prefer');
			var type = prefer.attr('type');
			var jid = prefer.attr('to');
			if(type == 'message') {
				var id = chat.jidToId(jid);
				var chatWindow = $('#'+id);
				if(chatWindow.data('step') == '4') {
					var body = message.find('body').text();
					chat.sendMessage(chatWindow, body);
				}
			} else {
				var chatPrefer = chat.prefer;
				chatPrefer[type](jid);
			}
		}
		return true;
	},
	prefer: {
		active: function(jid) {
			var id = chat.jidToId(jid);
			var chatWindow = $('#'+id);
			
			if(chatWindow.length == 0) {
				chatWindow = chat.openChat(jid);
			} else {
				chatWindow.find('.chat-conversation').slideDown();
			}
		},
		inactive: function(jid) {
			var id = chat.jidToId(jid);
			chat.toggleChat(id);
		},
		gone: function(jid) {
			var id = chat.jidToId(jid);
			chat.closeChat(id);
		},
		toggleList: function(jid) {
			$('.chat-list').toggleClass('active');
			$('.chat-list .list').slideToggle();
		}
	},
	getAvatar: function(jid){
		var convertServerString = XMPP_SERVER.replace(/\./g, '-');
		var listElement = $('#'+jid+'-'+convertServerString);
		if(listElement.length > 0)
			var src = listElement.find('.ava img').attr('src');
		else {
			var src = chat.defaultAvatar;
			$.get('/chat/GetUser', {username: jid}, function(response){
				var id = chat.jidToId(jid);
				var chatWindow = $('#'+id);
				chatWindow.data('avatar', response.avatar);
				chatWindow.find('.receive .ava img').attr('src', response.avatar);
			}, 'json');
		}
		return src;
	},
	sendMessage: function(chatWindow, body) {
		var itemWrap = chatWindow.find('.item-wrap');
		itemWrap.find('.composing').remove();
		
		var id = chatWindow.attr('id');
		var jid = chat.idToJid(id);
		
		var avatar = XMPP_AVT;
		var time = chat.makeTime();
		var item = chat.buildChatItem(avatar, body, time, 'send');
		
		itemWrap.append(item);
		chat.scrollChat(id);
	},
	receiveMessage: function(chatWindow, body) {
		var itemWrap = chatWindow.find('.item-wrap');
		itemWrap.find('.composing').remove();
		
		var id = chatWindow.attr('id');
		var jid = chat.idToJid(id);
		
		var avatar = chatWindow.data('avatar');
		var time = chat.makeTime();
		var item = chat.buildChatItem(avatar, body, time, 'receive');
		
		itemWrap.append(item);
		if((itemWrap.get(0).scrollHeight - 500) <= itemWrap.scrollTop())
			chat.scrollChat(id);
	},
	makeTime: function(time) {
		if(typeof time === 'undefined'){
			return new Date().toString("yyyy-M-dd - HH:mm:ss");
    	} else {
    		return new Date(time).toString("yyyy-M-dd - HH:mm:ss");
    	}
	},
	scrollChat: function(id) {
		var itemWrap = $('#'+id).find('.item-wrap').get(0);
		itemWrap.scrollTop = itemWrap.scrollHeight;
	},
	Iso8601ToUnix: function(isoTime) {
		var dateAndTime = isoTime.split('T');
		var timeAndTimeZone = dateAndTime[1].split('+');
		var date = dateAndTime[0].split('-');
		var time = timeAndTimeZone[0].split(':');
		var year = date[0];
		var month = date[1] - 1;
		var day = date[2];
		var hour = time[0];
		var minute = time[1];
		var second = time[2];
		var d = new Date(year, month, day, hour, minute, second);
		return d.getTime();
	},
	unixToIso8601: function(unixTime) {
		var d = new Date(unixTime);
		var year = d.getFullYear();
		var month = chat.zeroFill(d.getMonth() + 1);
		var day = chat.zeroFill(d.getDate());
		var hour = chat.zeroFill(d.getHours());
		var minute = chat.zeroFill(d.getMinutes());
		var second = chat.zeroFill(d.getSeconds());
		var isoTime = year + '-' + month + '-' + day + 'T' + hour + ':' + minute + ':' + second + '+0000';
		return isoTime;
	},
	zeroFill: function(i) {
		return (i < 10 ? '0' : '') + i;
	},
	unixToClientTime: function(unixTime) {
		var offset = new Date().getTimezoneOffset();
		var timeZone = - (offset / 60);
		
		unixTime += (timeZone*60*60*1000);
		var d = new Date(unixTime);
		
		return chat.makeTime(d);
	},
	replaceEmoticons: function(text) {
		var patterns = chat.patterns;
		var emoticons = chat.emoticons;
		return text.replace(new RegExp(patterns.join('|'),'g'), function (match) {
			return typeof emoticons[match] != 'undefined' ? '<span class="emo '+emoticons[match]+'"></span>' : match;
		});
	},
	emoticons: {
		':)'  : 'smile-1',
		":D"  : 'smile-2',
		':x'  : 'smile-love',
		'X('  : 'smile-angry'
	},
	patterns: [],
	buildPatterns: function() {
		var metachars = /[[\]{}()*+?.\\|^$\-,&#\s]/g;
		for (var i in chat.emoticons) {
			if (chat.emoticons.hasOwnProperty(i)) {
				chat.patterns.push('('+i.replace(metachars, "\\$&")+')');
			}
		}
	}
};

/******************* Call it first ***********************/
chat.init();
/******************* Call it first ***********************/

function setCookie(cname, cvalue, exdays) {
	if(typeof exdays === 'undefined') {
		document.cookie = cname + "=" + cvalue + "; path=/";
	} else {
		var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toGMTString();
	    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
	}
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}

function isIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    var trident = ua.indexOf('Trident/');

    if (msie > 0 || trident > 0) {
        return true;
    }
    
    return false;
}