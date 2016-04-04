				<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => $this->user->createUrl('/settings/save'),
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                        ));
                ?>                    
<div class="pad_10">
  <div class="setting_gen setting_basic_info">
    <p class="title left"><a title="Back" href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" class="link_back"></a><span><?php echo Lang::t('settings', 'Extra'); ?></span></p>
    <ul>
      <li><b><?php echo Lang::t('settings', 'Occupation'); ?>:</b></li>
      <li>
        <div class="select_style">
          <?php
	      	$occupation	=	ProfileSettingsConst::getOccupationLabel();
            $occupation_selected	=	isset($occupation[$model->occupation])	?	$occupation[$model->occupation]	:	$occupation[ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY];
	        echo $form->dropDownList($model, 'occupation', $occupation, array('class' => 'virtual_form', 'text' => 'occupation_text'));                                
	       ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city occupation_text"><?php echo $occupation_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Religion'); ?>:</b></li>
      <li>
        <div class="select_style">
          <?php
	        	$religion	=	ProfileSettingsConst::getReligionLabel();
                $religion_selected	=	isset($religion[$model->religion])	?	$religion[$model->religion]	:	$religion[ProfileSettingsConst::RELIGION_ATHEIST];
	            echo $form->dropDownList($model, 'religion', $religion, array('class' => 'virtual_form', 'text' => 'religion_text'));                                
	      ?>        
          <span class="txt_select user_looking_textselect"> <span class="select_city religion_text"><?php echo $religion_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Attributes'); ?>:</b></li>
      <li>
        <div class="findhim-sexrole">
					    <?php 
                    		$my_attributes	=	ProfileSettingsConst::getAttributesLabel(); 
                    		$my_attributes_selected	=	!empty($model->my_attributes)		?	explode(',', $model->my_attributes) 	:	array();
                    		foreach($my_attributes AS $key => $value): 
                        		$checkbox_attributes     =    array(
                        		        'value'     => $key,
                        		        'class'     => 'input-type-3 my_attributes',
                        		        'id'        =>    'my_attributes_' . $key
                        		);
                        		if(in_array($key, $my_attributes_selected)){
                        		    $checked    =    true;
                        		}else{
                        		    $checked    =    false;
                        		}
                    	?>
                    	<div class="squaredCheck">
                    	    <?php echo CHtml::CheckBox('my_attributes[]', $checked, $checkbox_attributes); ?>
                            <label for="my_attributes_<?php echo $key; ?>"></label>
                            <label for="my_attributes_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?>
        </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Mannerism'); ?>:</b></li>
      <li>
        <div class="select_style">
          <?php
	      	$mannerism	=	ProfileSettingsConst::getMannerismLabel();
            $mannerism_selected	=	isset($mannerism[$model->mannerism])	?	$mannerism[$model->mannerism]	:	$mannerism[ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY];
	        echo $form->dropDownList($model, 'mannerism', $mannerism, array('class' => 'virtual_form', 'text' => 'mannerism_text'));                                
	      ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city mannerism_text"><?php echo $mannerism_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Smoke'); ?>:</b></li>
      <li>
        <div class="select_style">
                    	   <?php
	                            	$smoke	=	ProfileSettingsConst::getSmokeLabel();
                            		$smoke_selected	=	isset($smoke[$model->smoke])	?	$smoke[$model->smoke]	:	$smoke[ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'smoke', $smoke, array('class' => 'virtual_form', 'text' => 'smoke_text'));                                
	                        ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city smoke_text"><?php echo $smoke_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Drink'); ?>:</b></li>
      <li>
        <div class="select_style">
                    	   <?php
	                            	$drink	=	ProfileSettingsConst::getDrinkLabel();
                            		$drink_selected	=	isset($drink[$model->drink])	?	$drink[$model->drink]	:	$drink[ProfileSettingsConst::DRINK_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'drink', $drink, array('class' => 'virtual_form', 'text' => 'drink_text'));                                
	                        ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city drink_text"><?php echo $drink_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Safe sex'); ?>:</b></li>
      <li>
        <div class="select_style">
                    	   <?php
	                            	$safe_sex	=	ProfileSettingsConst::getSafeSexLabel();
                            		$safe_sex_selected	=	isset($safe_sex[$model->safer_sex])	?	$safe_sex[$model->safer_sex]	:	$safe_sex[ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'safer_sex', $safe_sex, array('class' => 'virtual_form', 'text' => 'safer_sex_text'));                                
	                        ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city safer_sex_text"><?php echo $safe_sex_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Club'); ?>:</b></li>
      <li>
        <div class="select_style">
                    	   <?php
	                            	$club	=	ProfileSettingsConst::getClubLabel();
                            		$club_selected	=	isset($club[$model->club])	?	$club[$model->club]	:	$club[ProfileSettingsConst::CLUB_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'club', $club, array('class' => 'virtual_form', 'text' => 'club_text'));                                
	                        ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city club_text"><?php echo $club_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</b></li>
      <li>
        <div class="select_style">
                    	   <?php
	                            	$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
                            		$public_information_selected	=	isset($public_information[$model->public_information])	?	$public_information[$model->public_information]	:	$public_information[ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'public_information', $public_information, array('class' => 'virtual_form', 'text' => 'public_information_text'));                                
	                        ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city public_information_text"><?php echo $public_information_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'I Live With'); ?>:</b></li>
      <li>
        <div class="select_style">
                    	   <?php
	                            	$live_with	=	ProfileSettingsConst::getLiveWithLabel();
                            		$live_with_selected	=	isset($live_with[$model->live_with])	?	$live_with[$model->live_with]	:	$live_with[ProfileSettingsConst::LIVE_WITH_NOBODY];
	                           		echo $form->dropDownList($model, 'live_with', $live_with, array('class' => 'virtual_form', 'text' => 'live_with_text'));                                
	                        ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city live_with_text"><?php echo $live_with_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <!-- 
      <li><b><?php echo Lang::t('settings', 'Hobbies'); ?>:</b></li>
      <li>
        <div class="select_style hobbies_tag" onclick="$(this).find('input').focus();">
								<?php if(!empty($model->hobbies)):
									$hobbies	=	json_decode($model->hobbies, true);
								?>
								<?php foreach($hobbies AS $row):  ?>
								<a title="<?php echo $row; ?>" href="javascript:void(0);"><label class="text"><?php echo $row; ?></label><span class="p" onclick="$(this).parent().remove();">X</span></a>							
				<?php endforeach; ?>
				<?php endif; ?>
          		<input type="text" id="txt_hobbies_input" onblur="collect_hobbies();" class="city acfb-input ac_input" autocomplete="off" style="padding : 0px; border: none; display: block; float: left; width: 80px; margin-top: 5px;">
          </div>
      </li>
       -->
       <!-- 
      <li><b><?php echo Lang::t('settings', 'My Types'); ?>:</b></li>
      <li>
        <div class="findhim-sexrole">
                       <?php 
                    		$my_types	=	ProfileSettingsConst::getMyTypesLabel(); 
                    		$my_types_selected	=	!empty($model->my_types)		?	explode(',', $model->my_types) 	:	array();
                    		foreach($my_types AS $key => $value): 
                        		$checkbox_types     =    array(
                        		        'value'     => $key,
                        		        'class'     => 'input-type-3 my_types',
                        		        'id'        =>    'my_types_' . $key
                        		);
                        		if(in_array($key, $my_types_selected)){
                                    $checked    =    true;
                                }else{
                                    $checked    =    false;
                                }
                    	?>
                    	<div class="squaredCheck">
                    	    <?php echo CHtml::CheckBox('my_types[]', $checked, $checkbox_types); ?>
                            <label for="my_types_<?php echo $key; ?>"></label>
                            <label for="my_types_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?> 
        </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Stuff I\'m Into'); ?>:</b></li>
      <li>
        <div class="findhim-sexrole">
                        <?php 
                    		$my_stuff	=	ProfileSettingsConst::getStuffLabel();
                    		$my_stuff_selected	=	!empty($model->into_stuff)		?	explode(',', $model->into_stuff) 	:	array();
                    		foreach($my_stuff AS $key => $value): 
                        		$checkbox_stuff    =    array(
                        		        'value'     => $key,
                        		        'class'     => 'input-type-3 my_stuff',
                        		        'id'        =>    'my_stuff_' . $key
                        		);
                        		if(in_array($key, $my_stuff_selected)){
                                    $checked    =    true;
                                }else{
                                    $checked    =    false;
                                }
                    	?>
                    	<div class="squaredCheck">
                    	    <?php echo CHtml::CheckBox('my_stuff[]', $checked, $checkbox_stuff); ?>
                            <label for="my_stuff_<?php echo $key; ?>"></label>
                            <label for="my_stuff_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?> 
        </div>
      </li>
       -->
      <li class="but_func"><a class="but active" href="javascript:void(0);" title="<?php echo Lang::t('settings', 'Save'); ?>" onClick="save_settings_extra();"><?php echo Lang::t('settings', 'Save'); ?></a> <a href="<?php echo $this->usercurrent->createUrl('settings/index'); ?>" class="but"><?php echo Lang::t('settings', 'Discard'); ?></a></li>
    </ul>
  </div>
</div>
                
                    <?php $this->endWidget(); ?>