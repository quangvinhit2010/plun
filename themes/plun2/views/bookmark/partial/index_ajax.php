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
		<a href="<?php echo $bookmark->user->getUserUrl();?>" class="ava"><?php echo $bookmark->user->getAvatar(true); ?></a>
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
        <p><?php if($online_status):?><label class="online"></label><?php else :?><label class="offline"></label><?php endif;?> <a href="#"><?php echo $bookmark->user->getDisplayName(); ?></a></p>
        <span>|</span>
        <?php if(!empty(CParams::load()->params->anonymousChat)): ?>
            <a href="javascript:void(0);" class="icon_common icon_msg_user quick-chat" data-id="<?php echo $bookmark->user->username; ?>"></a>
        <?php endif;?>
    </div>

  </li>
  <?php } ?>
<div style="display: none;" id="next_page" page="<?php echo $next_page;?>"></div>