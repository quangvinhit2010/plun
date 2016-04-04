<?php 
	$i = 0;
	foreach($contacts AS $row): 
        $user = Member::model()->findByPk($row['user_id']);
        $url = Yii::app()->createUrl('//my/view', array('alias' => $row['alias_name']));
        $country_name   =   isset($country_info[$user->profile_settings->country_id]['name'])   ?   ", {$country_info[$user->profile_settings->country_id]['name']}"    :   '';
        $city_name   =   isset($city_info[$user->profile_settings->city_id]['name'])  ?  $city_info[$user->profile_settings->city_id]['name']    :   '' ;
                            			
		if($i >= $offset && $i < $offset + $limit && $i < $total){
	?>
                            	<li>
                                	<div class="w220 left">
                                    	<a class="left" target="_blank" href="<?php echo $url; ?>"><img width="40px" height="40px" src="<?php echo $user->getAvatar(); ?>" align="absmiddle"></a>
                                        <p class="name"><?php echo $user->getDisplayName(); ?></p>
                                        <p><?php echo $city_name; ?><?php echo $country_name; ?></p>
                                    </div> 
                                	<div class="w60 right"><a href="<?php echo $url; ?>" target="_blank"><?php echo Lang::t('invitation', 'Add'); ?></a></div>
                                </li>
     <?php 
		}
		$i++;
		endforeach; 
?>