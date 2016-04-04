<?php 

$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/bookmark/bookmark.js', CClientScript::POS_BEGIN);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/friend/addRemove.js', CClientScript::POS_BEGIN);

$this->widget('mobile.widgets.UserPage.PopupAlert', array('class'=>'unfriend_confirm', 'content'=> Lang::t('friend', 'Do you want to unfriend with him!')));
$this->widget('mobile.widgets.UserPage.PopupAlert', array('class'=>'cancelrequest_confirm', 'content'=> Lang::t('friend', 'Do you want to cancel request?')));
$this->widget('mobile.widgets.UserPage.PopupAlert', array('class'=>'accepted_request_confirm', 'content'=> Lang::t('friend', 'Do you want to Agree request?')));
?>
					<div class="friend_request profile_tab">
                      <ul>
                        <li class="active mar_rig_10"><a href="<?php echo $user->createUrl('//my/view');?>"><?php echo Lang::t('general', 'Profile'); ?></a></li>
                        <li class=""><a href="<?php echo $user->createUrl('//photo/viewphoto');?>"><?php echo Lang::t('general', 'Photos'); ?></a></li>
                      </ul>
                    </div>
                    <div class="pad_left_10 userprofile">
                    	<div class="left user_info">
                        	<div class="left avatar_user">
                        	    <?php 
                        	    $imageLarge = $this->user->getNoAvatar();
                        	    if(!empty($this->user->avatar)){
                        	        if(is_numeric($this->user->avatar)){
                        	            $photo = Photo::model()->findByAttributes(array('id'=>$this->user->avatar, 'status'=>1));
                        	            if(!empty($photo->name) && file_exists(VHelper::model()->path ($photo->path .'/thumb160x160/'. $photo->name))){
                        	                $imageLarge = $photo->getImageLarge(true);
                        	            }
                        	        }
                        	    }
                        	    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/my/avatar.js?t=' .time(), CClientScript::POS_END);
                        	    ?>
                        	    <a class="ava" title="" href="javascript:void(0);" lcaption="" limg="<?php echo $imageLarge . '?t=' . time(); ?>" onclick="Avatar.viewPhotoDetail(this);" lurlphoto="<?php echo $this->usercurrent->createUrl('//photo/index');?>">
                        	        <img class="mw140 mh60" src="<?php echo $avatar; ?>" align="absmiddle">
                        	    </a>
                        	</div>
                            <div class="left user_info_right">
                            	<h3><?php echo $user->username; ?></h3>
                            	
                            	<?php if(!empty($current_location)): ?>
                                <p class="location">
                                	<?php if($online_status){ ?>
                                	<label class="online"></label> 
                                	<?php }else{ ?>
                                	<label class="offline"></label>
                                	<?php } ?>
                                	<?php echo $current_location; ?></p>
                                <?php endif; ?>
                                
                                <?php if(!empty($location)): ?>
                                <p><b><?php echo Lang::t('newsfeed', 'From'); ?>:</b> <?php echo $location; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if($current_user->id != $user->id): ?>
                        <div class="left user_func">
                        	<ul>
                            	<li class="message"><a title="Message" class="<?php echo $this->clsAccNotActived;?>" href="<?php echo Yii::app()->createUrl('/messages/cw/', array('u' => Util::encrypt($user->alias_name))); ?>">&nbsp;</a></li>
                                <li class="fancy coming-soon"><a title="Fancy" href="#">&nbsp;</a></li>
                                <li class="rate coming-soon"><a title="Rate" href="#">&nbsp;</a></li>
                                <?php if ($friendship_status === false || $friendship_status == YumFriendship::FRIENDSHIP_REJECTED || $friendship_status == YumFriendship::FRIENDSHIP_NONE) { ?>
                                <li class="add"><a user_id="<?php echo $user_id; ?>" alias="<?php echo Yii::app()->request->getQuery('alias');?>" class="add addfriend<?php echo $this->clsAccNotActived;?>" title="Add" href="javascript:void(0);">&nbsp;</a></li>
                                <?php } else {
                                	if ($friendship_status == YumFriendship::FRIENDSHIP_ACCEPTED) {
                                ?>
                                	<li class="add"><a user_id="<?php echo $user_id; ?>" alias="<?php echo Yii::app()->request->getQuery('alias');?>" class="friend unfriend<?php echo $this->clsAccNotActived;?>" title="Cancel request" href="javascript:void(0);">&nbsp;</a>                     	
                                	</li>
                                <?php }else{ ?>
                                	<?php if($request_itsmy){ ?>
                                	<li class="add"><a user_id="<?php echo $user_id; ?>" alias="<?php echo Yii::app()->request->getQuery('alias');?>" class="waiting waiting_me<?php echo $this->clsAccNotActived;?>" title="Add" href="javascript:void(0);">&nbsp;</a></li>
                                	<?php }else{ ?>
                                	<li class="add"><a user_id="<?php echo $user_id; ?>" alias="<?php echo Yii::app()->request->getQuery('alias');?>" class="waiting waiting_you<?php echo $this->clsAccNotActived;?>" title="Add" href="javascript:void(0);">&nbsp;</a></li>
                                	<?php } ?>
                                <?php }
                                }
                                ?>
                                
			                    <?php if(Bookmark::model()->checkBookmark($user_id)) { ?>
			                    	<li class="bookmark"><a user_id="<?php echo $user_id; ?>" class="unbookmark<?php echo $this->clsAccNotActived;?>" href="javascript:void(0);" title="">&nbsp;</a></li>
			                    <?php } else{ ?>
			                    	<li class="bookmark"><a user_id="<?php echo $user_id; ?>" class="bookmark<?php echo $this->clsAccNotActived;?>" href="javascript:void(0);" title="">&nbsp;</a></li>
			                    <?php } ?>
                            </ul>                                                        
                        </div>
                        <?php endif; ?>
                        <div class="left user_more_info">
                        	<div class="left mag_bot_5">
                            	<h3><?php echo Lang::t('settings', 'Basic Info'); ?></h3>
                                <p><?php echo $basic_info_value; ?></p>
                                <?php if(!empty($looking_for_value)): ?>
                                <p><b><?php echo Lang::t('settings', 'Looking for'); ?>:</b> <?php echo $looking_for_value; ?></p>
                                <?php endif; ?>
                                
                                <?php if(!empty($languages_value)): ?>
                                <p><b><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</b> <?php echo $languages_value; ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="left">
                            	<h3><?php echo Lang::t('settings', 'What you see'); ?></h3>
                            	<p>
                            	<?php if(!empty($height)){ ?>
                                <b><?php echo Lang::t('settings', 'Height'); ?>:</b> <?php echo $height; ?>
                                <?php }else{ ?>
                                <p><b><?php echo Lang::t('settings', 'Height'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
                                <?php } ?>
                                 | 
                                <!-- weight -->
                            	<?php if(!empty($weight)){ ?>
                                <b><?php echo Lang::t('settings', 'Weight'); ?>:</b> <?php echo $weight; ?>
                                <?php }else{ ?>
                                <b><?php echo Lang::t('settings', 'Weight'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
                                <?php } ?> 
			                    <!-- weight -->
			                     | 
				            	<!-- get build -->
				            	<?php if(!empty($build)){ ?>
				            	<b><?php echo Lang::t('settings', 'Build'); ?>: </b><?php echo $build; ?>
			            	    <?php }else{ ?>
				            	<b><?php echo Lang::t('settings', 'Build'); ?>: </b><?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 <?php } ?>
				            	<!-- end get build -->        
				            	 </p>        
				            	 
				            	 <p>
				            	 	<?php if(!empty($body_hair)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Body Hair'); ?>:</b> <?php echo $body_hair; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Body Hair'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 	| 
				            	 	<?php if(!empty($tattoo)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Tattoos'); ?>:</b> <?php echo $tattoo; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Tattoos'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>				            	 	
				            	 </p>               
				            	 <p>
				            	 	<?php if(!empty($piercing)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Piercings'); ?>:</b> <?php echo $piercing; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Piercings'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p> 				            	                                 
                            </div>
                            <div class="left">
                            	<h3><?php echo Lang::t('settings', 'Extra'); ?></h3>
				            	 <p>
				            	 	<?php if(!empty($occupation_value)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Occupation'); ?>:</b> <?php echo $occupation_value; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Occupation'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>
				            	 <p>
				            	 	<?php if(!empty($religion)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Religion'); ?>:</b> <?php echo $religion; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Religion'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p> 
				            	 <p>
				            	 	<?php if(!empty($attributes_value)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Attributes'); ?>:</b> <?php echo $attributes_value; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Attributes'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>
				            	 <p>
				            	 	<?php if(!empty($mannerism)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Mannerism'); ?>:</b> <?php echo $mannerism; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Mannerism'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>
				            	 <p>
				            	 	<?php 
				            	 	$smoke	=	ProfileSettingsConst::getSmokeLabel();
				            	 	if(!empty($this->user->profile_settings->smoke)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Smoke'); ?>:</b> <?php echo $smoke[$this->user->profile_settings->smoke]; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Smoke'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>
				            	 <p>
				            	 	<?php 
				            	 	$drink	=	ProfileSettingsConst::getDrinkLabel();
				            	 	if(!empty($this->user->profile_settings->drink)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Drink'); ?>:</b> <?php echo $drink[$this->user->profile_settings->drink]; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Drink'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>	
				            	 <p>
				            	 <?php 
				            	 	$club	=	ProfileSettingsConst::getClubLabel();
				            	 	if(!empty($this->user->profile_settings->club)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Club'); ?>:</b> <?php echo $club[$this->user->profile_settings->club]; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Club'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>	
				            	 <p>
				            	 <?php 
				            	 	if(!empty($safer_sex)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'Safe sex'); ?>:</b> <?php echo $safer_sex; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'Safe sex'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>	
				            	 <p>
				            	 <?php 
				            	 	if(!empty($public_information)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</b> <?php echo $public_information; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>	
				            	 <p>
				            	 <?php 
				            	 	if(!empty($live_with)){ ?>
				            	 		<b><?php echo Lang::t('settings', 'I Live With'); ?>:</b> <?php echo $live_with; ?>
				            	 	<?php }else{ ?>
				            	 		<b><?php echo Lang::t('settings', 'I Live With'); ?>:</b> <?php echo ProfileSettingsConst::EMPTY_DATA; ?>
				            	 	<?php } ?>
				            	 </p>					            	 				            	 				            	 		            	 			            	 			            	 			            	 			            	 				            	 				            	 				            	   				            	                            
				           </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    