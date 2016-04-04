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
                        	<p class="title left"><a title="Back" href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" class="link_back"></a><span><?php echo Lang::t('settings', 'Basic Info'); ?></span></p>
                            <ul>
                            	<li><b><?php echo Lang::t('settings', 'Date of Birth'); ?>:</b></li>
                                <li class="dateofbirth">
                                	<div class="select_style w35per">
                                		<?php
				                            $birthday_month	=	BirthdayHelper::model()->getMonth();
			                            	$birthday_month_selected	=	isset($birthday_month[$model->birthday_month])	?	$birthday_month[$model->birthday_month]	:	$birthday_month[date('n', $model->birthday)];
			                            	echo $form->dropDownList($model, 'birthday_month', $birthday_month, array('class' => 'sl-month virtual_form', 'text' => 'birthday_month_text'));
			                            ?>						
                                        <span class="txt_select user_looking_textselect">
                                        	<span class="select_city birthday_month_text"><?php echo $birthday_month_selected; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
                                    <div class="select_style w25per">
	                                    	<?php
				                            	echo $form->dropDownList($model, 'birthday_day', BirthdayHelper::model()->getDates(), array('class' => 'sl-day virtual_form', 'text' => 'birthday_day_text'));
				                            ?>						
                                            <span class="txt_select user_looking_textselect">
                                            	<span class="select_city birthday_day_text"><?php echo $model->birthday_day; ?></span>
                                        	</span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
                                    <div class="select_style w25per">
			                            <?php
			                            	echo $form->dropDownList($model, 'birthday_year', BirthdayHelper::model()->getYears(), array('class' => 'sl-year virtual_form', 'text' => 'birthday_year_text'));
			                            ?>                                    						
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city birthday_year_text"><?php echo $model->birthday_year; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'You\'re From'); ?>:</b></li>
                                <li>
                                	<div class="select_style w45per">
										<?php
											$list_country = CHtml::listData($list_country, 'id', 'name');
											
											$top_country	=	array();
											
											$top_country1 = SysCountry::model()->getCountryTop();
											
											foreach($top_country1 AS $row){
												array_push($top_country, $row);
											}
											
											$top_country = CHtml::listData($top_country, 'id', 'name');
											if(isset($top_country[$model->country_id])){
												$selected_text	=	$top_country[$model->country_id];
												unset($list_country[$model->country_id]);
											}else{
												$selected_text	=	$list_country[$model->country_id];
											}
											$list_country_group = array('----------- ' => $top_country, '-----------' => $list_country);								
											
										
		                    	       		$list_country = CHtml::listData($list_country, 'id', 'name');
		                    	            echo CHtml::dropDownList('UsrProfileSettings[country_id]', $model->country_id, $list_country_group, array('onchange' => 'getStateRegister();', 'name' => 'txt-country','id' => 'UsrProfileSettings_country_id','class' => 're-country virtual_form', 'text' => 'country_register_text'));
		                    	        ?>                                							
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city country_register_text"><?php echo $selected_text; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
		                            <?php 
		                            	if($list_state){
		                                	$state_none	=	'';
		                                }else{
		                                    $state_none	=	' style="display: none;"';
		                                }
		                            ?>                                	
                                    <div class="select_style w45per rs-state"<?php echo $state_none; ?>>
                                    	<?php if($list_state):
			                                    $list_state = CHtml::listData($list_state, 'id', 'name');
			                                    if ($model->country_id) {
			                                        $top_state = LocationState::model()->getTopStateByCountry($model->country_id);
			                                        $top_state = CHtml::listData($top_state, 'id', 'name');
			                                        
			                                        if(isset($top_state[$model->state_id])){
			                                        	$selected_text	=	$top_state[$model->state_id];
			                                        	unset($list_state[$model->state_id]);
			                                        }else{
			                                        	$selected_text	=	isset($list_state[$model->state_id])	?	$list_state[$model->state_id]	:	Lang::t('search', '--Any--');
			                                        }
			                                        			                                        
			                                        if (sizeof($top_state)) {
			                                            $list_state_group = array(' ' => $top_state, '------------' => $list_state);
			                                        } else {
			                                            $list_state_group = $list_state;
			                                        }
			                                    } else {
			                                        $list_state_group = $list_state;
			                                    }
											echo CHtml::dropDownList('UsrProfileSettings[state_id]',$model->state_id, $list_state_group, array('name' => 'txt-state', 'id' => 'UsrProfileSettings_state_id', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--'))); 
										?>                                    							
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city state_register_text"><?php echo $selected_text; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                        <?php endif; ?>
                                	</div>
		                            <?php 
		                            	if($list_city){
		                                	$city_none	=	'';
		                                }else{
		                                    $city_none	=	' style="display: none;"';
		                                }
		                            ?>                                	
                                    <div class="select_style w45per mar_top_10 rs-city"<?php echo $city_none; ?>>
                                    		<?php if(sizeof($list_city)):
												$list_city = CHtml::listData($list_city, 'id', 'name');
											    $city_select	=	isset($list_city[$model->city_id])	?	$list_city[$model->city_id]	:	Lang::t('search', '--Any--');
											    echo CHtml::dropDownList('UsrProfileSettings[city_id]',$model->city_id, $list_city, array('name' => 'txt-city', 'id' => 'UsrProfileSettings_city_id', 'onchange' => "getDistrictRegister();",'class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--'))); 
											?>						
                                            <span class="txt_select user_looking_textselect">
                                            <span class="select_city city_register_text"><?php echo $city_select; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                        <?php endif; ?>
                                	</div>
		                            <?php 
		                            	if($list_district){
		                                	$district_none	=	'';
		                                }else{
		                                    $district_none	=	' style="display: none;"';
		                                }
		                            ?>                                	
                                    <div class="select_style w45per mar_top_10 rs-district"<?php echo $district_none; ?>>
										<?php 
											if(sizeof($list_district)):
												$list_district = CHtml::listData($list_district, 'id', 'name');
												$district_select	=	isset($list_district[$model->district_id])	?	$list_district[$model->district_id]	:	Lang::t('search', '--Any--');
												echo CHtml::dropDownList('UsrProfileSettings[district_id]',$model->district_id, $list_district, array('name' => 'txt-district', 'id' => 'UsrProfileSettings_district_id', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
										?>						
                                       <span class="txt_select user_looking_textselect">
                                            <span class="select_city district_register_text"><?php echo $district_select; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                        <?php endif; ?>
                                	</div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'Ethnicity'); ?>:</b></li>
                                <li>
                                	<div class="select_style">
                                	    <?php 
			                            	$ethnicity	=	ProfileSettingsConst::getEthnicityLabel();
			                            	$ethnicity_selected	=	isset($ethnicity[$model->ethnic_id])	?	$ethnicity[$model->ethnic_id]	:	$ethnicity[ProfileSettingsConst::ETHNICITY_ASIAN];
			                            	echo $form->dropDownList($model, 'ethnic_id', $ethnicity, array('class' => 'virtual_form', 'text' => 'ethnicity_text')); 
			                            ?>						
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city ethnicity_text"><?php echo $ethnicity_selected; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'Sexuality'); ?>:</b></li>
                                <li>
                                	<div class="select_style">	
		                                <?php
			                            	$sexuality	=	ProfileSettingsConst::getSexualityLabel();
		                            		$sexuality_selected	=	isset($sexuality[$model->sexuality])	?	$sexuality[$model->sexuality]	:	$sexuality[ProfileSettingsConst::SEXUALITY_GAY];
			                           		echo $form->dropDownList($model, 'sexuality', $sexuality, array('class' => 'virtual_form', 'text' => 'sexuality_text'));                                
			                            ?>
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city sexuality_text"><?php echo $sexuality_selected; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'Role'); ?>:</b></li>
                                <li>
                                	<div class="select_style">
			                            <?php
			                                $role	=	ProfileSettingsConst::getSexRoleLabel();
		                            		$role_selected	=	isset($role[$model->sex_role])	?	$role[$model->sex_role]	:	$role[ProfileSettingsConst::SEXROLE_TOP];
			                           		echo $form->dropDownList($model, 'sex_role', $role, array('class' => 'virtual_form', 'text' => 'sex_role_text'));                                
			                            ?>
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city sex_role_text"><?php echo $role_selected; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>			                                                            		
                                	</div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'Relationship Status'); ?>:</b></li>
                                <li>
                                	<div class="select_style">
		                                <?php
		                                	$relationship	=	ProfileSettingsConst::getRelationshipLabel();
		                            		$relationship_selected	=	isset($relationship[$model->relationship])	?	$relationship[$model->relationship]	:	$relationship[ProfileSettingsConst::RELATIONSHIP_PREFER_NOTTOSAY];
		                                	echo $form->dropDownList($model, 'relationship', $relationship, array('class' => 'virtual_form', 'text' => 'relationship_text'));
		                                ?>						
                                        <span class="txt_select user_looking_textselect">
                                            <span class="select_city relationship_text"><?php echo $relationship_selected; ?></span>
                                        </span> 
                                        <span class="btn_combo_down"></span>
                                	</div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'Looking for'); ?>:</b></li>
                                <li>
                                	<div class="">
				                        <?php 
				                    		$looking_for	=	ProfileSettingsConst::getLookingforLabel(); 
				                    		$looking_for_selected	=	!empty($model->looking_for)		?	explode(',', $model->looking_for) 	:	array();
				                    		foreach($looking_for AS $key => $value): 
				                        		$checkbox_looking_for     =    array(
				                        		        'value'     => $key,
				                        		        'class'     => 'input-type-3 looking_for',
				                        		        'id'        =>    'looking_for_' . $key
				                        		);
				                        		if(in_array($key, $looking_for_selected)){
				                                    $checked    =    true;
				                                }else{
				                                    $checked    =    false;
				                                }
				                    	?>                                	
		                                <div class="squaredCheck">
		                                    <?php echo CHtml::CheckBox('looking_for[]', $checked, $checkbox_looking_for); ?>
		                                    <label for="looking_for_<?php echo $key; ?>"></label>
		                                    <label class="mar_left_24" for="looking_for_<?php echo $key; ?>"><?php echo $value; ?></label>
		                                </div>
		                                <?php endforeach; ?>
						          </div>
                                </li>
                                <li><b><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</b></li>
                                <li>
                                	<div class="">
				                        <?php 
				                    		$languages	=	ProfileSettingsConst::getLanguagesLabel(); 
				                    		$languages_selected	=	!empty($model->languages)		?	explode(',', $model->languages) 	:	array();
				                    		foreach($languages AS $key => $value): 
				                        		$checkbox_languages     =    array(
				                        		        'value'     => $key,
				                        		        'class'     => 'input-type-3 languages',
				                        		        'id'        =>    'languages_' . $key
				                        		);
				                        		if(in_array($key, $languages_selected)){
				                                    $checked    =    true;
				                                }else{
				                                    $checked    =    false;
				                                }
				                    	?>                                	
		                                <div class="squaredCheck">
		                                    <?php echo CHtml::CheckBox('languages[]', $checked, $checkbox_languages); ?>
		                                    <label for="languages_<?php echo $key; ?>"></label>
		                                    <label class="mar_left_24" for="languages_<?php echo $key; ?>"><?php echo $value; ?></label>
		                                </div>
		                                <?php endforeach; ?>
						          </div>                                
                                </li>
                                <li class="but_func"><a href="javascript:void(0);" class="but active" onClick="save_settings_basicinfo();"><?php echo Lang::t('settings', 'Save'); ?></a>  <a href="<?php echo $this->usercurrent->createUrl('settings/index'); ?>" class="but"><?php echo Lang::t('settings', 'Discard'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>