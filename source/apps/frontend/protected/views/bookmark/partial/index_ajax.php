			<?php foreach ($bookmarks as $bookmark ) {?>
				<li class="bookmark_item" id="<?php echo $bookmark->target_id;?>">
					<a href="<?php echo $bookmark->user->getUserUrl();?>" class="ava">
						<?php echo $bookmark->user->getAvatar(true); ?>
						<span class="ava-bg"></span>
						<div class="name">
							<span class="fname"><?php echo $bookmark->user->getDisplayName(); ?></span>
							<?php 
							if(is_object($bookmark->user->profile_settings)){
								$show_more_info    =   true;
								$sex_role = isset($sex_roles_title[$bookmark->user->profile_settings->sex_role]) ? $sex_roles_title[$bookmark->user->profile_settings->sex_role] : '';
							}else{
								$show_more_info    =   false;
								$sex_role  =   '';
							}
							if($show_more_info){
							$country_name   =   isset($country_info[$bookmark->user->profile_settings->country_id]['name'])   ?   ", {$country_info[$bookmark->user->profile_settings->country_id]['name']}"    :   '';
							$city_name   =   isset($city_info[$bookmark->user->profile_settings->city_id]['code'])  ?  $city_info[$bookmark->user->profile_settings->city_id]['code']    :   '' ;
							$birthday_year   =   isset($bookmark->user->profile_settings->birthday_year)  ?   $bookmark->user->profile_settings->birthday_year    :   false ;
							?>
							<div class="more">
								<p class="location">
									<span class="text"><?php echo $city_name; ?><?php echo $country_name; ?></span>
								</p>
								<?php if($birthday_year): ?>
                                    <p class="contact"><?php echo Lang::t('bookmark', 'Age');?>: <?php echo date('Y') - $birthday_year; ?></p>
                                <?php endif; ?>
								 <p class="intro"><?php echo $sex_role; ?></p>   
							</div>
							<?php } ?>
						</div>
					</a>
					<div class="un_function">
                        <a href="javascript:void(0);" onclick="Bookmark.delete_bm(<?php echo $bookmark->target_id;?>);" title="<?php echo Lang::t('bookmark', 'Unbookmark');?>" class="unbookmark"></a>
                    </div>
				</li>
			<?php } ?>
			<div style="display: none;" id="next_page" page="<?php echo $next_page;?>"></div>
