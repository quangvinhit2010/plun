<?php $user = Yii::app()->user->data() ?>
<div class="content-message-wrap">
	<ul id="m-tab">
		<li class="active"><a href="<?php echo $user->createUrl('//messages/index'); ?>"><?php echo Lang::t('messages', 'Messages') ?></a></li>
		<li><a href="<?php echo $user->createUrl('//messages/friends'); ?>"><?php echo Lang::t('messages', 'Friends list') ?></a></li>
	</ul>
	<?php if($messages): $now = time(); ?>
	<ul id="listConversation" class="list-conversation">
		<?php foreach($messages as $k => $message):
				if($k == $limit)
					break;
				$username = str_replace('@'.Yii::app()->params['XMPP']['server'], '', $message['jid']);
				$xmlMessage = new SimpleXMLElement($message['msg']);
				$xmlMessage = (Array)$xmlMessage;
		?>
		<li class="item">
			<a href="<?php echo $this->createUrl('messages/cw', array('u'=>Util::encrypt($username),'t'=>$now)) ?>">
				<span class="left avatar"><img width="40" align="absmiddle" src="<?php echo Yii::app()->createUrl('/site/avatar', array('uid'=>$username)) ?>"></span>
				<span class="message_item">
					<p class="chat-nickname"><?php echo $username ?></p>
					<p class="time"><?php echo Util::getElapsedTime(strtotime($message['ts'])) ?></p>
					<p class="preview-message"><label class="<?php echo ($message['direction']) ? 'replied' : 'forwarded' ?>"></label><?php echo $xmlMessage['body'] ?></p>
				</span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	<ul id="no_mess"<?php if($messages) echo ' style="display: none;"' ?>>
		<li class="no_mess">
			<div class="no-status_feed">
				<span class="no-content"><?php echo strtoupper(Lang::t('messages', 'No content to show'));?></span>
			</div>
		</li>
	</ul>
	<?php if(count($messages) > $limit): ?>
	<div class="block_loading">
		<a href="javascript:void(0);" data-next="<?php echo $page+1 ?>" data-url="<?php echo $this->usercurrent->createUrl("//messages/index");?>"><span></span></a>
		<img style="display: none;" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/loadingImg.gif" />	
	</div>
	<?php endif; ?>
</div>
<script>chat.initListMessagePage();</script>