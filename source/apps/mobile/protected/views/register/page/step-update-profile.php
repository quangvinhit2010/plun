<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/register/common.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/settings/common.js', CClientScript::POS_BEGIN);		
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.bgiframe.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/friend/common.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/location/common.js');

?>
<?php
	$form = $this->beginWidget('CActiveForm', array(
    	'id' => 'frmFillOut',
        'action' => Yii::app()->createUrl('/register/stepUpdateProfile'),
        'enableClientValidation' => true,
		'enableAjaxValidation' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
	));
?>
<div class="pad_10">
	<!--  step 2 -->
	<div class="setting_gen step_one update_profile_1 update_profile_step1">
		<div class="titles">
			<h2><?php echo Lang::t('register', 'Fill out your profile info'); ?></h2>
			<p><?php echo Lang::t('register', 'This information will help you find your friends on Plun.'); ?></p>
			<!-- <a href="#" class="move_right"></a> -->
		</div>
		<div id="accordion" class="setting_basic_info">
			<p class="title left"><span><?php echo Lang::t('register', 'Basic Info'); ?></span></p>
			<ul class="basicinfo">
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
								$selected_text = '';
								if(!empty($model->country_id)){
    								if(isset($top_country[$model->country_id])){
    									$selected_text	=	$top_country[$model->country_id];
    									unset($list_country[$model->country_id]);
    								}else{
    									$selected_text	=	$list_country[$model->country_id];
    								}
								}
								$list_country_group = array('----------- ' => $top_country, '-----------' => $list_country);
									
																								
                    	       		$list_country = CHtml::listData($list_country, 'id', 'name');
                    	    echo CHtml::dropDownList('UsrProfileSettings[country_id]', $country_id, $list_country_group, array('onchange' => 'getStateRegister();', 'name' => 'txt-country','id' => 're-country','class' => 're-country virtual_form', 'text' => 'country_register_text'));
						?>
						<span class="txt_select user_looking_textselect">
							<span class="country_register_text"><?php echo $selected_text; ?></span>
						</span> 
						<span class="btn_combo_down"></span>
					</div>
					<?php $state_none = ($list_state) ? '' :  'style="display: none;';?>
					<?php if($list_state) { ?>
						<div class="select_style w45per rs-state" <?php echo $state_none; ?>>
							 <?php
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
									
								echo CHtml::dropDownList('UsrProfileSettings[state_id]',$model->state_id, $list_state_group, array('name' => 'txt-state', 'id' => 're-state', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--'))); 
							?>
							<span class="txt_select user_looking_textselect">
								<span class="state_register_text"><?php echo $selected_text; ?></span>
							</span> 
							<span class="btn_combo_down"></span>
						</div>
					<?php } ?>
					<?php if(sizeof($list_city)) {?>
						<div class="select_style w45per mar_top_10 rs-city">
							<?php 
	                        	$list_city = CHtml::listData($list_city, 'id', 'name');
	                        	echo CHtml::dropDownList('UsrProfileSettings[city_id]',$city_id, $list_city, array('name' => 'txt-city', 'id' => 're-city', 'onchange' => "getDistrictRegister();",'class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--'))); 
	                        ?> 						
							<span class="txt_select user_looking_textselect">
								<span class="city_register_text"><?php echo $list_city[$city_id]; ?></span>
							</span> 
							<span class="btn_combo_down"></span>
						</div>
					<?php } ?>
					<?php if(sizeof($list_district)) { ?>
						<div class="select_style w45per mar_top_10">
							<?php 
								$list_district = CHtml::listData($list_district, 'id', 'name');
								echo CHtml::dropDownList('UsrProfileSettings[district_id]',$district_id, $list_district, array('name' => 'txt-district', 'id' => 're-district', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
							?>   						
							<span class="txt_select user_looking_textselect">
								<span class="district_register_text"><?php echo $list_district[$district_id]; ?></span>
							</span> 
							<span class="btn_combo_down"></span>
						</div>
					<?php } ?>
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
							<span class="sexuality_text"><?php echo $sexuality_selected; ?></span>
						</span> 
						<span class="btn_combo_down"></span>
					</div>
				</li>
				<li><b><?php echo Lang::t('settings', 'Role'); ?>:</b></li>
				<li>
					<div class="select_style">
						 <?php
							$role	=	ProfileSettingsConst::getSexRoleLabel();
	                        $model->sex_role	=	!empty($model->sex_role)	?	$model->sex_role	:	ProfileSettingsConst::SEXROLE_TOP;
                            $role_selected	=	isset($role[$model->sex_role])	?	$role[$model->sex_role]	:	$role[ProfileSettingsConst::SEXROLE_TOP];
	                        echo $form->dropDownList($model, 'sex_role', $role, array('class' => 'virtual_form', 'text' => 'sex_role_text'));                                
						?>
						<span class="txt_select user_looking_textselect">
							<span class="sex_role_text"><?php echo $role_selected; ?></span>
						</span> 
						<span class="btn_combo_down"></span>
					</div>
				</li>
				<li><b><?php echo Lang::t('settings', 'Relationship Status'); ?>:</b></li>
				<li>
					<div class="select_style">
						<?php
							$relationship	=	ProfileSettingsConst::getRelationshipLabel();
							$model->relationship	=	!empty($model->relationship)	?	$model->relationship	:	ProfileSettingsConst::RELATIONSHIP_SINGLE;
							$relationship_selected	=	isset($relationship[$model->relationship])	?	$relationship[$model->relationship]	:	$relationship[ProfileSettingsConst::RELATIONSHIP_SINGLE];
							echo $form->dropDownList($model, 'relationship', $relationship, array('class' => 'virtual_form', 'text' => 'relationship_text'));
						?>
						<span class="txt_select user_looking_textselect">
							<span class="relationship_text"><?php echo $relationship_selected; ?></span>
						</span> 
						<span class="btn_combo_down"></span>
					</div>
				</li>
				<li><b><?php echo Lang::t('settings', 'Looking for'); ?>:</b></li>
				<li>
					<div class="findhim-sexrole">
					<?php 
						$looking_for	=	ProfileSettingsConst::getLookingforLabel(); 
						$looking_for_selected	=	!empty($model->looking_for)		?	explode(',', $model->looking_for) 	:	array();
						foreach($looking_for AS $key => $value): 
							$checkbox_looking_for     =    array(
									'value'     => $key,
									'class'     => 'looking_for input-type-3',
									'id'        =>    'looking_for_' . $key
							);
							if(in_array($key, $looking_for_selected)){
								$checked    =    true;
							}else{
								$checked    =    false;
							}
					?>
						<div class="squaredCheck"> 
							<?php echo CHtml::CheckBox('looking_for', $checked, $checkbox_looking_for); ?>
			              	<label for="looking_for_<?php echo $key; ?>"></label>
			              	<label for="looking_for_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
			            </div>
					<?php endforeach; ?>
				  </div>
				</li>
				<li><b><?php echo Lang::t('settings', 'Ethnicity'); ?>:</b></li>
				<li>
					<div class="select_style">
						<?php 
							$ethnicity	=	ProfileSettingsConst::getEthnicityLabel();
							$model->ethnic_id	=	!empty($model->ethnic_id)	?	$model->ethnic_id	:	ProfileSettingsConst::ETHNICITY_ASIAN;
							$ethnicity_selected	=	isset($ethnicity[$model->ethnic_id])	?	$ethnicity[$model->ethnic_id]	:	$ethnicity[ProfileSettingsConst::ETHNICITY_ASIAN];
							echo $form->dropDownList($model, 'ethnic_id', $ethnicity, array('class' => 'virtual_form', 'text' => 'ethnicity_text')); 
						?>
						<span class="txt_select user_looking_textselect">
							<span class="ethnicity_text"><?php echo $ethnicity_selected; ?></span>
						</span> 
						<span class="btn_combo_down"></span>
					</div>
				</li>                                    
				<li><b><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</b></li>
				<li>
					<div class="findhim-sexrole">
						<?php 
							$languages	=	ProfileSettingsConst::getLanguagesLabel(); 
							$languages_selected	=	!empty($model->languages)		?	explode(',', $model->languages) 	:	array();
							foreach($languages AS $key => $value): 
								$checkbox_languages     =    array(
										'value'     => $key,
										'class'     => 'languages input-type-3',
										'id'        =>    'languages_' . $key
								);
								if(in_array($key, $languages_selected)){
									$checked    =    true;
								}else{
									$checked    =    false;
								}
						?>
							<div class="squaredCheck"> 
								<?php echo CHtml::CheckBox('languages', $checked, $checkbox_languages); ?>
				              	<label for="languages_<?php echo $key; ?>"></label>
				              	<label for="languages_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
				            </div>
						<?php endforeach; ?>
				  </div>
				</li>
				<li class="but_func"><a class="but active" onclick="change_step_register('update_profile_step1', 'update_profile_step2');" href="javascript:void(0);"><?php echo Lang::t('settings', 'Next'); ?></a></li>
			</ul>
		</div>
	</div>
	<!--  step 2 -->
	<div class="setting_gen step_one update_profile_1  update_profile_step2" style="display: none;">
		<p class="title left"><span><?php echo Lang::t('settings', 'What you see'); ?></span></p>
		<ul class="whatyousee">
			<li><b><?php echo Lang::t('settings', 'Height'); ?>:</b></li>
			<li>
				<div class="select_style w120">	
          			<?php 
                		$unit_height	=	array();
                		if($model->measurement == UsrProfileSettings::VN_UNIT){
    						$unit_height	=	UnitHelper::getCmList();
                		}else{
    					 	$unit_height	=	UnitHelper::getFeetList();                       			
                		}
                        echo $form->dropDownList($model, 'height', $unit_height, array('class' => 'virtual_form', 'text' => 'height_text', 'empty' => Lang::t('search', '--Any--')));
                    ?>
                    <span class="txt_select"><span class="height_text"><?php echo (isset ($unit_height["$model->height"]) ? $unit_height["$model->height"] : Lang::t('search', '--Any--'));?></span></span> <span class="btn_combo_down"></span>
                </div>
		        <div class="select_style w120">	
                    <?php 
                		$opt_unit_height	=	array(UsrProfileSettings::VN_UNIT => 'cm', UsrProfileSettings::EN_UNIT => 'ft');
                        echo $form->dropDownList($model, 'unit_height', $opt_unit_height, array('class' => 'virtual_form', 'text' => 'unit_height_text', 'onchange' => 'changeUnit(this);'));
                    ?>                                           					
                	<span class="txt_select user_looking_textselect">
                    	<span class="select_city unit_height_text"><?php echo isset($opt_unit_height[$model->unit_height])	?	$opt_unit_height[$model->unit_height]	:	$unit_height[0]; ?></span>
                    </span> 
                    <span class="btn_combo_down"></span>
                </div>
			</li>
			<li><b><?php echo Lang::t('settings', 'Weight'); ?>:</b></li>
			<li>
	      		<div class="select_style w120">
                    <?php 
                        $unit_weight	=	array();
                        if($model->measurement == UsrProfileSettings::VN_UNIT){
    						$unit_weight	=		UnitHelper::getKgList();
                        }else{
    						$unit_weight	=		UnitHelper::getLbsList();
                        }
                       	echo $form->dropDownList($model, 'weight', $unit_weight, array('class' => 'virtual_form', 'text' => 'weight_text', 'empty' => Lang::t('search', '--Any--')));
                    ?>
               		<span class="txt_select"><span class="weight_text"><?php echo (isset($unit_weight["$model->weight"])	?	$unit_weight[$model->weight] :	Lang::t('search', '--Any--')); ?></span></span> <span class="btn_combo_down"></span>
                               		
          		</div>				
		        <div class="select_style w120">
               		<?php 
                		$opt_unit_weight	=	array(UsrProfileSettings::VN_UNIT => 'kg', UsrProfileSettings::EN_UNIT => 'lbs');
                        echo $form->dropDownList($model, 'unit_weight', $opt_unit_weight, array('class' => 'virtual_form', 'text' => 'unit_weight_text', 'onchange' => 'changeUnit(this);'));
                    ?>						
                    <span class="txt_select user_looking_textselect">
                    	<span class="select_city unit_weight_text"><?php echo isset($opt_unit_weight[$model->unit_weight])	?	$opt_unit_weight[$model->unit_weight]	:	$opt_unit_weight[0]; ?></span>
                    </span> 
                    <span class="btn_combo_down"></span>
                </div>
			</li>
			<li><b><?php echo Lang::t('settings', 'Build'); ?>:</b></li>
			<li>
				<div class="select_style">
					 <?php
						$build	=	ProfileSettingsConst::getBuildLabel();
						$build_selected	=	isset($build[$model->body])	?	$build[$model->body]	:	$build[ProfileSettingsConst::BUILD_PREFER_NOTTOSAY];
						echo $form->dropDownList($model, 'body', $build, array('class' => 'virtual_form', 'text' => 'body_text'));                                
					?>
					<span class="txt_select user_looking_textselect">
						<span class="body_text"><?php echo $build_selected; ?></span>
					</span> 
					<span class="btn_combo_down"></span>
				</div>
			</li>
			<li><b><?php echo Lang::t('settings', 'Body Hair'); ?>:</b></li>
			<li>
				<div class="select_style">
					<?php
						$body_hair	=	ProfileSettingsConst::getBodyHairLabel();
						$model->body_hair	=	!empty($model->body_hair)	?	$model->body_hair	:	ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY;
						$body_hair_selected	=	isset($body_hair[$model->body_hair])	?	$body_hair[$model->body_hair]	:	$body_hair[ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY];
						echo $form->dropDownList($model, 'body_hair', $body_hair, array('class' => 'virtual_form', 'text' => 'body_hair_text'));                                
					?>
					<span class="txt_select user_looking_textselect">
						<span class="body_hair_text"><?php echo $body_hair_selected; ?></span>
					</span> 
					<span class="btn_combo_down"></span>
				</div>
			</li>
			<li><b><?php echo Lang::t('settings', 'Tattoos'); ?>:</b></li>
			<li>
				<div class="select_style">
					<?php
						$tattoos	=	ProfileSettingsConst::getTattoosLabel();
						$model->tattoo	=	!empty($model->tattoo)	?	$model->tattoo	:	ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY;
						$tattoos_selected	=	isset($tattoos[$model->tattoo])	?	$tattoos[$model->tattoo]	:	$tattoos[ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY];
						echo $form->dropDownList($model, 'tattoo', $tattoos, array('class' => 'virtual_form', 'text' => 'tattoo_text'));
					?>
					<span class="txt_select user_looking_textselect">
						<span class="tattoo_text"><?php echo $tattoos_selected; ?></span>
					</span> 
					<span class="btn_combo_down"></span>
				</div>
			</li> 
			<li><b><?php echo Lang::t('settings', 'Piercings'); ?>:</b></li>
			<li>
				<div class="select_style">
					<?php
						$piercings	=	ProfileSettingsConst::getPiercingsLabel();
						$model->piercings	=	!empty($model->piercings)	?	$model->piercings	:	ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY;
						$piercings_selected	=	isset($piercings[$model->piercings])	?	$piercings[$model->piercings]	:	$piercings[ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY];
						echo $form->dropDownList($model, 'piercings', $piercings, array('class' => 'virtual_form', 'text' => 'piercings_text'));
					?>						
					<span class="txt_select user_looking_textselect">
						<span class="piercings_text"><?php echo $piercings_selected; ?></span>
					</span> 
					<span class="btn_combo_down"></span>
				</div>
			</li> 
			<li><b><?php echo Lang::t('settings', 'Occupation'); ?>:</b></li>
			<li>
				<div class="select_style">
					<?php
						$occupation	=	ProfileSettingsConst::getOccupationLabel();
						$model->occupation	=	!empty($model->occupation)	?	$model->occupation	:	ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY;
						$occupation_selected	=	isset($occupation[$model->occupation])	?	$occupation[$model->occupation]	:	$occupation[ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY];
						echo $form->dropDownList($model, 'occupation', $occupation, array('class' => 'virtual_form', 'text' => 'occupation_text'));                                
					?>
					<span class="txt_select user_looking_textselect">
						<span class="occupation_text"><?php echo $occupation_selected; ?></span>
					</span> 
					<span class="btn_combo_down"></span>
				</div>
			</li> 
			<li><b><?php echo Lang::t('settings', 'Religion'); ?>:</b></li>
			<li>
				<div class="select_style">
					<?php
						$religion	=	ProfileSettingsConst::getReligionLabel();
						$model->religion	=	!empty($model->religion)	?	$model->religion	:	ProfileSettingsConst::RELIGION_ATHEIST;
						$religion_selected	=	isset($religion[$model->religion])	?	$religion[$model->religion]	:	$religion[ProfileSettingsConst::RELIGION_ATHEIST];
						echo $form->dropDownList($model, 'religion', $religion, array('class' => 'virtual_form', 'text' => 'religion_text'));                                
					?>					
					<span class="txt_select user_looking_textselect">
						<span class="religion_text"><?php echo $religion_selected; ?></span>
					</span> 
					<span class="btn_combo_down"></span>
				</div>
			</li> 
			<li class="but_func">
				<a onclick="save_fill_out();" title="" href="javascript:void(0);" class="but active"><?php echo Lang::t('settings', 'Save'); ?></a>
				<!-- <a href="#" class="skipstep">Skip This Step <label></label></a> -->
			</li>
		</ul>
	</div>

	
</div>
<?php echo $form->hiddenField($model,'measurement'); ?>
<?php $this->endWidget(); ?>
<div class="list_unit_temp" style="display: none;">
                        	<?php 
    							$unit_height_cm	=	UnitHelper::getCmList();
	                            echo CHtml::dropDownList('height', false, $unit_height_cm, array('class' => 'unit_height_cm', 'empty' => Lang::t('search', '--Any--')));
	                            echo CHtml::dropDownList('height', false, UnitHelper::getFeetList(), array('class' => 'unit_height_ft', 'empty' => Lang::t('search', '--Any--')));
	                            


	                            echo CHtml::dropDownList('weight', false, UnitHelper::getKgList(), array('class' => 'unit_weight_kg', 'empty' => Lang::t('search', '--Any--')));
	                            echo CHtml::dropDownList('weight', false, UnitHelper::getLbsList(), array('class' => 'unit_weight_lbs', 'empty' => Lang::t('search', '--Any--')));
                           
	                            
                            ?>
</div>