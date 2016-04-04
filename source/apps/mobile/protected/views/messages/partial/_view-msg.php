<?php
if(!empty($msg)){
    if($read == true && (($msg->toMe() && $msg->message_read == Message::UNREAD_TO) || ($msg->fromMe() && $msg->message_read == Message::UNREAD_FROM))){        
        $msg->read();
    }
    $msg_User = $msg->from_user;
    $timestamp = ($msg->answered == 0) ? $msg->created : $msg->created;
?>
<li class="item item-active">
	<div class="feed clearfix"> 
	<a class="ava" title="" href="<?php echo $msg_User->getUserUrl()?>"><?php echo $msg_User->getAvatar(true);?></a> 
	<span class="time"><?php echo Util::getElapsedTime($timestamp);?></span>
	    <div class="info">
	    	<h4><a href="<?php echo $msg_User->getUserUrl();?>"><?php echo $msg_User->getDisplayName()?></a></h4>
	        <p class="text"><?php echo $msg->message;?></p>                                    
		</div>
    </div>
</li>
<?php }?>
	
