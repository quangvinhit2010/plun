<?php 
$sexualityLst	=	ProfileSettingsConst::getSexualityLabel();
$roles	=	ProfileSettingsConst::getSexRoleLabel();
?>
<div class="container pheader min_max_1024 clearfix hasBanner_160 bookmark_page">
	<div class="explore left">
		<div class="list_explore">
            <div class="shadow_top"></div>
			<div class="online_num">
				<div class="left title"><?php echo Lang::t('bookmark', 'Bookmark')?></div>
				<div class="right"><p><?php echo Lang::t('bookmark', 'You have <b>{number}</b> bookmarked profiles', array('{number}'=>$pages->getItemCount()));?></p></div>
			</div> 
																				   
			<div class="left bookmark_list">
				<?php if(isset($bookmarks)) { ?>
				<div class="list_user left wrap_scale_box">
					<ul id="bookmarks" page="<?php echo $next_page;?>">
						<?php foreach ($bookmarks as $bookmark ) {?>
	                	<?php
	                	$show_more_info    =   false;
	                	$sex_role  =   '';
	                	$sexuality  =   '';
						if(!empty($bookmark->user->profile_settings)){
							$show_more_info    =   true;
							$sex_role = !empty($roles[$bookmark->user->profile_settings->sex_role]) ? $roles[$bookmark->user->profile_settings->sex_role] : '';
							$sexuality = !empty($sexualityLst[$bookmark->user->profile_settings->sexuality]) ? $sexualityLst[$bookmark->user->profile_settings->sexuality] : '';
						}		
						
						$elasticsearch	=	new Elasticsearch();
						$online_data	=	$elasticsearch->checkOnlineStatus(array($bookmark->user->id));
						$online_status	=	isset($online_data['online'][$bookmark->user->id])	?	true	:	false;
						
						?>
						<li class="bookmark_item" id="<?php echo $bookmark->target_id;?>">
							<div class="wrap-img">
								<a href="javascript:void(0);"><img src="<?php echo $bookmark->user->getAvatar(); ?>" align="absmiddle" /></a>
								<?php 
								if($show_more_info){
									$country_name   =   isset($country_info[$bookmark->user->profile_settings->country_id]['name'])   ?   ", {$country_info[$bookmark->user->profile_settings->country_id]['name']}"    :   '';
									$city_name   =   isset($city_info[$bookmark->user->profile_settings->city_id]['code'])  ?  $city_info[$bookmark->user->profile_settings->city_id]['code']    :   '' ;
									$birthday_year   =   isset($bookmark->user->profile_settings->birthday_year)  ?   $bookmark->user->profile_settings->birthday_year    :   false ;
								?>                        		
								<div class="info">
									<a href="<?php echo $bookmark->user->getUserUrl();?>">
										<?php if($birthday_year): ?>                                  		
	                                    <p><?php echo Lang::t('bookmark', 'Age');?>: <?php echo date('Y') - $birthday_year; ?></p>
										<?php endif; ?>                                      
	                                    <p><?php echo Lang::t('settings', 'Sexuality')?>: <?php echo $sexuality;?></p>
	                                    <p><?php echo Lang::t('settings', 'Role')?>: <?php echo $sex_role; ?></p>
	                                	<div class="map"><ins></ins> <?php echo $city_name; ?><?php echo $country_name; ?></div>
	                            	</a>                                            
	                            </div>
	                            <?php }?>
                                <div class="icons_status">
                                    <div class="icon_each">
                                    	<a class="un_bookmark" href="javascript:void(0);" onclick="Bookmark.delete_bm(<?php echo $bookmark->target_id;?>);" title="unbookmark"></a>
                                    </div>
                                </div>

							</div>
                            <div class="status_user_onoff">
                                <p><?php if($online_status):?><label class="online"></label><?php else :?><label class="offline"></label><?php endif;?> <a href="javascript:void(0);"><?php echo $bookmark->user->getDisplayName(); ?></a></p>
                                <span>|</span>
                                <?php if(!empty(CParams::load()->params->anonymousChat)): ?>
                                    <a href="javascript:void(0);" class="icon_common icon_msg_user quick-chat" data-id="<?php echo $bookmark->user->username; ?>"></a>
                                <?php endif;?>
                            </div>

						</li>
						<?php } ?>
					</ul>
					<?php if($pages->pageCount > 1) {?>
						<div class="pagging more-wrap">
							<a class="showmore" onclick="Bookmark.show_more();" href="javascript:void(0);"><ins></ins></a>
						</div>
					<?php } ?>
				</div>
				<?php }?>
			</div>
		</div>
		<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_160)); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
        $('.list_explore .list_user ul li').boxResizeImg({
            wrapContent: true
        });
	});
</script>

<?php $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'remove_bookmark', 'content'=>'')); ?>