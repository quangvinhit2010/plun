function isIE(){var ua = window.navigator.userAgent;var msie = ua.indexOf('MSIE ');var trident = ua.indexOf('Trident/');if (msie > 0 || trident > 0) {return true;}return false;}
$.fn.selectRange = function(start, end) { if(!end) end = start; return this.each(function() { if (this.setSelectionRange) { this.focus(); this.setSelectionRange(start, end); } else if (this.createTextRange) { var range = this.createTextRange(); range.collapse(true); range.moveEnd('character', end); range.moveStart('character', start); range.select(); } }); };

var ua = navigator.userAgent.toLowerCase();
var isAndroid = ua.indexOf("android") > -1;

chat = {
	jid: '',
	unique: $.now(),
	init: function() {
		chat.connect();
		chat.buildPatterns();
		chat.mapEncryptUrl = $('.nav-msg a').data('map');
		$('.nav-msg a').click(function(){
			$('#new-messages-wrap').css({
				minHeight: $('#block_data_center').css('min-height')
			});
			var parent = $(this).closest('.nav-msg');
			parent.removeClass('flash');
			if(parent.hasClass('has-new')) {
				$('#new-messages-wrap').toggle();
				$('#block_data_center').toggle();
				return false;
			}
		});
		/*
		$(document).on('click', '#new-messages a', function(){
			$(this).closest('.item').remove();
			var count = $('.nav-msg .count');
			var countNo = Number(count.text()) - 1;
			count.text(countNo).show();
			
			if($('#new-messages > li').length == 0) {
				$('.nav-msg').removeClass('has-new');
				$('#new-messages-wrap').hide();
				$('#block_data_center').show();
			}
		});
		*/
		
		window.onbeforeunload = function(e) {
			chat.disconnect();
		};
		window.onunload = function(e) {
			chat.disconnect();
		};
	},
	initFriendsPage: function() {
		$('#submit-search').click(function(){
			$('#listFriends .item').each(function(){
				var self = $(this);
				if(self.find('.chat-nickname').text().indexOf($('#search-friend-field').val()) != -1)
					self.show();
				else
					self.hide();
			});
		});
	},
	initListMessagePage: function() {
		$(document).on('click', '.block_loading a', function(){chat.loadMore($(this))});
		chat.markUnread($('#listConversation .item'));
		
		$('.nav-msg a').off('click');
	},
	initChatPage: function() {
		$('#load-old-message').click(function(){
			chat.getChatHistory();
		});
		$('.send-wrap input').click(function(){
			var textField = $('.input-wrap input');
			var body = textField.val();
			if(body != '') {
				var messageId = chat.makeMessageId();
				var message = $msg({to: chat.bareJid, "type": "chat"}).c('body', {'data-id': messageId}).t(body).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
				chat.conn.send(message);

				var selfBareJid = chat.jidToBareJid(XMPP_JID);
				var message = $msg({to: selfBareJid, "type": "headline"}).c('body', {'data-id': messageId}).t(body).up().c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'message', to: chat.jid, unique: chat.unique});
				chat.conn.send(message);

				
				if($('#chat-list-item .time-separator').last().data('date') != chat.serverDate) {
					$('#chat-list-item').append('<li data-date="'+chat.serverDate+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+chat.serverDate+'</span></li>');
				}
				$('#chat-list-item').append(chat.buildSendMessage(body));
				
				chat.scroll();
				
				textField.val('');
			}
		});
		$('.input-wrap input').focus(function(){
			/*
			var notifyComposing = $msg({to: chat.bareJid, "type": "chat"}).c('composing', {xmlns: "http://jabber.org/protocol/chatstates"});
			chat.conn.send(notifyComposing);
			
			window.location.hash = 'input-text';
			*/
			if(isAndroid) {
				window.location.hash = 'input-text';
			}
		}).blur(function(){
			/*
			var notifyUnComposing = $msg({to: chat.bareJid, "type": "chat"}).c('uncomposing', {xmlns: "http://jabber.org/protocol/chatstates"});
			chat.conn.send(notifyUnComposing);
			
			window.location.hash = '#';
			
			*/
			if(isAndroid) {
				window.location.hash = '#';
				setTimeout(function() {
					$(window).scrollTop($(document).height());
				}, 500);
			}
		});
		
		$('#toggle-emo').click(function(){
			$('.emoticons-wrap.items').toggle();
			chat.scroll();
		});
		$('.emoticons-item .emo').click(function(){
			var textArea = $('.input-wrap input');
			var itemClass = $(this).attr('class').replace('emo ', '');
			var text = '';
			$.each(chat.emoticons, function(index, val){
				if(val == itemClass) {
					text = chat.decodeHtml(index);
					return false;
				}
			});
			var startSelect = textArea[0].selectionStart;
			var firstText = textArea.val().slice(0, startSelect);
			var endText = textArea.val().slice(startSelect, textArea.val().length);
			textArea.val(firstText+text+endText);
			textArea.selectRange(firstText.length+text.length);
			$(this).closest('.emoticons-wrap.items').hide();
		});
		
		window.addEventListener('scroll',function(event) {
			if($(window).scrollTop() + $(window).height() + 71 >= $(document).height() ) {
				$('#chat-wrap').data('bottom', '1');
			} else {
				$('#chat-wrap').data('bottom', '0');
			}
		}, false);
		
		chat.clientTime = $.now();
		chat.serverTime = $('#chat-wrap').data('time');
		var d = new Date(chat.serverTime * 1000);
		chat.serverDate = chat.zeroFill(d.getDate())+'-'+chat.zeroFill(d.getMonth()+1)+'-'+d.getFullYear();
		
		chat.conn.addHandler(chat.onMessageChatPage, null, "message", "chat");
		chat.conn.addHandler(chat.onPrefer, null, "message", "headline");
	},
	connect: function() {
		if(isIE()) XMPP_BIND = XMPP_BIND.replace('ws', 'http').replace('5290', '5280');
		chat.conn = new Strophe.Connection(XMPP_BIND);
		chat.conn.connect(chat.jidToBareJid(XMPP_JID), XMPP_JKEY, function(status){
			if (status === Strophe.Status.CONNECTED) {
				chat.connected();
			} else if (status === Strophe.Status.DISCONNECTED) {
				chat.disconnected();
			}
		});
		
		chat.conn.addHandler(chat.onMessage, null, "message", "chat");
		
		chat.conn.addHandler(chat.onErrorGetConversation, "urn:xmpp:archive", "iq", "error");
	},
	disconnect: function() {
		if(chat.conn != null) {
			chat.conn.flush();
			chat.conn.options.sync = true
			chat.conn.disconnect();
			
			chat.conn = null;
		}
	},
	connected: function() {
		chat.conn.send($pres());
		chat.conn.send($iq({ type: 'set', id: 'autoSave' }).c('auto', { save: 'true', xmlns: 'urn:xmpp:archive' }));
		
		if($('#chat-wrap').length > 0) {
			chat.jid = $('#chat-wrap').data('jid');
			chat.avatar = $('.composing img').attr('src');
			chat.bareJid = chat.jidToBareJid(chat.jid);
			chat.getConversation();
		}
	},
	getChatHistory: function() {
		$('.load-old-message-wrap img').show();
		$('#load-old-message').hide();
		
		var start = chat.conversation[chat.page-1];
		var end = chat.conversation[chat.page];

		end = chat.Iso8601ToUnix(end) - 1000;
		end = chat.unixToIso8601(end);

		chat.getChat(start, end);
		chat.page -= 1;
	},
	getConversation: function() {
		var iq = $iq({
			type: 'get',
			id: chat.jid
		}).c('list', {
			xmlns: 'urn:xmpp:archive',
			'with': chat.bareJid
		}).c('set', {
			xmlns: 'http://jabber.org/protocol/rsm'
		}).c('set', {xmlns: 'http://jabber.org/protocol/rsm'}).c('max').t('1000');
		chat.conn.sendIQ(iq, chat.onGetConversation);
	},
	onGetConversation: function(iq) {
		iq = $(iq);
		chat.conversation = iq.find('chat').map(function() { return $(this).attr("start"); });

		chat.page = Number(iq.find('count').text()) - 1;
		chat.getChat(chat.conversation[chat.page]);
	},
	getChat: function(start, end) {
		var iq = $iq({
			type: 'get',
			id: chat.jid
		}).c('retrieve', {
			xmlns: 'urn:xmpp:archive',
			'with': chat.bareJid,
			start: start
		}).c('set', {xmlns: 'http://jabber.org/protocol/rsm'}).c('max').t('100000');
		if(typeof end !== 'undefined') {
			iq.up().up().attrs({end: end});
		}
		chat.conn.sendIQ(iq, chat.onGetChat);
	},
	onGetChat: function(iq) {
		iq = $(iq);
		var child = iq.children().children();
		var length = child.length - 1;
		
		var chatList = '';
		
		for(i=0;i<length;i++) {
			var item = child.eq(i);
			var body = item.find('body').text();
			
			if(item.is('to')) {
				var chatItem = chat.buildSendMessage(body);
			} else {
				var chatItem = chat.buildReceiveMessage(body);
			}
			chatList += chatItem;
		}
		$('#chat-list-item').prepend(chatList);
		
		if(Number(iq.find('count').text()) > 0) {
			var start = iq.children().attr('start');
			var elapsedTime = chat.getElapsedTime(start);
			$('#chat-list-item').prepend('<li data-date="'+elapsedTime+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+elapsedTime+'</span></li>');
		}
		
		$('.load-old-message-wrap img').hide();
		if(chat.page > 0)
			$('#load-old-message').addClass('show');
		else
			$('#load-old-message').removeClass('show');
		
		chat.scroll();
	},
	onMessage: function(message) {
		message = $(message);
		var fullJid = message.attr('from');
		var bareJid = Strophe.getBareJidFromJid(fullJid);
		var jid = chat.bareJidToJid(bareJid);
		var body = message.find('> body').text();
		
		if(message.find('active').length > 0 && jid != chat.jid) {
			var mssLink = $('.nav-msg a');
			mssLink.closest('.nav-msg').addClass('has-new flash');
			
			var msgOff = (mssLink.data('offline-message') != "") ? mssLink.data('offline-message').split(',') : [];
			if(msgOff.indexOf(jid) == -1) {
				msgOff.push(jid);
				mssLink.data('offline-message', msgOff.join(','));
				
				var count = $('.nav-msg .count');
				var countNo = Number(count.text()) + 1;
				count.text(countNo).show();
				
				$.post(mssLink.data('update-url'), {jid: jid}, function(response){
					chat.mapEncryptUrl[jid] = response;
					chat.buildNewMessase(jid, body);
				});
			} else {
				chat.buildNewMessase(jid, body);
			}
		}
		
		return true;
	},
	buildNewMessase: function(jid, body) {
		var newItem = $('#new-message-'+jid);
		if(newItem.length > 0) {
			newItem.find('.preview-message').html('<label class="replied"></label>'+body);
		} else {
			var mssLink = $('.nav-msg a');
			var chatUrl = mssLink.data('chat-url')+'?u='+chat.mapEncryptUrl[jid]+'&t='+$.now();
			var avatar = mssLink.data('avatar-url')+'?uid='+jid;
			var item = '<li id="new-message-'+jid+'" class="item unread">'+
							'<a href="'+chatUrl+'">'+
								'<span class="left avatar"><img width="40" align="absmiddle" src="'+avatar+'"></span>'+
								'<span class="message_item">'+
									'<p class="chat-nickname">'+jid+'</p>'+
									'<p class="preview-message"><label class="replied"></label>'+body+'</p>'+
								'</span>'+
							'</a>'+
						'</li>';
			$('#new-messages').prepend(item);
		}
	},
	onMessageChatPage: function(message) {
		message = $(message);
		var fullJid = message.attr('from');
		var bareJid = Strophe.getBareJidFromJid(fullJid);
		var jid = chat.bareJidToJid(bareJid);
		
		if(jid != chat.jid)
			return true;
		
		if(message.find('delay').length > 0)
			return true;
		
		if(message.find('composing').length > 0) {
			$('.composing').show();
			chat.scroll();
		} else if(message.find('active').length > 0) {
			var body = message.find('> body').text();
			
			if($('#chat-list-item .time-separator').last().data('date') != chat.serverDate) {
				$('#chat-list-item').append('<li data-date="'+chat.serverDate+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+chat.serverDate+'</span></li>');
			}

			$('.composing').hide();
			$('#chat-list-item').append(chat.buildReceiveMessage(body));
			chat.scroll();
		} else if(message.find('uncomposing').length > 0) {
			$('.composing').hide();
		}
		return true;
	},
	onPrefer: function(message) {
		message = $(message);
		var fullJid = message.attr('from');
		if(Strophe.getResourceFromJid(fullJid) == Strophe.getResourceFromJid(chat.conn.jid))
			return true;
			
		var prefer = message.find('prefer');
		var type = prefer.attr('type');
		var jid = prefer.attr('to');
		
		if(jid == chat.jid && type == 'message') {
			var body = message.find('body').text();
			if($('#chat-list-item .time-separator').last().data('date') != chat.serverDate) {
				$('#chat-list-item').append('<li data-date="'+chat.serverDate+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+chat.serverDate+'</span></li>');
			}
			$('#chat-list-item').append(chat.buildSendMessage(body));
			
			chat.scroll();
		}
		return true;
	},
	onErrorGetConversation: function(iq) {
		$('.load-old-message-wrap img').hide();
		return true;
	},
	buildSendMessage: function(body) {
		body = Strophe.xmlescape(body);
		body = chat.replaceEmoticons(body);
		var chatItem = '<li class="sender"><div class="bubble-wrap"><div class="bubble"><span class="arrow-wrap"><span class="arrow"></span></span>'+body+'</div></div></li>';
		return chatItem;
	},
	buildReceiveMessage: function(body) {
		body = Strophe.xmlescape(body);
		body = chat.replaceEmoticons(body);
		var chatItem = '<li class="reveiver"><img width="25" height="25" class="avatar" src="'+chat.avatar+'" /><div class="bubble-wrap"><div class="bubble"><span class="arrow-wrap"><span class="arrow"></span></span>'+body+'</div></div></li>';
		return chatItem;
	},
	loadMore: function(pageLink) {
		pageLink.hide().next().show();
		var url = pageLink.data('url');
		var next = pageLink.data('next');
		$.ajax({
			url: url,
			data: {page: next},
			success: function(response) {
				response = $(response);
				var item = response.find('#listConversation > li.item');
				chat.markUnread(item);
				$('#listConversation').append(item);
				var pagging = response.find('.block_loading');
				if(pagging.length > 0) {
					$('.block_loading').replaceWith(pagging);
					pageLink.show().next().hide();
				} else
					$('.block_loading').remove();
				
			}
		});
	},
	markUnread: function(items) {
		var listOffline = $('.nav-msg > a').data('offline-message').split(',');
		items.each(function(){
			var username = $(this).find('.chat-nickname').text();
			if(listOffline.indexOf(username) != -1)
				$(this).addClass('unread');
		});
	},
	makeMessageId: function() {
		return (chat.serverTime*1000) + ($.now() - chat.clientTime);
	},
	idToJid: function(id) {
		return id.replace(chatBoxIdPrefix, "");
	},
	jidToId: function(jid) {
		var id = chatBoxIdPrefix+jid;
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
	replaceEmoticons: function(text) {
		var patterns = chat.patterns;
		var emoticons = chat.emoticons;
		return text.replace(new RegExp(patterns.join('|'),'g'), function (match) {
			return typeof emoticons[match] != 'undefined' ? '<span class="emo '+emoticons[match]+'"></span>' : match;
		});
	},
	emoticons: {
		':)'  : 'emo-1',
		':D'  : 'emo-2',
		':xO' : 'emo-3',
		'x-D' : 'emo-4',
		'&gt;&lt;' : 'emo-4',
		'&gt;.&lt;' : 'emo-4',
		';)'  : 'emo-5',
		':-J' : 'emo-6',
		'(:|' : 'emo-7',
		'(:)' : 'emo-8',
		'X('  : 'emo-9',
		'x:)' : 'emo-10',
		'():)': 'emo-11',
		'I-)' : 'emo-12',
		'B-)' : 'emo-13',
		':-O' : 'emo-14',
		':((' : 'emo-15',
		':x'  : 'emo-16',
		':('  : 'emo-17',
		':-*' : 'emo-18',
		'0y0' : 'emo-19',
		'0i0' : 'emo-20',
		'&lt;3'  : 'emo-21'
	},
	patterns: [],
	buildPatterns: function() {
		var metachars = /[[\]{}()*+?.\\|^$\-,&#\s]/g;
		for (var i in chat.emoticons) {
			if (chat.emoticons.hasOwnProperty(i)) {
				chat.patterns.push('('+i.replace(metachars, "\\$&")+')');
			}
		}
	},
	decodeHtml: function(html) {
	    var txt = document.createElement("textarea");
	    txt.innerHTML = html;
	    return txt.value;
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
	getElapsedTime: function(time) {
		var dateAndTime = time.split('T');
		var date = dateAndTime[0].split('-');
		var year = date[0];
		var month = date[1];
		var day = date[2];
		return day+'-'+month+'-'+year;
	},
	zeroFill: function(i) {
		return (i < 10 ? '0' : '') + i;
	},
	scroll: function() {
		if($('#chat-wrap').data('bottom') == '1')
			$(window).scrollTop($(document).height());
	}
};
chat.init();