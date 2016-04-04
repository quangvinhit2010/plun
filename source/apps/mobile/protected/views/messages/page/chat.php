<?php $user = Yii::app()->user->data() ?>
<div id="chat-wrap" data-jid="<?php echo $username ?>" data-time="<?php echo time() ?>" data-bottom="1">
	<div class="chat-nickname"><?php if($isOnline) echo '<span class="online"></span>' ?><?php echo $username ?></div>
	<div class="content-message-wrap">
		<div class="load-old-message-wrap">
			<a href="javascript:;" id="load-old-message"><?php echo Lang::t('chat', 'See old message') ?></a>
			<img
				src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/loadingImg.gif" />
		</div>
		<ul id="chat-list-item"></ul>
		<div class="composing reveiver">
			<img width="25" class="avatar" src="<?php echo $avatar ?>">
			<div class="bubble-wrap">
				<div class="bubble">
					<span class="arrow-wrap"><span class="arrow"></span></span><img
						src="<?php echo Yii::app()->theme->baseUrl ?>/resources/css/images/composing.gif" />
				</div>
			</div>
		</div>
	</div>
	<div class="emoticons-wrap items"><div class="emoticons-item"><span class="emo emo-1"></span><span class="emo emo-2"></span><span class="emo emo-3"></span><span class="emo emo-4"></span><span class="emo emo-5"></span><span class="emo emo-6"></span><span class="emo emo-7"></span><span class="emo emo-8"></span><span class="emo emo-9"></span><span class="emo emo-10"></span><span class="emo emo-11"></span><span class="emo emo-12"></span><span class="emo emo-13"></span><span class="emo emo-14"></span><span class="emo emo-15"></span><span class="emo emo-16"></span><span class="emo emo-17"></span><span class="emo emo-18"></span><span class="emo emo-19"></span><span class="emo emo-20"></span><span class="emo emo-21"></span></div></div>
	<div id="chat-field-wrap">
		<div id="toggle-emo" class="emoticons-wrap">
			<span class="emoticons"></span>
		</div>
		<div class="send-wrap"><input type="submit" value="Send" /></div>
		<div class="input-wrap"><input id="input-text" type="text" placeholder="<?php echo Lang::t('messages', 'Write a reply...') ?>" /></div>
	</div>
</div>
<script>chat.initChatPage();</script>