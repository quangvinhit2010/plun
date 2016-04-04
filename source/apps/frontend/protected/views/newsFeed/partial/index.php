<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/newsfeed.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/perfect-scrollbar.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/html/css/perfect-scrollbar.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/rate/common.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/messages/common.js', CClientScript::POS_BEGIN);
CController::forward(Yii::app()->createUrl('/rate'), false);

$content =  $this->renderPartial('/messages/partial/_new-msg', array('model'=> new MessageForm(), 'to'=>$this->user), true);
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'new-messages', 'content'=>$content)); 
$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_status', 'content'=>''));
		
//render Smoke Drink Club
			$smoke_drink_club	=	array();
			//get Smoke
			$smoke	=	ProfileSettingsConst::getSmokeLabel();
			if(!empty($smoke[$this->user->profile_settings->smoke])):
				$smoke_drink_club[]	=	'<span>' . Lang::t('settings', 'Smoke') . ': </span>' . $smoke[$this->user->profile_settings->smoke] ;
			else: 
			    $smoke_drink_club[]	= '<span>' . Lang::t('settings', 'Smoke') . ': </span>' . ProfileSettingsConst::EMPTY_DATA ;
			endif;
			//get drink
			$drink	=	ProfileSettingsConst::getDrinkLabel();
			if(!empty($drink[$this->user->profile_settings->drink])):
				$smoke_drink_club[]	=	'<span>' . Lang::t('settings', 'Drink') . ': </span>' . $drink[$this->user->profile_settings->drink] ;
			else:
			    $smoke_drink_club[]	= '<span>' . Lang::t('settings', 'Drink') . ': </span>' . ProfileSettingsConst::EMPTY_DATA ;
			endif;
			//get Club
			$club	=	ProfileSettingsConst::getClubLabel();
			if(!empty($club[$this->user->profile_settings->club])):
				$smoke_drink_club[]	=	'<span>'. Lang::t('settings', 'Club') . ': </span>' . $club[$this->user->profile_settings->club] ;			
			else:
			    $smoke_drink_club[]	= '<span>' . Lang::t('settings', 'Club') . ': </span>' . ProfileSettingsConst::EMPTY_DATA ;
			endif;
			
			$smoke_drink_club	=	sizeof($smoke_drink_club)	?	implode('&ensp;<label></label>&ensp;', $smoke_drink_club) :	'';
			
			
//render Smoke Drink Club

?>

<div class="block news-feed user-info profile_setting_new">
    <div class="info-wrap"> 
        <div class="accordion-heading setting-title">
            <a href="javascript:void(0);" class="accordion-toggle profile-collapse"><i class="i18 i18-setting-profile-new"></i><span class="inline-text"><?php echo Lang::t('newsfeed', 'Profile')?></span><span class="line"></span><span class="arrow"><i class="i18"></i></span></a>
        </div>
    	<div class="info info-top">
	        <div class="extra-userlocation" id="">
	            <ul class="detail_1">
	                <?php if(!empty($location)): ?>
	                <!-- location -->
	                <li><span><?php echo Lang::t('newsfeed', 'From'); ?>:</span> <?php echo $location; ?></li>
	                <!-- end location -->
	                <?php endif; ?>            
	            </ul>
	            <h3 class="title_profile"><?php echo Lang::t('settings', 'Basic Info'); ?></h3>
		        <!-- basic detail -->
	            <ul class="detail_1">
	            	<li style="padding-left:7px;"><?php echo $basic_info_value; ?></li>
	                <?php if(!empty($looking_for_value)): ?>
	                <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Looking for'); ?>:</span> <?php echo $looking_for_value; ?></li>
	                 <?php endif; ?>
	                <?php if(!empty($languages_value)): ?>
	                <!-- Languages I Understand -->                
	                <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</span> <?php echo $languages_value; ?></li>
	                <!-- end Languages I Understand -->
	                 <?php endif; ?>
	            </ul>
	            <!-- end basic detail -->
	            
		        <h3 class="title_profile"><?php echo Lang::t('settings', 'What you see'); ?></h3>
	            <ul class="detail">
	            	<!-- get height -->
	            	<?php if(!empty($height)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Height'); ?>:</span><?php echo $height; ?><i></i></li>
	            	<?php }else{ ?>
	            	<li><span><?php echo Lang::t('settings', 'Height'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	<?php } ?>
	            	<!-- end get height -->
	            	<!-- get weight -->
	            	<?php if(!empty($weight)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Weight'); ?>:</span><?php echo $weight; ?><i></i></li>
	            	<?php }else{ ?>
	            	<li><span><?php echo Lang::t('settings', 'Weight'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?><i></i></li>
	            	<?php } ?>
	            	<!-- end get weight -->
	            	<!-- get build -->
	            	<?php if(!empty($build)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Build'); ?>:</span><?php echo $build; ?></li>
            	    <?php }else{ ?>
	            	    <li><span><?php echo Lang::t('settings', 'Build'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	            	<!-- end get build -->            	
	            </ul>	
	            <ul class="detail">
	                <!-- get body hair -->
	            	<?php if(!empty($body_hair)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Body Hair'); ?>:</span><?php echo $body_hair; ?><i></i></li>
            	    <?php }else{ ?>
	            	    <li><span><?php echo Lang::t('settings', 'Body Hair'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?><i></i></li>
	            	 <?php } ?>
	            	<!-- end get body hair -->
	            	
	                <!-- get body tattoo -->
	            	<?php if(!empty($tattoo)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Tattoos'); ?>:</span><?php echo $tattoo; ?><i></i></li>
            	    <?php }else{ ?>
	            	    <li><span><?php echo Lang::t('settings', 'Tattoos'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?><i></i></li>
	            	 <?php } ?>
	            	<!-- end get tattoo -->
	
	                <!-- get body Piercings -->
	            	<?php if(!empty($piercing)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Piercings'); ?>:</span><?php echo $piercing; ?></li>
            	    <?php }else{ ?>
	            	    <li><span><?php echo Lang::t('settings', 'Piercings'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	            	<!-- end get Piercings -->            	            	            	
	            </ul>
	
	            <h3 class="title_profile"><?php echo Lang::t('settings', 'Extra'); ?></h3>
	            <ul class="detail_1">
	                <!-- get Occupation -->
	            	<?php if(!empty($occupation_value)){ ?>
	            		<li><span><?php echo Lang::t('settings', 'Occupation'); ?>:</span><?php echo $occupation_value; ?></li>
	            	<?php }else{ ?>
	            	    <li><span><?php echo Lang::t('settings', 'Occupation'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	            	<!-- end get Occupation -->
	            	
	                <!-- get Religion -->
	            	<?php if(!empty($religion)){ ?>
	            		<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Religion'); ?>:</span><?php echo $religion; ?></li>
	            	<?php }else{ ?>
	            	    <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Religion'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	            	<!-- end get Religion -->
	                <!-- get Attributes -->
	            	<?php if(!empty($attributes_value)){ ?>
	            		<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Attributes'); ?>:</span><?php echo $attributes_value; ?></li>
	            	<?php }else{ ?>
	            	    <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Attributes'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	            	<!-- end get Attributes -->
	                <!-- get Mannerism -->
	            	<?php if(!empty($mannerism)): ?>
	            		<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Mannerism'); ?>:</span><?php echo $mannerism; ?></li>
	            	<?php endif; ?>
	            	<!-- end get Mannerism -->
	            	<?php if(!empty($smoke_drink_club)): ?>
	            	<li style="float: left; width: 100%;">
		                <!-- get Smoke drink club -->
		            	<?php echo $smoke_drink_club; ?>
		            	<!-- end get Smoke drink club -->
	            	</li>
	            	<?php endif; ?>
	            	
	                <!-- get Safe Sex -->
	            	<?php if(!empty($safer_sex)){ ?>
	            		<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Safe sex'); ?>:</span><?php echo $safer_sex; ?></li>
	            	<?php }else{ ?>
	            	    <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Safe sex'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	            	<!-- end get Safe Sex -->
	            	
	            	<!-- How Out Are You -->         
	            	<?php if(!empty($public_information)){ ?>   	            	
	            		<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</span><?php echo $public_information; ?></li>
	            	<?php }else{ ?>
	            	    <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	                <!-- How Out Are You -->
	                
	            	<!-- Live with -->         
	            	<?php if(!empty($live_with)){ ?>   	            	
	            		<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'I Live With'); ?>:</span><?php echo $live_with; ?></li>
	            	<?php }else{ ?>
	            	    <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'I Live With'); ?>:</span><?php echo ProfileSettingsConst::EMPTY_DATA; ?></li>
	            	 <?php } ?>
	                <!-- end Live with -->                
	            </ul> 
            </div>	        
        </div>
    </div>
    <div class="cont feed-list">
        <div>
            <div class="accordion-heading setting-title">
                <a href="javascript:void(0);" class="accordion-toggle status-collapse"><i class="i18 i18-setting-status-new"></i><span class="inline-text"><?php echo Lang::t('newsfeed', 'Status');?></span><span class="line"></span><span class="arrow"><i class="i18"></i></span></a>
            </div>
            <ul class="feed-list-item">
                <?php if($this->usercurrent->isFriendOf($this->user->id) || $this->user->isMe()){?>
                <?php if ($activities['data']) { ?>
                    <?php
                    foreach ($activities['data'] as $data) {   
							//is my status
							if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_POST_WALL){
								$status_text_is_me	=	true;
							}else{
								$status_text_is_me	=	false;
							} 
							if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_PHOTO_UPLOAD){
								$status_photo_is_me	=	true;
							}else{
								$status_photo_is_me	=	false;
							}							                    
                        ?>
                        <!-- single news feed item -->
                        <li class="item status_row_<?php echo $data->id; ?>">
                            <span class="marginline margin-top"></span>
                            <?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
                                    'data'=>$data,
                            		'status_text_is_me'	=>	$status_text_is_me,
                            		'status_photo_is_me'	=>	$status_photo_is_me
                            ));?>
                            <span class="marginline margin-bottom"></span>
                        </li>
                        <!-- single news feed item -->
                    <?php } ?>
                <?php } else { ?>
                    <li>
        		        <p class="no-request-friends" style="padding: 20px 10px;"><?php echo Lang::t("newsfeed", '{username} doesn’t post any status', array('{username}'=>$this->user->getDisplayName()))?></p>
        		    </li>
                <?php } ?>
                <?php }else{?>
                    <li>
        		        <p class="no-request-friends" style="padding: 20px 10px; line-height: 16px;"><?php echo Lang::t("newsfeed", 'Content is not shown because you are not this user\'s friend. Please send your friend request.')?></p>
        		    </li>
                <?php } ?>
            </ul>
            <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset" id="newsfeed_offset" />
            <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" /> 
            <?php if($this->usercurrent->isFriendOf($this->user->id) || $this->user->isMe()){?>
	            <?php if ($show_more): ?>
	                <div class="more-wrap-col2">
	                    <a class="showmore" onclick="NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 0);" href="javascript:void(0);"><?php echo Lang::t('general', 'Show More'); ?></a>
	                </div>
	            <?php endif; ?>
            <?php }?>
        </div>
    </div>
</div>


<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'list-userliked', 'content'=>'')); ?>
