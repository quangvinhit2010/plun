	<?php
		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/newsfeed.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/friend.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/bookmark.js');
				
		$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_status', 'content'=>''));
		
		Yii::app()->clientScript->registerScript('Profile',"
			$(document).ready(function(){
					$('.list-preview-photo').colorbox({
			        	  slideshowAuto: false,
			        	  fixed: true,
			        	  scrolling: false,
			        	  innerHeight: true,
			        	  scalePhotos: true,
			        	     maxWidth: '100%',
			        	  maxHeight: '95%'
		   			 });
				
			        window.onload = function(){
                        $(\".sticky_column\").fixed_col_scroll();
                    }
					$('.avatar_thumbnail').colorbox({
					  slideshowAuto: false,
					  rel:'avatar_thumbnail',
					  fixed: true,
					  scrolling: false,
					  innerHeight: true,
					  scalePhotos: true,
					     maxWidth: '100%',
					  maxHeight: '95%'
					 });
					objCommon.loadFristElement('.wrapper_main_menu',objCommon.checkMenuBar);
					$(\".photo_video .content ul li.bg_soc a ins\").hover(function(){
						$(this).next(\".tooltip\").show();
					})
					$(\".photo_video .content ul li.bg_soc a ins\").mouseout(function(){
						$(this).next(\".tooltip\").hide();
					})
					
					$(\".main_profile .profile_function .list_function ul li.more\").click(function(){
						$(this).next(\"ol.list_more_function\").toggle();	
					})
			
					objCommon.sprScroll(\".popup_chondanhhieu .content\");
				
					$( \".showpopup_message_user\" ).on('click',function() {
						$( \".popup_message_user\" ).exists(function(){
							$( \".popup_message_user\" ).pdialog({
							open: function(event, ui) {
								$(\"body\").css({ overflow: 'hidden' });
								objCommon.no_title(this); // config trong file jquery-ui.js
								objCommon.outSiteDialogCommon(this);
							},
							resizable: false,
							position: 'middle',
							draggable: false,
							autoOpen: false,
							center: true,
							width: 550,
							modal: true,
							buttons: {				
								\"Cancel\": function() {
									text: tr(\"Cancel\"),
									$(\"#msg-form\").trigger(\"reset\");
									$( this ).dialog( \"close\" );
								},
								\"Submit\": {
									text: tr(\"Submit\"),
									class: 'active',
									click: function(){
										if($('#MessageForm_body').val() == ''){
											$( this ).dialog( \"close\" );
											Util.popAlertSuccess(tr('Please input a message!'), 300);	
											setTimeout(function () {
												$( \".pop-mess-succ\" ).pdialog('close');
											}, 1000);
											return false;				
															
										}
										objCommon.loading();
										$.ajax({
											type: \"POST\",
											url: $('#msg-form').attr('action'),
											data: $('#msg-form').serialize(),
											dataType: 'json',
											success: function( response ) {	
												objCommon.unloading();
												$(\"#msg-form\").trigger(\"reset\");
												$( \".popup_message_user\" ).pdialog('close');
												if(response.status == true){
													Util.popAlertSuccess(response.msg, response._wd);
													setTimeout(function () {
														$( \".pop-mess-succ\" ).pdialog('close');
													}, 3000);
												}else if(response.status == false){
													Util.popAlertFail(response.msg, response._wd);
													setTimeout(function () {
														$( \".pop-mess-fail\" ).pdialog('close');
													}, 3000);
												}												
												
											}
										});
									}
								},
							},		
						});
					});	
				});
			});
",
		CClientScript::POS_END);
		
		$quotas = Message::model()->getQuotas($current_user->id);
		
		$country_in_cache = new CountryonCache();
		$state_in_cache	=	new StateonCache();
		$city_in_cache = new CityonCache();
		$district_in_cache = new DistrictonCache();
				
		$looing_foronline	=	ProfileSettingsConst::getLookingForOnlineLabel();
		$online_lookingfor_id	=	$user->profile->online_lookingfor;
		$looking_online_status	=	isset($looing_foronline[$online_lookingfor_id])		?	' & ' . $looing_foronline[$online_lookingfor_id]  :	'';
		
		//get online status
		$elasticsearch	=	new Elasticsearch();
		$online_data	=	$elasticsearch->checkOnlineStatus(array($friend_id));
		$online_status	=	isset($online_data['online'][$friend_id])	?	true	:	false;
		if($user->profile_location){
			$country_id		=	$user->profile_location->current_country_id;
			$state_id		=	$user->profile_location->current_state_id;
			$city_id		=	$user->profile_location->current_city_id;
			$district_id	=	$user->profile_location->current_district_id;
		}else{
			$country_id		=	$user->profile_settings->country_id;
			$state_id		=	$user->profile_settings->state_id;
			$city_id		=	$user->profile_settings->city_id;
			$district_id	=	$user->profile_settings->district_id;
		}
				
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
		
		
		//render Smoke Drink Club
		$smoke_drink_club	=	array();
		//get Smoke
		$smoke	=	ProfileSettingsConst::getSmokeLabel();
		if(!empty($smoke[$user->profile_settings->smoke])):
		$smoke_drink_club[]	=	'<span>' . Lang::t('settings', 'Smoke') . ': </span>' . $smoke[$user->profile_settings->smoke] ;
		else:
		$smoke_drink_club[]	= '<span>' . Lang::t('settings', 'Smoke') . ': </span>' . ProfileSettingsConst::EMPTY_DATA ;
		endif;
		//get drink
		$drink	=	ProfileSettingsConst::getDrinkLabel();
		if(!empty($drink[$user->profile_settings->drink])):
		$smoke_drink_club[]	=	'<span>' . Lang::t('settings', 'Drink') . ': </span>' . $drink[$user->profile_settings->drink] ;
		else:
		$smoke_drink_club[]	= '<span>' . Lang::t('settings', 'Drink') . ': </span>' . ProfileSettingsConst::EMPTY_DATA ;
		endif;
		//get Club
		$club	=	ProfileSettingsConst::getClubLabel();
		if(!empty($club[$user->profile_settings->club])):
		$smoke_drink_club[]	=	'<span>'. Lang::t('settings', 'Club') . ': </span>' . $club[$user->profile_settings->club] ;
		else:
		$smoke_drink_club[]	= '<span>' . Lang::t('settings', 'Club') . ': </span>' . ProfileSettingsConst::EMPTY_DATA ;
		endif;
			
		$smoke_drink_club	=	sizeof($smoke_drink_club)	?	implode('&ensp;<label></label>&ensp;', $smoke_drink_club) :	'';
			
			
		//render Smoke Drink Club
		
		$content =  $this->renderPartial('/messages/partial/_profile-new-msg', array('model'=> new MessageForm(), 'to'=>$this->user), true);
		
	?>
	<!-- InstanceBeginEditable name="doctitle" -->
    <div class="container pheader wrap_scroll clearfix hasBanner_160 page_profiles">
      <div class="wrap-feed left">
      <div class="shadow_top"></div>
        <div class="main_profile sticky_column">
          <?php 
                $urlAvatar =  $imageLarge = $user->getNoAvatar();
                if(!empty($user->avatar)){
                    if(is_numeric($user->avatar)){
                    	$photo = Photo::model()->findByAttributes(array('id'=>$user->avatar, 'status'=>1));
                    	if($photo){
                    		$urlAvatar = $photo->getImageThumbnail160x160(true);
                    		$imageLarge = $photo->getImageLarge(true);
                    	}else{
                    		$imageLarge	=	$urlAvatar;
                    	}
                    
                    }else{
                    	$urlAvatar = $imageLarge = VAvatar::model()->urlAvatar($user->avatar);
                    }                    
                }
          ?>
          <div class="wrap_infor_user">
          <div class="left profile_function"> 
          	<?php if($user->is_vip): ?>
          		<div class="icon_vip"></div>
          	<?php endif; ?>
          	<a href="<?php echo $imageLarge;?>" class="avatar_thumbnail">
          		<img class="left" src="<?php echo $urlAvatar; ?>" align="absmiddle" width="120" height="120"/> 
            </a>
            <div class="left user_location">
                <p class="nickname"><a href="<?php echo $user->getUserUrl(); ?>"><?php echo $user->username;?></a></p> 
                
                <?php if($online_status){ ?>
                	<!-- if set venue -->
	                <?php if($venues_data){?>
		                <p>
		                	<label class="icon_online"></label> <?php echo Lang::t('settings', 'is online at'); ?>
		                	<?php echo $venues_data['title']; ?>
		      			</p>
		      			<p><?php echo $current_location; ?></p>
	      			<?php }else{ ?>
	      				<!-- don't set venues -->
		                <p>
		                	<label class="icon_online"></label> <?php echo Lang::t('settings', 'is online at'); ?>
		                	<?php echo $current_location; ?>
		      			</p>	      				
	      			<?php } ?>
      			<?php }else{ ?>
      				<p><label class="offline"></label> <?php echo Lang::t('settings', 'is offline', array('{time}'=>date('H:i d-m-Y', !empty($user->lastvisit) ? $user->lastvisit : $user->createtime))); ?></p>
      			<?php }?>
            </div>
            <div class="list_function loadingItem">
            	<?php if(!$is_me): ?>
            	<?php  //$this->widget('frontend.widgets.UserPage.PcReportContentWidget', array('modelClassId' => $community->id, 'modelClassName' => get_class($community)));?>
            	<ul>
                	<!--<li class="message"><a class="showpopup_message_user" title="Message" href="javascript:void(0);"></a></li>-->
                    <li class="chat">
                        <?php if(!empty(CParams::load()->params->anonymousChat)): ?>
                            <a data-id="<?php echo Yii::app()->request->getQuery('alias');?>" class="quick-chat icon_common" title="Chat" href="javascript:void(0);"></a>
                        <?php else: ?>
                            <?php if ($friendship_status == YumFriendship::FRIENDSHIP_ACCEPTED){ ?>
                                <a data-id="<?php echo Yii::app()->request->getQuery('alias');?>" class="is-friend icon_common" title="Chat" href="javascript:void(0);"></a>
                            <?php }else{ ?>
                                <a class="is-not-friend icon_common" title="Chat" href="javascript:void(0);"></a>
                            <?php } ?>
                        <?php endif; ?>
                    </li>
                    <li class="fancy"><a class="showpopup_chondanhhieu coming-soon icon_common" title="Fancy" href="javascript:void(0);"></a></li>
                    
                    <?php if ($friendship_status === false || $friendship_status == YumFriendship::FRIENDSHIP_REJECTED || $friendship_status == YumFriendship::FRIENDSHIP_NONE): ?>
                    <li class="addfriend"><a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.add_friend('<?php echo $friend_id; ?>', '<?php echo Yii::app()->request->getQuery('alias');?>');"></a></li>
                    <?php endif; ?>
                    
                    <?php if ($friendship_status == YumFriendship::FRIENDSHIP_ACCEPTED): ?>
                    <li class="hasfriend"><a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.confirm_unfriend('<?php echo $friend_id; ?>', '<?php echo Yii::app()->request->getQuery('alias');?>');"></a></li>
                    <?php endif; ?>

                    <?php if($friendship_status == YumFriendship::FRIENDSHIP_REQUEST):
	                    if($request_itsmy){ ?>
	                    <li class="pending_friend"><a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.confirm_cancel_request('<?php echo $friend_id; ?>', '<?php echo Yii::app()->request->getQuery('alias');?>');"></a></li>
	                    <?php }else{ ?>
	                    <li class="pending_friend"><a class="icon_common" title="Friend" href="javascript:void(0);" onclick="Friend.confirm_decision_request('<?php echo $friend_id; ?>', '<?php echo Yii::app()->request->getQuery('alias');?>');"></a></li>
	                    <?php } 
                    endif; ?>
                    <?php 
                    $classRate = 'coming-soon';
                    $url = '';
                    if(!empty(CParams::load()->params->rate->enable)){
                    	$classRate = 'rateHim';
                    	$url = $this->usercurrent->createUrl('//rate/list', array('var'=>Util::encryptRandCode('rate-profile')));
                    }
                    ?>
                    <li class="rate"><a title="Rate" href="javascript:void(0);" class="<?php echo $classRate;?> icon_common" data-url="<?php echo $url;?>"></a></li>

                    <?php if(Bookmark::model()->checkBookmark($user->id)) { ?>
                    	<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_bookmark', 'content'=>'')); ?>
                    	<li class="unbookmark">
                    		<a class="icon_common" id="boookmark-icon" onclick="Bookmark.delete_bm(<?php echo $user->id;?>, this);" href="javascript:void(0);" title="<?php echo Lang::t('bookmark', 'Bookmark');?>"></a>
                    	</li>
                    <?php } else{ ?>
                    	<li class="bookmark">
                    		<a class="icon_common" id="boookmark-icon" onclick="Bookmark.add_bm(<?php echo $user->id;?>);" href="javascript:void(0);" title="<?php echo Lang::t('bookmark', 'Bookmark');?>"></a>
                    	</li>
                    <?php } ?>                    
                </ul>
                <?php echo $content; ?>

                <?php endif; ?>
            </div>
          </div>
          <?php $this->widget('frontend.widgets.UserPage.ProfileRating', array('view'=>'profile-rating')); ?>
          <div class="left profile_info">
            <div class="title"> <ins></ins> <?php echo Lang::t('newsfeed', 'Profile')?> </div>
            <div class="content extra-userlocation">
                <ul class="detail">
                  <li><span><?php echo Lang::t('newsfeed', 'From'); ?>: </span> <?php echo $location; ?></li>
                </ul>
                <h3 class="title_profile"><?php echo Lang::t('settings', 'Basic Info'); ?></h3>
                <ul class="detail">
                  <li><?php echo $basic_info_value; ?></li>
                  
                  <?php if(!empty($looking_for_value)): ?>
                  	<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Looking for'); ?>:</span> <?php echo $looking_for_value; ?></li>
                  <?php endif; ?>
                  
                  <?php if(!empty($languages_value)): ?>
                  	<li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</span> <?php echo $languages_value; ?></li>
                  <?php endif; ?>	
                </ul>
                <h3 class="title_profile"><?php echo Lang::t('settings', 'What you see'); ?></h3>
                <ul class="detail">
                  <li><span><?php echo Lang::t('settings', 'Height'); ?>:</span><?php echo (!empty($height)	?	$height	:	ProfileSettingsConst::EMPTY_DATA); ?><i></i></li>
                  <li><span><?php echo Lang::t('settings', 'Weight'); ?>:</span><?php echo (!empty($weight)	?	$weight	:	ProfileSettingsConst::EMPTY_DATA); ?><i></i></li>
                  <li><span><?php echo Lang::t('settings', 'Build'); ?>:</span><?php echo (!empty($build)	?	$build	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                </ul>
                <ul class="detail">
                  <li><span><?php echo Lang::t('settings', 'Body Hair'); ?>:</span><?php echo (!empty($body_hair)	?	$body_hair	:	ProfileSettingsConst::EMPTY_DATA); ?><i></i></li>
                  <li><span><?php echo Lang::t('settings', 'Tattoos'); ?>:</span><?php echo (!empty($tattoo)	?	$tattoo	:	ProfileSettingsConst::EMPTY_DATA); ?><i></i></li>
                  <li><span><?php echo Lang::t('settings', 'Piercings'); ?>:</span><?php echo (!empty($piercing)	?	$piercing	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                </ul>
                <h3 class="title_profile"><?php echo Lang::t('settings', 'Extra'); ?></h3>
                <ul class="detail">
                  <li><span><?php echo Lang::t('settings', 'Occupation'); ?>:</span><?php echo (!empty($occupation_value)	?	$occupation_value	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                  <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Religion'); ?>:</span><?php echo (!empty($religion)	?	$religion	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                  <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Attributes'); ?>:</span><?php echo (!empty($attributes_value)	?	$attributes_value	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                  <?php if(!empty($mannerism)): ?>
                  <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Mannerism'); ?>:</span><?php echo $mannerism; ?></li>
                  <?php endif; ?>
                  <li style="float: left; width: 100%;">
                  	<?php echo $smoke_drink_club; ?>
                  </li>
                  <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'Safe sex'); ?>:</span><?php echo (!empty($safer_sex)	?	$safer_sex	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                  <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</span><?php echo (!empty($public_information)	?	$public_information	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                  <li style="float: left; width: 100%;"><span><?php echo Lang::t('settings', 'I Live With'); ?>:</span><?php echo (!empty($live_with)	?	$live_with	:	ProfileSettingsConst::EMPTY_DATA); ?></li>
                </ul>
            </div>
          </div>
          </div>
          <div class="left profile_status">
            <div class="title"> <ins></ins> <?php echo Lang::t('newsfeed', 'Status');?> </div>
            <div class="feed">
            	<div class="content">
            		<?php 
            		$time = '';
            		if(!empty($activities['data'][0])){
            			$time = $activities['data'][0]->timestamp;
            		}
            		if(!empty(CParams::load()->params->newsfeed->profile_feed) || $current_user->isFriendOf($friend_id) || $is_me){
	            		if ($activities['data']) {?>
	                    <ul class="profile-feeds">
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
								if($data->user_id == Yii::app()->user->id && $data->action == Activity::LOG_CHECK_IN){
									$checkin_is_me	=	true;
								}else{
									$checkin_is_me	=	false;
								}
														
	                			?>
	                			<!-- single news feed item -->
	                			<li class="item status_row_<?php echo $data->id; ?>">
	                				<?php $this->renderPartial("//newsFeed/partial/_view-newsfeed", array(
	                                        'data'=>$data,
	                						'status_text_is_me'	=>	$status_text_is_me,
	                						'status_photo_is_me'	=>	$status_photo_is_me,
	                						'checkin_is_me'	=>	$checkin_is_me
	                                ));?>
	                			</li>
	                			<!-- single news feed item -->
	                			<?php }?>                      
	                    </ul>
	                        <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset" id="newsfeed_offset" />
		                    <input type="hidden" value="<?php echo $limit; ?>" name="newsfeed_offset_first" id="newsfeed_offset_first" /> 
		                    <?php if($show_more): ?>
		            		<div class="pagging">
		                    	<a class="showmore" onclick="NewsFeed.show_more_newsfeed('<?php echo $this->user->alias_name; ?>', 1);" href="javascript:void(0);"><ins></ins></a>
		                    </div>
		                    <?php endif; ?>
	                    <div style="display: none;" class="feedLasted" data-url="<?php echo $user->createUrl('/newsFeed/feedUpdate')?>" data-time="<?php echo $time;?>"></div>
	            		<?php }else{ ?>
	                		<ul>
	                		    <li>
	                		        <p class="no-request-friends"><?php echo Lang::t("newsfeed", 'No news feed')?></p>
	                		    </li>
	                		</ul>
	            		<?php }?>            		
            		<?php }else{ ?>
	                <ul>
	                		    <li>
	                		        <p class="no-request-friends"><?php echo Lang::t("newsfeed", 'Content is not shown because you are not this user\'s friend. Please send your friend request.')?></p>
	                		    </li>
	                </ul>
	                <?php } ?>            		
            	    <!-- news feed list -->

                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="explore left box_margin_fixed">
        <div class="clearfix sticky_column wrap_profiles_right">
        <?php CController::forward('/photo/ListPhotos', false); ?>
        <?php $this->widget('frontend.widgets.UserPage.Banner', array()); ?>
        </div>
      </div>
    </div>
    <!-- InstanceEndEditable -->
    <div id="popupUserCheckIn" class="popup_general" style="display:none;">
	</div> 
                            <?php 
                        Yii::app()->clientScript->registerScript('checkin_newsfeed',"
							$(document).on('click','.popupListCheckIn',function(){
								objCommon.loading();
								$.ajax({
									type: 'POST',
									url: $(this).attr('href'),
									dataType: 'html',
									success: function(res){
										$('#popupUserCheckIn').html('');
										$('#popupUserCheckIn').html(res);
										if($('#popupUserCheckIn .scrollPopup ul li').length > 5)
										    objCommon.sprScroll('#popupUserCheckIn .scrollPopup');
										else
										    $('#popupUserCheckIn .scrollPopup').css('height','auto');
										$('#popupUserCheckIn').pdialog({
											open: function(){
												objCommon.outSiteDialogCommon(this);
												objCommon.no_title(this);
											},
											width: 370
										});
										objCommon.unloading();
									}
								});
								return false;	
							});
							     ",
                        CClientScript::POS_END);
                        ?>