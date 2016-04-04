<?php if(!empty($message->id)):?>
    <?php 
    /* if(($message->toMe() && $message->message_read == Message::UNREAD_TO) || ($message->fromMe() && $message->message_read == Message::UNREAD_FROM)){
	    $message->read();
    } */
    $unread = ($message->message_read == 0 || $message->message_read == 2) ? ' item-active' : '';
    if($message->from_user->id == $this->usercurrent->id){
	    $msgUser = $message->to_user;
        $displayName = $message->to_user->getDisplayName();
    }else{
        $msgUser = $message->from_user;
        $displayName = $message->from_user->getDisplayName();
    }
    
    $view_url = $this->usercurrent->createUrl('//messages/view', array('id' => Util::encryptRandCode($message->id)));
    ?>
	<li class="item<?php echo $unread; ?>">
	<div class="feed clearfix">
		<a href="<?php echo $msgUser->getUserUrl()?>" title="" class="ava"><?php echo $msgUser->getAvatar(true);?></a>
		<a rel="<?php echo Util::encryptRandCode($message->id);?>" href="<?php echo $view_url;?>" class="btn-messagemore">
		<div class="info">
			<h4><?php echo $displayName;?></h4>    								
					<p class="subtime"><?php echo Util::getElapsedTime($displayMsg->timestamp);?></p>
					<p class="text">
					<?php if($displayMsg->toMe()){?>
						<label class="replied"></label>
					<?php }else{?>
						<label class="forwarded"></label>                                	      
					<?php }?>
					<?php echo Util::partString($displayMsg->message, 0, 100);?>    								
					</p>
		</div>
		</a>
		<a href="javascript:void(0);" rel="<?php echo Util::encryptRandCode($message->id);?>" class="btn-delete"><i class="ismall ismall-delete"></i></a>
	</div>
</li>	
	
<?php endif;?>