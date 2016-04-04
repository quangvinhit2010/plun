          <div class="listSuggestUserWidget friend_search_form right<?php echo $class_css	?	" {$class_css}" : ''; ?>">
            <div class="left">
            	<h3><?php echo Lang::t('friend', 'Suggest friend'); ?></h3>
            	<div class="content search_result">
              <ul>
              	<?php 
	              	$params = CParams::load ();
	              	$img_webroot_url	=	$params->params->img_webroot_url;
              		foreach($suggest_data AS $item): 
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
              	?>
                <li>
                  <div class="left avatar"><a target="_blank" href="<?php echo $url; ?>"><img src="<?php echo $avatar; ?>" align="absmiddle" width="50px" height="50px"  border="0" align="absmiddle" onerror="$(this).attr('src','/public/images/no-user.jpg');"/></a></div>
                  <div class="left info_friend">
                    <p class="name"><a target="_blank" href="<?php echo $url; ?>"><?php echo $item['username']; ?></a></p>
                    <p class="type_position"><?php echo $item['sex_role_name']; ?> | <?php echo $location_display; ?></p>
                    <p class="but_add_friend"><a target="_blank" href="<?php echo $url; ?>"><?php echo Lang::t('friend', 'Add'); ?></a></p>
                  </div>
                </li>   
                <?php 
                	endforeach;
                ?>             
              </ul>

            </div>
            </div>
          </div>