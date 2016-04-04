<?php
foreach ($myfriend_list['dbrow'] AS $item):
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
    					<li class="friendlist-item-<?php echo $item->invited->id; ?> item_friendlist_showmore" style="display: none;">
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