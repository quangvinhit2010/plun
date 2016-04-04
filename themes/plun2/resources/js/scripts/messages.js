$(function(){
	Messages.msgInit();
	inMessageSite = true;
});

var Messages = {
	initial: false,
	unique: '',
	jid: null,
	bareJid: null,
	conversation: null,
	myAvatar: null,
	yourAvatar: null,
	msgInit: function () {
		Messages.resizeMessageWrap();
		$('#message-wrap').mCustomScrollbar({
			scrollInertia: 0,
			autoHideScrollbar: true,
			mouseWheel: {
				preventDefault: true,
				scrollAmount: 100
			},
			callbacks: {
				onTotalScrollBack: function() {
					Messages.getChatHistory();
				},
				onTotalScroll: function() {
					$('#message-wrap').data('bottom', '1');
					$('.content .scroll-down').hide();
				},
				onScroll: function() {
					if(this.mcs.topPct < 99)
						$('#message-wrap').data('bottom', '0');
				}
			}
		});
		$('.content .scroll-down').click(function(){
			$(this).hide();
			Messages.scrollBottom();
		});
		
		Messages.unique = $.now();
		Messages.myAvatar = $('.main_menu_avatar img').attr('src');
		$('.listConversation').on('click', '.del', function(){
			Messages.deleteMessages($(this));
			return false;
		});
		$('.listConversation').on('click', '.message_item', function(e) {
			if($(this).closest('li').hasClass('deleting'))
				return false;
			Messages.initial = true;
			Messages.getConversation($(this));
			Messages.checkOnline($(this));
			e.preventDefault();
		});
		if($('.listConversation .message_item:first').length > 0) {
			Messages.markUnread($('.listConversation > .item'));
			$('.listConversation .message_item:first').trigger('click');
			$('.message_send .message_input').show();
		}
		
		$('.message_input .emoticons').click(function(){
			$(this).prev().toggle();
		});
		$('.message_input .emo').click(function(){
			var itemClass = $(this).attr('class').replace('emo ', '');
			var text = '';
			$.each(chat.emoticons, function(index, val){
				if(val == itemClass) {
					text = chat.decodeHtml(index);
					return false;
				}
			});
			
			var textArea = $('.message_input textarea');
			var startSelect = textArea[0].selectionStart;
			var firstText = textArea.val().slice(0, startSelect);
			var endText = textArea.val().slice(startSelect, textArea.val().length);
			textArea.val(firstText+text+endText);
			textArea.selectRange(firstText.length+text.length);
			
			$(this).closest('.emoticons-item').hide();
		});
		$(document).on('click', '.pagging a', function(){
			objCommon.loading();
			var url = $(this).data('url');
			var next = $(this).data('next');
			$.ajax({
				url: url,
				data: {page: next, offset: $('.listConversation').data('offset')},
				success: function( response ) {					
					var temp = $('<div>'+response+'</div>');
					var item = temp.find('.listConversation > li.item');
					Messages.markUnread(item);
					$('.listConversation').append(item);
					$('.sticky_column').fixed_col_scroll();
					var pagging = temp.find('.pagging');
					if(pagging.length > 0)
						$('.pagging').replaceWith(pagging);
					else
						$('.pagging').remove();
					objCommon.unloading();
				}
			});
		});
		
		$('.replyMsg').keydown(function(e){
			if(e.which === 13) {
				e.preventDefault();
				var body = $(this).val();
				
				if(body != '') {
					var messageId = chat.makeMessageId();
					var message = $msg({to: Messages.bareJid, "type": "chat"}).c('body', {'data-id': messageId}).t(body).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
					chat.conn.send(message);
					
					var selfBareJid = chat.jidToBareJid(XMPP_JID);
					var message = $msg({to: selfBareJid, "type": "headline"}).c('body', {'data-id': messageId}).t(body).up().c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'message', to: Messages.jid, unique: chat.unique, messageunique: Messages.unique});
					chat.conn.send(message);
					
					if($('#cb-'+Messages.jid).length > 0)
						chat.sendMessage($('#cb-'+Messages.jid), body, messageId);
					
					if($('.list_message .time-separator').last().data('date') != chat.serverDate) {
						$('.list_message').append('<li data-date="'+chat.serverDate+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+chat.serverDate+'</span></li>');
					}
					$('.list_message').append(Messages.buildSendMessage(body));
					$('li.active .preview-message').html('<ins class="next_mes"></ins>'+body);
					
					Messages.scrollBottom();
					
					$(this).val('');
					$(this).data('composing', false);
				}
			}
		}).keyup(function(e){
			if(e.which != 13) {
				if($(this).val() === '') {
					var notifyUnComposing = $msg({to: Messages.bareJid, "type": "chat"}).c('uncomposing', {xmlns: "http://jabber.org/protocol/chatstates"});
					chat.conn.send(notifyUnComposing);
					$(this).data('composing', false);
				} else {
					var composing = $(this).data('composing');
					if (!composing) {
						var notifyComposing = $msg({to: Messages.bareJid, "type": "chat"}).c('composing', {xmlns: "http://jabber.org/protocol/chatstates"});
						chat.conn.send(notifyComposing);
						$(this).data('composing', true);
					}
				}
			}
		}).click(function(){
			$('.message_input .emoticons-item').hide();
		});
		
		chat.conn.addHandler(function(message){
			message = $(message);
			var fullJid = message.attr('from');
			var bareJid = Strophe.getBareJidFromJid(fullJid);
			var jid = chat.bareJidToJid(bareJid);
			
			if(message.find('delay').length > 0)
				return true;
			
			if(jid == Messages.jid) {
				if(message.find('composing').length > 0) {
					var avatar = '<a target="_blank" title="" href="/u/'+Messages.jid+'"><img width="35" alt="avatar" src="'+Messages.yourAvatar+'" /></a>';
					var username = '<a target="_blank" href="/u/'+Messages.jid+'"><b class="left">'+Messages.jid+'</b></a>';
					
					var chatItem = '<li>';
						chatItem += 	'<div class="left avatar">'+avatar+'</div>';
						chatItem += 	'<div class="left info">';
						chatItem += 		'<p class="nick">'+username+'<!-- <label class="right">1 hour ago</label> --></p>';
						chatItem += 		'<p><img src="http://'+location.host+'/themes/plun2/resources/html/css/images/composing.gif" /></p>';
						chatItem += 	'</div>';
						chatItem += '</li>';
					$('.content .composing').append(chatItem);
					if($('#message-wrap').data('bottom') == 1)
						Messages.scrollBottom();
				} else if(message.find('active').length > 0) {
					var body = message.find('> body').text();
					$('.content .composing').html('');
					
					if($('.list_message .time-separator').last().data('date') != chat.serverDate) {
						$('.list_message').append('<li data-date="'+chat.serverDate+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+chat.serverDate+'</span></li>');
					}
					
					$('.list_message').append(Messages.buildReceiveMessage(body));
					$('li.active .preview-message').html('<ins class="back_mes"></ins>'+body);
					
					if($('#message-wrap').data('bottom') == 1)
						Messages.scrollBottom();
					else
						$('.content .scroll-down').show();
					
				} else if(message.find('uncomposing').length > 0) {
					$('.content .composing').html('');
				}
			}
			
			return true;
		}, null, "message", "chat");
		
		chat.conn.addHandler(function(message) {
			message = $(message);
			var prefer = message.find('prefer');
			var type = prefer.attr('type');
			var messageUnique = prefer.attr('messageunique');
			var jid = prefer.attr('to');
			if(jid == Messages.jid && type == 'message' && messageUnique != Messages.unique) {
				var body = message.find('body').text();
				if($('.list_message .time-separator').last().data('date') != chat.serverDate) {
					$('.list_message').append('<li data-date="'+chat.serverDate+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+chat.serverDate+'</span></li>');
				}
				$('.list_message').append(Messages.buildSendMessage(body));
				Messages.scrollBottom();
			}
			return true;
		}, null, "message", "headline");
		
		chat.conn.addHandler(function() {
			$('#message-wrap').removeClass('getting-chat');
			return true;
		}, "urn:xmpp:archive", "iq", "error");
		
		$(window).resize(function(){
			Messages.resizeMessageWrap();
		});
	},
	getConversation: function(item) {
		$('#message-wrap').addClass('getting-chat');
		$('.list_message').html('');
		$('.content .composing').html('');
		
		$('.listConversation .active').removeClass('active');
		item.closest('li').addClass('active');
		
		Messages.yourAvatar = $('.listConversation .active').find('.avatar img').attr('src');
		
		Messages.jid = item.find('.nickname > a').text();
		Messages.bareJid = chat.jidToBareJid(Messages.jid);
		
		$('.list_explore .title .left').text(Messages.jid);
		
		var iq = $iq({
			type: 'get',
			id: 'inSiteMessage'+Messages.jid
		}).c('list', {
			xmlns: 'urn:xmpp:archive',
			'with': Messages.bareJid
		}).c('set', {
			xmlns: 'http://jabber.org/protocol/rsm'
		}).c('set', {xmlns: 'http://jabber.org/protocol/rsm'}).c('max').t('1000');
		
		if(chat.isConnected)
			chat.conn.sendIQ(iq, Messages.onGetConversation);
		else {
			var checkChatInstance;
			checkChatInstance = setInterval(function(){
				if(chat.isConnected) {
					chat.conn.sendIQ(iq, Messages.onGetConversation);
					clearInterval(checkChatInstance);
				}
			}, 50);
		}
	},
	onGetConversation: function(iq) {
		iq = $(iq);
		var conversation = iq.find('chat').map(function() { return $(this).attr("start"); });

		Messages.page = Number(iq.find('count').text()) - 1;
		Messages.getChat(Messages.jid, conversation[Messages.page]);
		
		Messages.conversation = conversation;
	},
	getChat: function(jid, start, end) {
		var iq = $iq({
			type: 'get',
			id: 'inSiteMessage'+Messages.jid
		}).c('retrieve', {
			xmlns: 'urn:xmpp:archive',
			'with': Messages.bareJid,
			start: start
		}).c('set', {xmlns: 'http://jabber.org/protocol/rsm'}).c('max').t('100000');
		
		if(typeof end !== 'undefined') {
			iq.up().up().attrs({end: end});
		}
		
		chat.conn.sendIQ(iq, Messages.onGetChat);
	},
	onGetChat: function(iq) {
		$('#message-wrap').removeClass('getting-chat');
		
		iq = $(iq);
		
		if(iq.attr('id').replace('inSiteMessage', '') == Messages.jid) {
			var child = iq.children().children();
			var length = child.length - 1;
			
			var chatList = '';
			
			for(i=0;i<length;i++) {
				var item = child.eq(i);
				var body = item.find('body').text();
				
				if(item.is('to')) {
					var chatItem = Messages.buildSendMessage(body);
				} else {
					var chatItem = Messages.buildReceiveMessage(body);
				}
					
				chatList += chatItem;
			}
			
			if(Messages.initial) {
				$('.list_message').prepend(chatList);
				Messages.initial = false;
				
				if(Number(iq.find('count').text()) > 0) {
					var start = iq.children().attr('start');
					var elapsedTime = chat.getElapsedTime(start);
					
					var lastTimeSeparator = $('.list_message .time-separator').last();
					if(lastTimeSeparator.data('date') == elapsedTime)
						lastTimeSeparator.remove();
					
					$('.list_message').prepend('<li data-date="'+elapsedTime+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+elapsedTime+'</span></li>');
				}
				
				Messages.scrollBottom();
			} else {
				var csbContainer = $('#message-wrap .mCSB_container');
				var csbContainerBeforeHeight = csbContainer.height();
				
				$('.list_message').prepend(chatList);
				
				if(Number(iq.find('count').text()) > 0) {
					var start = iq.children().attr('start');
					var elapsedTime = chat.getElapsedTime(start);
					$('.list_message').prepend('<li data-date="'+elapsedTime+'" class="time-separator"><span class="separator-line"></span><span class="separator-date">'+elapsedTime+'</span></li>');
				}
				
				var csbContainerAfterHeight = csbContainer.height();
				var scrollTo = csbContainerBeforeHeight - csbContainerAfterHeight + csbContainer.position().top;
				$('#message-wrap').mCustomScrollbar('update');
				$('#message-wrap').mCustomScrollbar('scrollTo', scrollTo,{timeout:0});
			}
			
			if($('.list_message').height() <= $('#message-wrap').height())
				Messages.getChatHistory();
		}
	},
	getChatHistory: function() {
		if(!$('#message-wrap').hasClass('getting-chat') && Messages.page != 0) {
			$('#message-wrap').addClass('getting-chat');
			if(Messages.page >= 1) {
				var start = Messages.conversation[Messages.page-1];
				var end = Messages.conversation[Messages.page];

				end = chat.Iso8601ToUnix(end) - 1000;
				end = chat.unixToIso8601(end);

				Messages.getChat(Messages.jid, start, end);
				Messages.page -= 1;
			}
		}
	},
	buildSendMessage: function(body) {
		var avatar = '<img width="35" alt="avatar" src="'+Messages.myAvatar+'" />';
		var username = '<b class="left">'+usercurrent+'</b>';
		
		var chatItem = Messages.buildMessage(body, avatar, username);
		return chatItem;
	},
	buildReceiveMessage: function(body) {
		var avatar = '<a target="_blank" title="" href="/u/'+Messages.jid+'"><img width="35" alt="avatar" src="'+Messages.yourAvatar+'" /></a>';
		var username = '<a target="_blank" href="/u/'+Messages.jid+'"><b class="left">'+Messages.jid+'</b></a>';
		
		var chatItem = Messages.buildMessage(body, avatar, username);
		return chatItem;
	},
	buildMessage: function(body, avatar, username) {
		body = Strophe.xmlescape(body);
		body = chat.replaceEmoticons(body);
		
		var chatItem = '<li>';
			chatItem += 	'<div class="left avatar">'+avatar+'</div>';
			chatItem += 	'<div class="left info">';
			chatItem += 		'<p class="nick">'+username+'<!-- <label class="right">1 hour ago</label> --></p>';
			chatItem += 		'<p>'+body+'</p>';
			chatItem += 	'</div>';
			chatItem += '</li>';
		return chatItem;
	},
	scrollBottom: function() {
		$('#message-wrap').mCustomScrollbar('update');
		$('#message-wrap').mCustomScrollbar('scrollTo','bottom',{timeout:0});
	},
	resizeMessageWrap: function() {
		$('.message_send').css({
			minHeight: 'inherit',
			height: $(window).height() - 84 + 'px'
		});
		$('#message-wrap').height($('.message_send').height() - 159);
		$('.sticky_column').fixed_col_scroll();
	},
	checkOnline: function(item) {
		var data = {username: Messages.jid};
		
		if(item.closest('.item').hasClass('unread')) {
			data.updateOffline = '1';
			item.closest('.item').removeClass('unread');
			
			var count = $('.main_menu_message .count');
			var num = Number(count.text());
			if(num == '1')
				count.remove();
			else
				count.text(num - 1);
		}
		
		$.post('/my/CheckOnlineByUsername?t='+$.now(), data, function(response){
			if(response == '1') {
				var title = $('.message_send .title .left');
				title.html(title.text()+'<span class="online"></span>');
			}
		});
	},
	deleteMessages: function(del) {
		var item = del.closest('li.item');
		$( ".popup-alert.deleteConversation .frame_content" ).html(tr('Are you sure you want to delete this conversation?'));
		$( ".popup-alert.deleteConversation" ).pdialog({
			title: tr('Message'),
			buttons: [
						{
						  text: tr("OK"),
						  click: function() {
							  del.replaceWith('<div class="loadingInside"></div>');
							  item.addClass('deleting');
							  
							  var buddy_username = item.find('.nickname a').text();
							  var data = {buddy_username: buddy_username};
							  
							  var currentPagging = $('.message_list .pagging');
							  if(currentPagging.length > 0) {
								  var listConversation = $('.listConversation');
								  var limit = listConversation.data('limit');
								  var currentItem = $('.item', listConversation);
								  if(currentItem.length == limit) {
				        				data.load_new = '1';
								  } else if(currentItem.length > limit) {
									  	listConversation.data('offset', Number(listConversation.data('offset'))-1);
								  }
							  }
							  
							  $.post('/messages/deleteMessage', data, function(response){

								  if(response != '') {
									  var temp = $('<div>'+response+'</div>');
									  listConversation.append(temp.find('.listConversation > li:last'));
									  var responsePagging = temp.find('.pagging');
		 			    			  
		 			    				if(responsePagging.length == 0)
		 			    					currentPagging.remove();
		 			    				else
		 			    					currentPagging.replaceWith(responsePagging);
								  }
								  
								  if(item.hasClass('active')) {
									  var next = item.next();
									  if(next.length > 0)
										  next.find('.message_item').trigger('click');
									  else {
										  var prev = item.prev();
										  if(prev.length > 0)
											  prev.find('.message_item').trigger('click');
										  else {
											  $('#no_mess').show();
											  $('.message_send .title .left').html('');
											  $('.list_message').empty();
										  }
									  }
								  }
								  
								  item.remove();
							  });
							  
							  $( this ).pdialog( "close" );
						  }
						},
						{
						  text: tr("Cancel"),
						  click: function() {
							  $( this ).pdialog( "close" );
						  }
						},
					  ],
		});
	},
	markUnread: function(items) {
		var listOffline = $('.main_menu_message > a').data('offline-message').split(',');
		items.each(function(){
			var username = $(this).find('.nickname a').text();
			if(listOffline.indexOf(username) != -1)
				$(this).addClass('unread');
		});
	}
}