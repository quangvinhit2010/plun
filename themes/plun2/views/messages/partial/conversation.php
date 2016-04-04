<?php if($messages): ?>
<ul class="listConversation" data-limit="<?php echo $limit ?>" data-offset="0">
	<?php foreach($messages as $k => $message):
			if($k == $limit)
				break;
			$username = str_replace('@'.Yii::app()->params['XMPP']['server'], '', $message['jid']);
			$xmlMessage = new SimpleXMLElement($message['msg']);
			$xmlMessage = (Array)$xmlMessage;
	?>
	<li class="item">
		<div class="left avatar"><a target="_blank" href="/u/<?php echo $username ?>"><img width="50" align="absmiddle" src="<?php echo Yii::app()->createUrl('/site/avatar', array('uid'=>$username)) ?>"></a></div>
		<div class="left message_item">
		<p class="nickname"><a href="/u/tomlex25"><?php echo $username ?></a></p>
		<p class="time"><?php echo Util::getElapsedTime(strtotime($message['ts'])) ?></p>
		<p class="preview-message"><ins class="<?php echo ($message['direction']) ? 'back_mes' : 'next_mes' ?>"></ins><?php echo $xmlMessage['body'] ?></p>
		</div>
		<a class="del" href="#"></a>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
<ul id="no_mess"<?php if($messages) echo ' style="display: none;"' ?>>
	<li class="no_mess">
		<div class="no-status_feed">
			<span class="icon_post_status"><i class="icon_common"></i><span><?php echo strtoupper(Lang::t('general', 'No content to show'));?></span></span>
		</div>
	</li>
</ul>
<?php if(count($messages) > $limit): ?>
<div class="pagging"><a href="javascript:void(0);" data-next="<?php echo $page+1 ?>" data-url="<?php echo $this->usercurrent->createUrl("//messages/conversation");?>"><ins></ins></a></div>
<?php endif; ?>