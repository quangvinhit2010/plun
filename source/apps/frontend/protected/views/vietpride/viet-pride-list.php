<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/common.js', CClientScript::POS_BEGIN);

?>
<div class="col-right">
	<div class="members feed-hidden">
	    <h3>
	    	<a href="javascript:void(0);" class="btn-top btn-hide">
	    		<i class="imed imed-arrow-left"></i>
				<span class="text"><?php echo Lang::t('general', 'Hide'); ?></span>
			</a>
	        <p>
	        	<span class="result_total"><?php echo number_format($total_result); ?></span> 
	        	Thành Viên Đã Nhận Quà
	        </p>    
	    </h3>
	    <div class="list clearfix suggest-user-settings">
	            <ul>
	 <!-- search -->      
	 
	 	<?php
	    if($total_result):
	    foreach ($data as $key => $item) :
	    	$member = 	YumUser::model()->findByPk($item->user_id);
	    
	    	$profile_setting = UsrProfileSettings::model()->findByAttributes(array('user_id' => $item->user_id));
	    	$profile_location = UsrProfileLocation::model()->findByAttributes(array('user_id' => $item->user_id));
	    	
	    	
	    	$country_info	=	SysCountry::model()->findByPk($profile_location->current_country_id);
	    	$state_info		=	LocationState::model()->findByPk($profile_location->current_state_id);
	    	
	    	
	    	if(is_numeric($member->avatar)){
				$photo	=	Photo::model()->findByPk($member->avatar);
	    		$avatar	=	$photo->path . '/thumb160x160/'. $photo->name;
	    	}else{
	    		$avatar = VAvatar::model()->urlAvatar($member->avatar);

	    	}
	    	if(empty($avatar)){
	    		$avatar	=	Member::model()->getNoAvatar();
	    	}else{
	    		$avatar = Yii::app()->request->getHostInfo().DS.$avatar;
	    	}
	    		    	
	        $url = Yii::app()->createUrl('//my/view', array('alias' => $member->alias_name));
	        ?>
	        <li>
	            <a target="_blank" href="<?php echo $url; ?>" class="ava"> 
	                <img src="<?php echo $avatar; ?>" alt="<?php echo $member->username; ?>" border=""/> 
	                <span class="ava-bg"></span>
	                <div class="name">
	                    <span class="fname"><?php echo $member->username; ?></span>
	                   <?php 
	                   
	
	                            $location_display	=	'';
	                            $state_name   =   !empty($state_info['name'])  ?  $state_info['name']    :   '' ; 
	                            $profile_setting_const	=	ProfileSettingsConst::getSexRoleLabel();
	                                                        
	                            $country_name   =   !empty($country_info['name'])   ?   $country_info['name']    :   '';
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
	                            	<?php 
	                            		echo $location_display; 
	                      			?>
	                            </span>
	                        </p>
	                        <?php if($profile_setting->birthday_year): ?>
	                            <p class="contact"><?php echo Lang::t('settings', 'Age'); ?>: <?php echo date('Y') - $profile_setting->birthday_year; ?></p>
	                        <?php endif; ?>
	                        <p class="contact"><?php echo isset($profile_setting_const[$profile_setting->sex_role])	?	$profile_setting_const[$profile_setting->sex_role]	:	'Top'; ?></p>
	                    </div>
	
	                </div> 
	            </a>
	            <?php if(in_array($item->user_id, $my_friendlist)): ?>
	            <div class="un_function">
	           		<a href="#" title="Friend" class="mark_friend"></a> 
	            </div>
	            <?php endif; ?>
	        </li>
	      <?php 
	      	endforeach; 
	      	endif;
	      ?>  

	 <!-- end search -->        
	            </ul>
	            <?php if($show_more): ?>
				<div class="more-wrap">
	               <a class="showmore show-more-searchresult" onclick="showmore_searchresult();"><?php echo Lang::t('general', 'Show More'); ?></a>
	            </div>
	            <?php endif; ?>
	    </div>
	    <!-- members list -->
	</div>
	<input type="hidden" name="showmore_next_offset" id="showmore_next_offset" value="<?php echo $offset ?>" />
	<input type="hidden" name="showmore_type" id="showmore_type" value="vietpride" />
</div>