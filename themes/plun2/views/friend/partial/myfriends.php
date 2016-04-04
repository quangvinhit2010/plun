<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/friend.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScript('request', "Friend.request();", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('friends',"
            $(document).ready(function(){
                $('.list_explore .list_user ul li').boxResizeImg({
                    wrapContent: true
                });
                $(\".show_num\").click(function(){
				$(\".friend_request_popup\").toggle(\"fast\");
				});
				$(\".friend_request_popup .title a.close\").click(function(){
					$(this).parents(\".friend_request_popup\").hide();
				});
				objCommon.sprScroll(\".friend_request_popup .content\");
			});
			$(window).resize(function(){

			});
		",
		CClientScript::POS_END);
?>            	
            	
<!-- InstanceBeginEditable name="doctitle" -->
<div class="container pheader hasBanner_160 myfriends_page clearfix">
		<div class="explore left min_height_common">
	        <div class="list_explore clearfix">
                <div class="shadow_top"></div>
				<div class="friend_title left">
					<div class="left title">
						<p><?php echo Lang::t('general', 'Friends'); ?></p>
					</div>
					<div class="right friend_request_title">
						<p class="num_friend"><?php echo sprintf(Lang::t('friend', 'You have %d friends'), $myfriend_list['total_friends']); ?></p>
						<?php if($waiting_request_addfriends['total_request']){ ?>
						<p><a href="javascript:void(0);" class="show_num"><?php echo Lang::t('friend', 'Friend Request'); ?> <ins><?php echo $waiting_request_addfriends['total_request']; ?></ins></a></p>
						<div class="friend_request_popup" style="display: none;">
							<a href="javascript:void(0);" class="top_arrow"></a>
							<div class="title">
								<?php echo Lang::t('friend', 'Friend Request'); ?> 
								<a class="close" href="javascript:void(0);" onclick="Friend.togglePopup();"></a>
							</div>

								<?php if($waiting_request_addfriends['total_request'] >= $popup_limit_display){ ?>
									<div class="content friend_height">
								<?php }else{ ?>
									<div class="content">
								<?php } ?>
										<ul>
											<?php foreach ($waiting_request_addfriends['dbrow'] AS $item): ?>
											<li>
												<div class="left info">
													<a target="_blank" class="left" href="<?php echo $item->inviter->getUserUrl();?>"><img width="35px" height="35px" src="<?php echo $item->inviter->getAvatar(false); ?>" onerror="$(this).attr('src','/public/images/no-user.jpg');" />
													<div class="name">
														<p class="nick"><a target="_blank" href="<?php echo $item->inviter->getUserUrl();?>"><?php echo $item->inviter->getDisplayName(); ?></a></p>
														<p class="time"><?php echo Util::getElapsedTime($item->requesttime) ?></p>
													</div>
												</div>
												<div class="right addfriendrequest-result-<?php echo $item->inviter_id; ?>">
													<a href="javascript:void(0);" class="accept-<?php echo $item->inviter_id; ?> active" onclick="Friend.accept_friend('<?php echo $item->inviter_id; ?>', '<?php echo $item->inviter->alias_name; ?>');"><?php echo Lang::t('general', 'Accept'); ?></a>
													<a href="javascript:void(0);" class="decline-<?php echo $item->inviter_id; ?>" onclick="Friend.decline_friend('<?php echo $item->inviter_id; ?>');"><?php echo Lang::t('general', 'Decline'); ?></a>
												</div>
											</li>
											<?php endforeach;?>
										</ul>
										<div class="pagging">
											<a href="<?php echo Yii::app()->createUrl('//friend/request', array('alias'=>$this->usercurrent->alias_name))?>"><?php echo Lang::t('general', 'Show More'); ?></a>
										</div>
									</div>
					</div>
						<?php }else{ ?>
							<p><a href="<?php echo Yii::app()->createUrl('//friend/request', array('alias'=>$this->usercurrent->alias_name))?>" class="show_num"><?php echo Lang::t('friend', 'Friend Request'); ?></a></p>
	                     <?php } ?>
					</div>
	            	</div>
	            <div class="clear"></div>
	            <div class="list_user left wrap_scale_box">
	                <?php if($myfriend_list['total_friends']){ ?>
	                    <ul>
	                        <?php
	                        foreach($myfriend_list['dbrow'] AS $item):
	                            if(Yii::app()->user->id == $item->invited->id ) {
	                                $item->invited = $item->inviter;
	                            }
	                            if(is_object($item->invited->profile_settings)){
	                                $show_more_info    =   true;
	                                $sex_role = isset($sex_roles_title[$item->invited->profile_settings->sex_role]) ? $sex_roles_title[$item->invited->profile_settings->sex_role] : '';
	                            }else{
	                                $show_more_info    =   false;
	                                $sex_role  =   '';
	                            }
	                            $location_display	=	'';
	                            $state_name   =   !empty($state_info[$item->invited->profile_settings->state_id]['name'])  ?  $state_info[$item->invited->profile_settings->state_id]['name']    :   '' ;
	                            $country_name   =   !empty($country_info[$item->invited->profile_settings->country_id]['name'])   ?   $country_info[$item->invited->profile_settings->country_id]['name']    :   '';
	                            if(!empty($country_name)){
	                                if(!empty($state_name)){
	                                    $location_display	=	"$state_name, $country_name";
	                                }else{
	                                    $location_display	=	$country_name;
	                                }
	                            }
	                            $sexualityLabel	=	ProfileSettingsConst::getSexualityLabel();
	
	                            ?>
	                            <li class="friendlist-item-<?php echo $item->invited->id; ?>">
	                                <div class="wrap-img">
	                                    <a href="<?php echo $item->invited->getUserUrl();?>">
	                                        <?php echo $item->invited->getAvatar(true); ?>
	                                    </a>
	                                    <?php if($show_more_info):
	                                        $birthday_year   =   isset($item->invited->profile_settings->birthday_year)  ?  $item->invited->profile_settings->birthday_year    :   false ;
	                                        ?>
	                                        <div class="info">
	                                            <a href="<?php echo $item->invited->getUserUrl();?>">
	
	                                                <?php if($birthday_year): ?>
	                                                    <p><?php echo Lang::t('search', 'Age'); ?>: <?php echo date('Y') - $birthday_year; ?></p>
	                                                <?php endif; ?>
	
	                                                <?php if(isset($sexualityLabel[$item->invited->profile_settings->sexuality])): ?>
	                                                    <p><?php echo Lang::t('settings', 'Sexuality'); ?>: <?php echo $sexualityLabel[$item->invited->profile_settings->sexuality]; ?></p>
	                                                <?php endif; ?>
	
	                                                <?php if($sex_role): ?>
	                                                    <p><?php echo Lang::t('settings', 'Role'); ?>: <?php echo $sex_role; ?></p>
	                                                <?php endif; ?>
	
	                                                <div class="map"><ins></ins> <?php echo $location_display;?></div>
	                                            </a>
	                                        </div>
	                                    <?php endif; ?>
                                        <div class="icons_status">
                                            <div class="icon_each">
                                            	<a title="unfriend" href="javascript:void(0);" class="unfriend" onclick="Friend.confirm_unfriend_infriendlist('<?php echo $item->invited->id; ?>', '<?php echo $item->invited->alias_name; ?>');"></a>
                                            </div>
                                        </div>

	                                </div>
                                    <div class="status_user_onoff">
                                        <p>
                                            <?php if(isset($online_data['online'][$item->invited->id])){ ?>
                                                <label class="online"></label>
                                            <?php }else{ ?>
                                                <label class="offline"></label>
                                            <?php } ?>
                                            <a href="<?php echo $item->invited->getUserUrl();?>"><?php echo $item->invited->getDisplayName(); ?></a></p>
                                        <span>|</span>
                                        <?php if(!empty(CParams::load()->params->anonymousChat)): ?>
                                            <a href="javascript:void(0);" class="icon_common icon_msg_user quick-chat" data-id="<?php echo $item->invited->username; ?>"></a>
                                        <?php endif;?>
                                    </div>

	                            </li>
	                        <?php endforeach; ?>
	                    </ul>
	                    <input type="hidden" name="showmore_friendlist_offset" id="showmore_friendlist_offset" value="<?php echo $limit_friendlist; ?>" />
	                    <input id="showmore_friendlist_offset_first" type="hidden" name="showmore_friendlist_offset" value="<?php echo $limit_friendlist; ?>">
						<?php if($show_more_myfriendlist): ?>
	                    <div class="pagging">
	                        <a href="javascript:void(0);" class="showmore showmore-friendlist" onclick="Friend.showmore_friendlist();"><ins></ins></a>
	                    </div>
	                    <?php endif;?>
	                <?php }else{ ?>
	                    <div class="noFriends">
	                    	<span class="icon_common"></span>
	                        <p><?php echo Lang::t('friend', 'No friends to show'); ?></p>
	                    </div>
	                <?php } ?>
	            </div>
	        </div>
	        <?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
        </div>
</div>
<!-- InstanceEndEditable -->    
<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_status', 'content'=>'')); ?>        	