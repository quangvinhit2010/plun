<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/messages/common.js', CClientScript::POS_BEGIN);
$quotas = Message::model()->getQuotas($this->usercurrent->id);
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'deleteConversation', 'content'=>''));
$styleBanner = "margin-top:0px;";
?>
<div class="col-feed col-left">
	<div class="block news-feed news-message">
		<div class="title">
			<h2><?php echo Lang::t('messages', 'Message');?></h2>
            <?php if($quotas['total'] >= $quotas['limit']):?>
                <?php
                $newMsg = 0;
                if($quotas['total'] > $quotas['limit']){ 
                    $newMsg = $quotas['total'] - $quotas['limit'];
                ?>
                    <div class="left anount_quota"><?php echo Lang::t('messages', 'Full your quota. You have {num} new message.', array('{num}'=>'<b>'.$newMsg.'</b>'))?></div>
                <?php }?>
            <?php endif;?>
			<div class="loading_status">
            	<p><b><?php echo $quotas['percent'];?>%</b></p>            	
                <div class="loading_percent">
                    <?php if(!empty($quotas['percent'])){?>
                    <label style="width:<?php echo $quotas['percent'];?>%;"></label>
                    <?php }?>
                </div>
            </div>
		</div>
		<div class="cont feed-list">
    		<div class="wrap_list_feed">
    			<div class="padding"></div>
    			<?php if(!empty($messages)){
    				$styleBanner = "margin-top:-40px;";
    			?>
    			<ul class="feed-list-item">
    			<?php foreach ($messages as $key=>$message){?>
    			    <?php
    			    if($message->from_user->id == $this->usercurrent->id){
    				    $msgUser = $message->to_user;
    			        $displayName = $message->to_user->getDisplayName();
                    }else{
                        $msgUser = $message->from_user;
                        $displayName = $message->from_user->getDisplayName();
                    }
                    $class = ($key == 0) ? ' item-active' : '';
                    $class2 = ($message->message_read == 2 && $message->from_user_id != $this->usercurrent->id) ? ' unread' : '';
                    $reply = $message->getAnswerLasted();
                    $displayMsg = $message;
                    if(!empty($reply)){
                        $displayMsg = $reply;
                    }
                   	$is_system = ($displayMsg->is_system == 1) ? 'system' : 'user' ;
                    ?>
    				<li class="item<?php echo $class.$class2;?> <?php echo $is_system;?>">
    					<div class="feed clearfix">
    						<a href="<?php echo $msgUser->getUserUrl()?>" title="" class="ava"><?php echo $msgUser->getAvatar(true);?></a>
    						<a rel="<?php echo Util::encryptRandCode($message->id);?>" href="<?php echo $this->usercurrent->createUrl("//messages/view");?>" class="btn-messagemore">
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
    				<?php }?>
    			</ul>
    			<?php }else{?>
    			    <ul>
    			        <li class="no-request-friends"><?php echo Lang::t('messages', 'No new messages')?></li>
    			    </ul>			    
    			<?php }?>
    		</div>
		</div>
		<input type="hidden" value="<?php echo count($messages); ?>" id="offsetMsg" />
		<input type="hidden" value="<?php echo $limit; ?>" id="quotasMsg" />
		<input type="hidden" value="<?php echo $this->usercurrent->createUrl("//messages/delete");?>" id="urlDel" />
	</div>
	<!-- news feed -->
</div>

<div class="col-right">
	 <div class="title_message">
    	<div class="left">&nbsp</div>
    	<?php if($this->usercurrent->isActive()){?>
        <div class="right"><a href="#"><?php echo Lang::t('messages', 'New message')?></a></div>
        <?php }?>
    </div>
    <div class="message-detail left width-message-detail">
    </div>
    <div class="right" style="<?php echo $styleBanner;?>">
    	<a href="#"><img align="absmiddle" src="<?php echo Yii::app()->theme->baseUrl . '/resources/html/'?>images/banner_120x600.jpg"></a>
    </div>
	<!-- block-chat area -->
</div>


<?php 
$msgModel = new MessageForm();
$content =  $this->renderPartial('partial/_new-msg', array('model'=> $msgModel), true);
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'new-messages','content'=>$content)); 
?>
