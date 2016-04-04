<?php 
$get = Yii::app()->cache->get('viewProfiles_'.Yii::app()->user->id);
$arrProfiles = array();
$total = 0;
if(!empty($get)){
    $arrProfiles = json_decode($get);
    $total = count($arrProfiles);
}
//build current area
$current_area	=	array();
if($current_district_name){
	array_push($current_area, $current_district_name);
}
if($current_city_name){
	array_push($current_area, $current_city_name);
}
if($current_state_name){
	array_push($current_area, $current_state_name);
}
if($current_country_name){
	array_push($current_area, $current_country_name);
}
?>
<div class="list_explore">
	<div class="online_num left">
		<label>
			<span class="result_total"><?php echo number_format($total_result); ?></span> 
			<?php echo Lang::t('general', 'members in &1 &2 &3', array('&1' => '<a href="javascript:void(0);"><ins>', '&2' => implode(', ', $current_area), '&3' => '</ins></a>')); ?>		
		</label> 
		<a href="javascript:void(0);"><ins class="icon_location"></ins></a>
	</div> 
    <?php $this->widget('frontend.widgets.popup.Findhim', array()); ?>
    <div class="clear"></div>
    <div class="left">
	    <div class="list_user left wrap_scale_box suggest-user-settings">
	    <ul>
	 	<?php
	    if($total_result):
	    $params = CParams::load ();
	    $img_webroot_url	=	$params->params->img_webroot_url;
	    foreach ($data as $key => $item) :
	    	$item	=	$item['_source'];
	    	
	        $url = Yii::app()->createUrl('//my/view', array('alias' => $item['alias_name']));
	        if($item['have_avatar']){
	        	$avatar	=	"http://{$img_webroot_url}{$item['avatar']}";
	        }else{
	        	$avatar	=	$item['avatar'];
	        }
	        $location_display	=	'';
	        $state_name   =   !empty($item['state_name'])  ?  $item['state_name']    :   '' ; 
	        $country_name   =   !empty($item['country_name'])   ?   $item['country_name']    :   '';
	        if(!empty($country_name)){
	        	if(!empty($state_name)){
	            	$location_display	=	"$state_name, $country_name";
	            }else{
	            	$location_display	=	$country_name;
	            }
	        }
	        $sexualityLabel	=	ProfileSettingsConst::getSexualityLabel();
	        ?>
	        <li>
	        	<div class="wrap-img">
	            	<a target="_blank" href="<?php echo $url; ?>"><img src="<?php echo $avatar; ?>" alt="<?php echo $item['username']; ?>" border="0" align="absmiddle" onerror="$(this).attr('src','/public/images/no-user.jpg');" /></a> 
	                <div class="info">
                    	<a href="<?php echo $url; ?>">
                        	<p><?php echo Lang::t('settings', 'Age'); ?>: <?php echo date('Y') - $item['birthday_year']; ?></p>
                        	<?php if(isset($sexualityLabel[$item['sexuality']])): ?>
                            	<p><?php echo Lang::t('settings', 'Sexuality'); ?>: <?php echo $sexualityLabel[$item['sexuality']]; ?></p>
                            <?php endif; ?>
                            <p><?php echo Lang::t('settings', 'Role'); ?>: <?php echo $item['sex_role_name']; ?></p>
                            <div class="map"><ins></ins> <?php echo $location_display; ?></div>
                        </a>	                
	                </div>
	            </div>    
	            <?php if(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){ ?>
	            	<p><label class="online"></label><a href="<?php echo $url; ?>"><?php echo $item['username']; ?></a></p>
	            <?php }else{ ?>
	            	<p><label class="offline"></label><a href="<?php echo $url; ?>""><?php echo $item['username']; ?></a></p>
	            <?php } ?>
	       </li>         
	      <?php 
	      	endforeach; 
	      	endif;
	      ?>  
	      </ul>
	      <?php if($show_more): ?>
	      	<div class="pagging">
            	<a href="javascript:void(0);" onclick="showmore_searchresult();"><ins></ins></a>
            </div>
	      <?php endif; ?>
	    </div>
    </div>
    <!-- members list -->
</div>
<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="<?php echo $offset ?>" />
<input type="hidden" name="showmore_type" id="showmore_type" value="usersetting" />