<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/messages.js', CClientScript::POS_BEGIN);
?>
<?php if(!empty($message->id)):?>
    <?php 
    if(($message->toMe() && $message->message_read == Message::UNREAD_TO) || ($message->fromMe() && $message->message_read == Message::UNREAD_FROM)){
	    $message->read();
    }
    if($message->from_user->id == $this->usercurrent->id){
	    $msgUser = $message->to_user;
        $displayName = $message->to_user->getDisplayName();
    }else{
        $msgUser = $message->from_user;
        $displayName = $message->from_user->getDisplayName();
    }
    ?>
    <li class="item">
		<div class="feed clearfix">
			<a href="<?php echo $msgUser->getUserUrl()?>" title="" class="ava"><?php echo $msgUser->getAvatar(true);?></a>
			<a rel="<?php echo Util::encryptRandCode($message->id);?>" href="<?php echo $this->usercurrent->createUrl("//messages/view");?>" class="btn-messagemore">
				<div class="info">
					<h4><?php echo $displayName;?></h4>
					<p class="subtime"><?php echo Util::getElapsedTime($message->timestamp);?></p>
					<p class="text"><?php echo $message->message;?></p>
				</div>
			</a>
			<a href="javascript:void(0);" rel="<?php echo Util::encryptRandCode($message->id);?>" class="btn-delete"><i class="ismall ismall-delete"></i></a>
		</div>
	</li>
<?php endif;?>