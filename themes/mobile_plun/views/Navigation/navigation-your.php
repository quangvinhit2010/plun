<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/limitCounter.js');
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/friend/addRemove.js', CClientScript::POS_BEGIN);

$quotas = Message::model()->getQuotas($this->controller->usercurrent->id);

$looing_foronline	=	ProfileSettingsConst::getLookingForOnlineLabel();
$online_lookingfor_id	=	$usercurrent->profile->online_lookingfor;
$looking_online_status	=	isset($looing_foronline[$online_lookingfor_id])		?	$looing_foronline[$online_lookingfor_id] . ' | '	:	'';

$current_location	=	array();
if($district_id){
	$district =	$district_in_cache->getDistrictInfo($district_id);
	$current_location[]	=	$district['name'];
}
if($city_id){
	$city =	$city_in_cache->getCityInfo($city_id);
	$current_location[]	=	$city['name'];
}
if($state_id){
	$state =	$state_in_cache->getStateInfo($state_id);
	$current_location[]	=	$state['name'];
}
if($country_id){
	$country =	$country_in_cache->getCountryInfo($country_id);
	$current_location[]	=	$country['name'];
}

//general current location
if(!empty($current_location)){
	$current_location	=	implode(', ', $current_location);
}else{
	$current_location	=	'';
}

?>
<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_bookmark', 'content'=>'')); ?>
<div class="col-nav">
    <div class="user">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'uploadAvatar',
            'action' => Yii::app()->createUrl('//site/index', array()),
        ));
        ?>
        <div href="#" title="" class="photo">
            <div class="photo-wrap">
                <img src="<?php echo $this->controller->user->getAvatar(); ?>?t=<?php echo time(); ?>" width="150" alt="" border="" class="imgAvatar"/>
            </div>
        </div>
        <?php $this->endWidget(); ?>		
        <!-- user photo -->
        <div class="info">
            <h2 class="name"><?php echo $this->controller->user->getDisplayName(); ?></h2>  
            <div class="user_location">
            	<p class="current_location"><?php echo $looking_online_status; ?>
            		<a href="javascript:void(0);"><?php echo $current_location; ?></a>
            	</p>		
            </div>       
            <ul class="profile-tool">
                <li>
                    <a class="message<?php echo $this->controller->clsAccNotActived;?>" href="#" title=""><?php echo Lang::t('general', 'Message');?></a>                    
                </li>
                <li class="end">
                    <?php if($this->controller->usercurrent->isFriendOf($this->controller->user->id)){?>
                        <a class="chat<?php echo $this->controller->clsAccNotActived;?>" href="javascript:Chat.open_chat_box('<?php echo $this->controller->user->username;?>');" title="">chat</a>
                    <?php }else{?>
                        <a class="chat<?php echo $this->controller->clsAccNotActived;?>" href="javascript:Chat.can_not_chat();" title="">chat</a>
                    <?php }?>
                </li>
                <li>
                    <a class="fancy coming-soon<?php echo $this->controller->clsAccNotActived;?>" href="#" title=""><?php echo Lang::t('general', 'Fancy');?></a>
                </li>
                <li class="end">
                    <?php if ($friendship_status === false || $friendship_status == YumFriendship::FRIENDSHIP_REJECTED || $friendship_status == YumFriendship::FRIENDSHIP_NONE) { ?>
                        <a data-toggle="dropdown" title="add" href="javascript:void(0);" class="add<?php echo $this->controller->clsAccNotActived;?>" onclick="add_friend('<?php echo $friend_id; ?>', '<?php echo Yii::app()->request->getQuery('alias');?>');"><?php echo Lang::t('friend', 'Add'); ?></a>
                        <div class="pop-addfriend pop">
                            <span class="arrow"></span>
                            <div class="popcont">
                                <span class="icon-check"></span>
                                <p><?php echo Lang::t('friend', 'Your Friend Request has been sent!'); ?></p>
                            </div>
                        </div>
                    <?php
                    } else {
                        if ($friendship_status == YumFriendship::FRIENDSHIP_ACCEPTED) {
                            ?>
                            <a data-toggle="dropdown" title="" href="javascript:void(0);" class="friend<?php echo $this->controller->clsAccNotActived;?>"><?php echo Lang::t('friend', 'Friend'); ?></a>
                            <div class="pop-waiting pop">
                                <span class="arrow"></span>
                                <a class="btn-close" href="#" title="Close">X</a>
                                <div class="popcont">
                                    <p><?php echo Lang::t('friend', 'Do you want to unfriend with him!'); ?></p>
                                    <div class="block-btn">
                                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="unfriend_request('<?php echo $friend_id; ?>', '<?php echo Yii::app()->request->getQuery('alias');?>');"><?php echo Lang::t('friend', 'UnFriend'); ?></a>
                                    </div>
                                </div>                                

                            </div>                            
                    <?php } else { ?>
                            
                            <a data-toggle="dropdown" title="" href="javascript:void(0);" class="waiting<?php echo $this->controller->clsAccNotActived;?>"><?php echo Lang::t('friend', 'Waiting'); ?></a>
                            <div class="pop-waiting pop">
                                <span class="arrow"></span>
                                <a class="btn-close" href="#" title="Close">X</a>
                                <?php if($request_itsmy){ ?>
                                <div class="popcont">
                                    <p><?php echo Lang::t('friend', 'You has sent a Friend Request to him!'); ?></p>
                                    <div class="block-btn">
                                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="cancel_request('<?php echo $friend_id; ?>');"><?php echo Lang::t('friend', 'Cancel'); ?></a>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="popcont">
                                    <p><?php echo Lang::t('friend', 'You has sent a Friend Request to him!'); ?></p>
                                    <div class="block-btn">
                                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="agree_request('<?php echo $friend_id; ?>');"><?php echo Lang::t('friend', 'Agree'); ?></a>
                                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="decline_request('<?php echo $friend_id; ?>');"><?php echo Lang::t('friend', 'Decline'); ?></a>
                                    </div>
                                </div>                                
                                <?php } ?>
                            </div>
                        <?php }
                    }
                    ?>          
                </li>
                <li class="no_line">
                    <a class="rate coming-soon<?php echo $this->controller->clsAccNotActived;?>" href="#" title=""><?php echo Lang::t('general', 'Rate');?></a>
                </li>
				<li class="end no_line" id="boookmark-icon">
                    <?php if(Bookmark::model()->checkBookmark($this->controller->user->id)) { ?>
                    	<a class="bookmark_tru" onclick="Bookmark.delete_bm(<?php echo $this->controller->user->id;?>, this);" href="javascript:void(0);" title=""><?php echo Lang::t('bookmark', 'Bookmark');?></a>
                    <?php } else{ ?>
                    	<a class="bookmark_cong" onclick="Bookmark.add_bm(<?php echo $this->controller->user->id;?>);" href="javascript:void(0);" title=""><?php echo Lang::t('bookmark', 'Bookmark');?></a>
                    <?php } ?>
				</li>
            </ul>
            <!-- user menu -->
        </div>
    </div>
    <!-- user -->
</div>

