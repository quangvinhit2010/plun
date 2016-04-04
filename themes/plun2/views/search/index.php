<?php 
$cs = Yii::app()->clientScript;
/*$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/search.js', CClientScript::POS_BEGIN);*/
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
    <div class="shadow_top"></div>
    <?php 
    $param = CParams::load();
	if(!empty($param->params->systems_alert->enable)){
    ?>
     <div class="topListExploreBanner">
        <div class="con_Banner">
            <div class="innerBoxBanner">
                <div class="n_y">
                   <!--<ins class="icon_common icon_alert_fun"></ins>-->
                     <div class="ticker1 modern-ticker mt-round mt-scroll">
                      <div class="mt-body">
                            <div class="slide_new_fun mt-news">
                                <ul id="">
                                   <li><a href="#">Tính năng Check in Vừa Update, xem ngay để trải nghiệm 1 .</a> </li>
                                    <li><a href="#">Tính năng Check in Vừa Update, xem ngay để trải nghiệm 2.</a> </li>
                                   <li><a href="#">Tính năng Check in Vừa Update, xem ngay để trải nghiệm 3.</a> </li>
                               </ul>
                            </div>
                            <!--<div class="mt-controls"><div class="mt-prev"></div><div class="mt-next"></div></div>-->
                      </div>
                    </div>

                </div>
                <a class="btnCloseAdsTop" href="#"><ins class="icon_common"></ins></a>
            </div>
         </div>
    </div>
    <?php 
	}
    ?>
    <div class="online_num left">
			<label>
				<?php echo number_format($total_result); ?>
				<?php echo Lang::t('general', 'members in &1 &2 &3', array('&1' => '<a href="javascript:void(0);"><ins>', '&2' => implode(', ', $current_area), '&3' => '</ins></a>')); ?>.
			</label>			
			<a class="txtBlock" href="javascript:void(0);"><ins class="icon_location"></ins><?php echo Lang::t('search', 'Find your criteria')?></a>
	</div> 
    <?php $this->widget('frontend.widgets.popup.Findhim', array()); ?>
    
    <div class="clear"></div>

	    <div class="list_user left wrap_scale_box suggest-user-settings">
        <ul>
	 	<!-- search -->      
	 
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
	            	<a target="_blank" href="<?php echo $url; ?>"><img class="lazy" data-original="<?php echo $avatar; ?>" alt="<?php echo $item['username']; ?>" border="0" align="absmiddle" onerror="$(this).attr('src','/public/images/no-user.jpg');" /></a>
	                <div class="info">
                    	<a href="<?php echo $url; ?>" target="_blank">
                        	<p><?php echo Lang::t('settings', 'Age'); ?>: <?php echo date('Y') - $item['birthday_year']; ?></p>
                        	<?php if(isset($sexualityLabel[$item['sexuality']])): ?>
                            	<p><?php echo Lang::t('settings', 'Sexuality'); ?>: <?php echo $sexualityLabel[$item['sexuality']]; ?></p>
                            <?php endif; ?>
                            <p><?php echo Lang::t('settings', 'Role'); ?>: <?php echo $item['sex_role_name']; ?></p>
                            <div class="map"><ins></ins> <?php echo $location_display; ?></div>
                        </a>	             
	                </div>
	            </div>
                <div class="status_user_onoff">
                    <?php if(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){ ?>
                        <p><label class="online"></label><a href="<?php echo $url; ?>" target="_blank"><?php echo $item['username']; ?></a></p>
                    <?php }else{ ?>
                        <p><label class="offline"></label><a href="<?php echo $url; ?>" target="_blank"><?php echo $item['username']; ?></a></p>
                    <?php } ?>
                    <span>|</span>
                    <?php if(!empty(CParams::load()->params->anonymousChat)): ?>
                        <a href="javascript:void(0);" class="icon_common icon_msg_user quick-chat" data-id="<?php echo $item['username']; ?>"></a>
                    <?php endif; ?>
                </div>
                <div class="icons_status">
                    <div class="icon_each">
                    	<?php if(in_array($item['user_id'], $my_friendlist)): ?>
                            <div class="has_friend"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                	if(isset($item['is_vip'])):
                		if($item['is_vip']):
                ?>
                			<div class="icon_vip"></div>
                		<?php endif; ?>
                 <?php endif; ?>
                	
	       </li>         
	      <?php 
	      	endforeach; 
	      	endif;
	      ?>  
	      
	 	  <!-- end search -->        
	      </ul>
	            <?php if($show_more): ?>
	            <div class="pagging">
                	<a href="javascript:void(0);" onclick="showmore_searchresult();"><ins></ins></a>
                </div>
	            <?php endif; ?>
	    </div>

    <!-- members list -->
</div>
<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="<?php echo $offset ?>" />
<input type="hidden" name="showmore_type" id="showmore_type" value="usersetting" />