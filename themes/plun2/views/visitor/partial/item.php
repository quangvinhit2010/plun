<?php if(!empty($vstUserViewUser)):?>
<?php for ($i=0;$i<$limit;$i++):?>
	<?php 
		if(!empty($vstUserViewUser[$i])):
		$userView = $vstUserViewUser[$i];	
		$Elasticsearch	=	new Elasticsearch();
		$e_user			=	(object)$Elasticsearch->load($userView->view_id);	
		$roles	=	ProfileSettingsConst::getSexRoleLabel();
		$sexuality	=	ProfileSettingsConst::getSexualityLabel();
		
		$city_in_cache = new CityonCache();
		$country_in_cache   =   new CountryonCache();
		$state_in_cache	=	new StateonCache();
		$district_in_cache	=	new DistrictonCache();
		$location	=	array();
		if(!empty($e_user->current_district_id)){
			$district =	$district_in_cache->getDistrictInfo($e_user->current_district_id);
			$location[]	=	$district['name'];
		}
		if(!empty($e_user->current_city_id)){
			$city =	$city_in_cache->getCityInfo($e_user->current_city_id);
			$location[]	=	$city['name'];
		}
		if(!empty($e_user->current_state_id)){
			$state =	$state_in_cache->getStateInfo($e_user->current_state_id);
			$location[]	=	$state['name'];
		}
		if(!empty($e_user->current_country_id)){
			$country =	$country_in_cache->getCountryInfo($e_user->current_country_id);
			$location[]	=	$country['name'];
		}
		//general location
		if(sizeof($location)){
			$location	=	implode(', ', $location);
		}else{
			$location	=	ProfileSettingsConst::EMPTY_DATA;
		}
		
		$url = Yii::app()->createUrl('//my/view', array('alias' => $e_user->username));
		$params = CParams::load ();
		$avatar = "http://{$params->params->img_webroot_url}{$e_user->avatar}";
		
		$_lbl_sex_role = '';
		if(!empty($roles[$e_user->sex_role])){
			$_lbl_sex_role = $roles[$e_user->sex_role];
		}
		$_lbl_sexuality = '';
		if(!empty($sexuality[$e_user->sexuality])){
			$_lbl_sexuality = $sexuality[$e_user->sexuality];
		}
	?>
	<li>
		<a href="<?php echo $url;?>" class="wrap_img left"><img src="<?php echo $avatar;?>" width="70"/></a>
		<div class="left item_u_vis">
			<div class="c_item_box right">
				<div class="_tempH"></div>
				<div class="c_item">
					<a data-id="<?php echo $e_user->username;?>" class="quick-chat" href="javascript:void(0);"><ins class="icon_common"></ins>CHAT</a>
				</div>
			</div>
			<a class="u_n" href="<?php echo $url;?>">
				<?php 
				if((time() - $e_user->last_activity) <= $params->params->Elastic->update_activity_time)
					echo '<label><span class="online"></span></label>';
				else
					echo '';				
				?>				
				<?php echo $e_user->username;?>
			</a>
			<p><?php echo Lang::t('settings', 'Age');?> : <?php echo $e_user->age;?>  I  <?php echo Lang::t('settings', 'Sexuality');?>: <?php echo $_lbl_sexuality;?>  I   <?php echo Lang::t('settings', 'Role');?>: <?php echo $_lbl_sex_role;?>  I  <?php echo Lang::t('newsfeed', 'From'); ?>: <?php echo $location;?></p>
			<p><?php echo Lang::t('visitor', 'Visit {time}.', array('{time}'=>Util::getElapsedTime($userView->timestamp)))?></p>
		</div>
	</li>
	<?php endif;?>
<?php endfor;?>
<?php endif;?>