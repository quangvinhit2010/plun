<?php 
$userTop = UserTop::model()->getUsers();
if(!empty($userTop)){
    $city_in_cache = new CityonCache();
    $country_in_cache   =   new CountryonCache();
    $state_in_cache	=	new StateonCache();
    $city_info = $city_in_cache->getListCity();
    $country_info = $country_in_cache->getListCountry();
    $state_info = $state_in_cache->getListState();
?>
<ul>
    <?php foreach ($userTop as $item){?>
    <?php 
    if(!empty($item->user)){
        $profileLocation = $item->user->profile_location;
        
        $location_display	=	'';
        $state_name   =   !empty($state_info[$profileLocation->current_state_id]['name'])  ?  $state_info[$profileLocation->current_state_id]['name']    :   '' ;
        $country_name   =   !empty($country_info[$profileLocation->current_country_id]['name'])   ?   $country_info[$profileLocation->current_country_id]['name']    :   '';
        if(!empty($country_name)){
            if(!empty($state_name)){
                $location_display	=	"$state_name, $country_name";
            }else{
                $location_display	=	$country_name;
            }
        }
        $sex_roles_title	=	ProfileSettingsConst::getSexRoleLabel();
        if(isset($item->user->profile_settings) && is_object($item->user->profile_settings)){
            $sex_role = isset($sex_roles_title[$item->user->profile_settings->sex_role]) ? $sex_roles_title[$item->user->profile_settings->sex_role] : '';
        }else{
            $sex_role  =   '';
        }
        
        $birthday_year   =   isset($item->user->profile_settings->birthday_year)  ?  $item->user->profile_settings->birthday_year    :   false ;
        ?>
        <li>
            <a href="<?php echo $item->user->getUserUrl();?>" title="" class="ava">
                <img src="<?php echo $item->user->getAvatar();?>" alt="" border="">
                <span class="ava-bg"></span>
                <div class="name">
                    <span class="fname"><?php echo $item->user->getDisplayName();?></span>
                    <div class="more">
                        <p class="location">
                            <i class="imed imed-loc"></i>
                            <span class="text"><?php echo $location_display;?></span>
                        </p>
                        <?php if($birthday_year): ?>
                            <p class="intro"><?php echo Lang::t('search', 'Age'); ?>: <?php echo date('Y') - $birthday_year; ?></p>
                        <?php endif; ?>
                        <p class="contact"><?php echo $sex_role; ?></p>
                    </div>
                </div>
            </a>
        </li>
    <?php }?>
    <?php }?>
</ul>
<?php 
}
?>
