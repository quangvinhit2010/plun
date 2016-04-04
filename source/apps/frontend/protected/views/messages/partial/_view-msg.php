<?php
if(!empty($msg)){
    if($read == true && (($msg->toMe() && $msg->message_read == Message::UNREAD_TO) || ($msg->fromMe() && $msg->message_read == Message::UNREAD_FROM))){        
        $msg->read();
    }
    $msg_User = $msg->from_user;
    $timestamp = ($msg->answered == 0) ? $msg->created : $msg->created;
?>
<li>
	<div class="left">
		<a href="<?php echo $msg_User->getUserUrl()?>" title="" class="ava"><?php echo $msg_User->getAvatar(true);?></a>
	</div>
	<div class="right">
		<p class="nick"><a href="<?php echo $msg_User->getUserUrl();?>"><b class="left"><?php echo $msg_User->getDisplayName()?></b></a><label class="right"><?php echo Util::getElapsedTime($timestamp);?></label></p>
		<p><?php echo $msg->message;?></p>
	</div>                      
</li>
<?php }?>
	