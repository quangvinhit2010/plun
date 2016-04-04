<?php
    if($total):
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
                        <?php if(in_array($item['user_id'], $my_friendlist)): ?>
                        	<a class="icon_common icon_user_added" href="javascript:void(0);"></a>   
                        <?php endif; ?> 	                
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
	       </li>
      <?php 
      	endforeach; 
      	endif;
      ?>