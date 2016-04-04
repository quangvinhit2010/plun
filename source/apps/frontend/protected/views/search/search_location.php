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
        }        //$avatar	=	$item['avatar'];
        ?>
        <li>
            <a target="_blank" href="<?php echo $url; ?>" class="ava"> 
                <img src="<?php echo $avatar; ?>" alt="<?php echo $item['username']; ?>" border="0"/> 
                <span class="ava-bg"></span>
                <div class="name">
                	<?php if(time() - $item['last_activity'] <= Yii::app()->params->Elastic['update_activity_time']){ ?>
                		<label class="is_online"></label>
                	<?php }else{ ?>
                	<label class="is_offline"></label>
                	<?php } ?>
                    <span class="fname"><?php echo $item['username']; ?></span>
                   <?php 
                   

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
                            
							
                     ?>                    
                    <div class="more">
                        <p class="location">
                        	<!--  
                            <i class="imed imed-loc"></i>
                            -->
                            <span class="text">
                            	<?php echo $location_display; ?>
                            </span>
                        </p>
                        <?php if($item['birthday_year']): ?>
                            <p class="contact"><?php echo Lang::t('settings', 'Age'); ?>: <?php echo date('Y') - $item['birthday_year']; ?></p>
                        <?php endif; ?>
                        <p class="contact"><?php echo $item['sex_role_name']; ?></p>
                    </div>

                </div> 
            </a>
            <?php if(in_array($item['user_id'], $my_friendlist)): ?>
            <div class="un_function">
           		<a href="#" title="Friend" class="mark_friend"></a> 
            </div>
            <?php endif; ?>
        </li>
      <?php 
      	endforeach; 
      	endif;
      ?>  
      <?php if($fullscreen): ?>
      <script type="text/javascript">
      NewsFeed.MyNewsFeed_init();
	  </script>
      <?php endif; ?>