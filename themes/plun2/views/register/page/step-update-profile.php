<?php
		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/register.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/settings.js');
		
    	$form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmFillOut',
                    'action' => Yii::app()->createUrl('/register/stepUpdateProfile'),
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<!-- InstanceBeginEditable name="doctitle" -->
<div class="container pheader min_max_926 page_step">
	<div class="explore left clearfix">
		<div class="title_top">
			<div class="process_bar clearfix">
				<ul>
					<li>
						<div class="active">
							<span class="icon_common icon_round"></span> <span><?php echo Lang::t('settings', 'Update Profile'); ?></span>

						</div> <span class="line_process"></span>
					</li>
					<li>
						<div>
							<span class="icon_common icon_round"></span> <span><?php echo Lang::t('register', 'Set Avatar'); ?></span>

						</div> <span class="line_process"></span>
					</li>
					<li>
						<div>
							<span class="icon_common icon_round"></span> <span><?php echo Lang::t('register', 'Find Friends'); ?></span>

						</div> <span class="line_process"></span>
					</li>
					<li>
						<div>
							<span class="icon_common icon_round"></span> <span><?php echo Lang::t('register', 'Suggest Friends'); ?></span>
						</div>

					</li>
				</ul>
			</div>
		</div>
		<div class="header_page">
			<h4><?php echo Lang::t('register', 'Fill out your profile info'); ?></h4>
			<p><?php echo Lang::t('register', 'This information will help you find your friends on Plun.'); ?></p>
		</div>
		<div class="box_detail basic_info">
			<div class="row">
				<div class="column">
					<h5><?php echo Lang::t('register', 'Basic Info'); ?></h5>
				</div>
				<div class="column">
					<h5><?php echo Lang::t('settings', 'What you see'); ?></h5>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'You\'re From'); ?>:</span>
						<div class="left group_frm">
																<?php
																
																$html_option = false;
																$html_listcountry = '';
																if ($model->country_id) {
																	
																	$top_country = SysCountry::model ()->getCountryTop ();
																	
																	if (isset ( $top_country [$model->country_id] )) {
																		$selected_text = $top_country [$model->country_id] ['name'];
																		$selected_top_id = $model->country_id;
																		$selected_id = false;
																	} else {
																		$selected_text = isset ( $list_country [$model->country_id] ['name'] ) ? $list_country [$model->country_id] ['name'] : Lang::t ( 'search', '--Any--' );
																		$selected_top_id = false;
																		$selected_id = ! empty ( $model->country_id ) ? $model->country_id : false;
																	}
																	
																	$list_country = CHtml::listData ( $list_country, 'id', 'name' );
																	
																	if (sizeof ( $top_country )) {
																		$top_country = CHtml::listData ( $top_country, 'id', 'name' );
																		$html_listcountry = CHtml::listOptions ( $selected_top_id, array (
																				'------------' => $top_country 
																		), $html_option );
																	}
																	
																	$html_listcountry = $html_listcountry . CHtml::listOptions ( $selected_id, array (
																			'------------' => $list_country 
																	), $html_option );
																} else {
																	$html_listcountry = CHtml::listOptions ( $model->country_id, $list_country, $html_option );
																}
																
																?>
								                    	        <select
								class="register-country"
								id="UsrProfileSettings_country_id"
								name="UsrProfileSettings[country_id]"
								onchange="Location.getStateListRegister();">
								                    	        	<?php echo $html_listcountry; ?>
								                    	        </select>
								                    	        
								                    	<?php 
							                            	if($list_state){
							                                	$state_none	=	'';
							                                }else{
							                                    $state_none	=	' style="display: none;"';
							                                }
									                                    $html_option		=	false;
									                                    $html_liststate		=	'';
									                                    
									                                    if ($model->country_id) {

									                                        $top_state 			= 	LocationState::model()->getTopStateByCountry($model->country_id);
									                                        
									                                        if(isset($top_state[$model->state_id])){
									                                        	$selected_text		=	$top_state[$model->state_id]['name'];
									                                        	$selected_top_id	=	$model->state_id;
									                                        	$selected_id		=	false;
									                                        }else{
									                                        	$selected_text	=	isset($list_state[$model->state_id])	?	$list_state[$model->state_id]['name']	:	Lang::t('search', '--Any--');
									                                        	$selected_top_id	=	false;
									                                        	$selected_id		=	!empty($model->state_id)	?	$model->state_id	:	false;
																			}

									                                        $list_state = CHtml::listData($list_state, 'id', 'name');
									                                        
									                                        if(sizeof($top_state)){
																				$top_state 			= 	CHtml::listData($top_state, 'id', 'name');
									                                        	$html_liststate		=	CHtml::listOptions($selected_top_id, array('------------' => $top_state), $html_option);
									                                        }
									                                        
									                                        $html_liststate		=	$html_liststate	.	CHtml::listOptions($selected_id, array('------------' => $list_state), $html_option);
									                                        $html_liststate		=	CHtml::listOptions(false, array(Lang::t('search', '--Any--')), $html_option)	.	$html_liststate;
									                                        
									                                    } else {
									                                        $html_liststate		=	CHtml::listOptions($model->state_id, $list_state, $html_option);
									                                    }
						
														?>
									                    <select class="register-state right" id="UsrProfileSettings_state_id" name="UsrProfileSettings[state_id]" onchange="Location.getCityListRegister();"<?php echo $state_none; ?>>
									                    	<?php echo $html_liststate; ?>
									                    </select>
							                          <?php 
							                          if(sizeof($list_city)){
							                          	$city_none	=	'display: inline;';
							                          }else{
							                          	$city_none	=	'display: none;';
							                          }
			
														$list_city = CHtml::listData($list_city, 'id', 'name');
														$city_select	=	isset($list_city[$model->city_id])	?	$list_city[$model->city_id]	:	Lang::t('search', '--Any--');
														echo CHtml::dropDownList('UsrProfileSettings[city_id]',$model->city_id, $list_city, array('style' => $city_none,'name' => 'txt-city', 'id' => 'UsrProfileSettings_city_id', 'onchange' => "Location.getDistrictListRegister();",'class' => 'register-city', 'empty' => Lang::t('search', '--Any--'))); 

							                          if(sizeof($list_district)){
							                          	$district_none	=	'display: inline;';
							                          }else{
							                          	$district_none	=	'display: none;';
							                          }
							                          $list_district = CHtml::listData($list_district, 'id', 'name');
							                          $district_select	=	isset($list_district[$model->district_id])	?	$list_district[$model->district_id]	:	Lang::t('search', '--Any--');
							                          
							                          echo CHtml::dropDownList('UsrProfileSettings[district_id]',$model->district_id, $list_district, array('style' => $district_none,'name' => 'txt-district', 'id' => 'UsrProfileSettings_district_id', 'class' => 'register-district right', 'empty' => Lang::t('search', '--Any--')));
							                          ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Ethnicity'); ?>:</span>
						<div class="left group_frm">
					           <?php 
					           	$ethnicity	=	ProfileSettingsConst::getEthnicityLabel();
					           	$ethnicity_selected	=	isset($ethnicity[$model->ethnic_id])	?	$ethnicity[$model->ethnic_id]	:	$ethnicity[ProfileSettingsConst::ETHNICITY_ASIAN];
					            echo $form->dropDownList($model, 'ethnic_id', $ethnicity); 
					          ?>
						</div>
					</div>

					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Sexuality'); ?>:</span>
						<div class="left group_frm">
				              <?php
					          	$sexuality	=	ProfileSettingsConst::getSexualityLabel();
				                $sexuality_selected	=	isset($sexuality[$model->sexuality])	?	$sexuality[$model->sexuality]	:	$sexuality[ProfileSettingsConst::SEXUALITY_GAY];
					            echo $form->dropDownList($model, 'sexuality', $sexuality, array('class' => 'virtual_form', 'text' => 'sexuality_text'));                                
					          ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Role'); ?>:</span>
						<div class="left group_frm">
              					<?php
	                                $role	=	ProfileSettingsConst::getSexRoleLabel();
	                                $model->sex_role	=	!empty($model->sex_role)	?	$model->sex_role	:	ProfileSettingsConst::SEXROLE_TOP;
                            		$role_selected	=	isset($role[$model->sex_role])	?	$role[$model->sex_role]	:	$role[ProfileSettingsConst::SEXROLE_TOP];
	                           		echo $form->dropDownList($model, 'sex_role', $role, array('class' => 'virtual_form', 'text' => 'sex_role_text'));                                
	                            ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left">Relationship Status:</span>
						<div class="left group_frm">
              					<?php
                                	$relationship	=	ProfileSettingsConst::getRelationshipLabel();
                                	$model->relationship	=	!empty($model->relationship)	?	$model->relationship	:	ProfileSettingsConst::RELATIONSHIP_SINGLE;
                            		$relationship_selected	=	isset($relationship[$model->relationship])	?	$relationship[$model->relationship]	:	$relationship[ProfileSettingsConst::RELATIONSHIP_SINGLE];
                                	echo $form->dropDownList($model, 'relationship', $relationship, array('class' => 'virtual_form', 'text' => 'relationship_text'));
                                ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Looking for'); ?>:</span>
						<div class="left group_frm">
						    <?php 
	                    		$looking_for	=	ProfileSettingsConst::getLookingforLabel(); 
	                    		$looking_for_selected	=	!empty($model->looking_for)		?	explode(',', $model->looking_for) 	:	array();
	                    		foreach($looking_for AS $key => $value): 
	                        		$checkbox_looking_for     =    array(
	                        		        'value'     => $key,
	                        		        'class'     => 'looking_for',
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
				              <label for="looking_for_<?php echo $key; ?>"
									class="input-type-3 sexuality"><?php echo $value; ?></label>
							</div>
				            <?php endforeach; ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</span>
						<div class="left group_frm">
					          <?php 
					                    		$languages	=	ProfileSettingsConst::getLanguagesLabel(); 
					                    		$languages_selected	=	!empty($model->languages)		?	explode(',', $model->languages) 	:	array();
					                    		foreach($languages AS $key => $value): 
					                        		$checkbox_languages     =    array(
					                        		        'value'     => $key,
					                        		        'class'     => 'languages',
					                        		        'id'        =>    'languages_' . $key
					                        		);
					                        		if(in_array($key, $languages_selected)){
					                                    $checked    =    true;
					                                }else{
					                                    $checked    =    false;
					                                }
					            ?>
					            <div class="squaredCheck"> <?php echo CHtml::CheckBox('languages', $checked, $checkbox_languages); ?>
					              <label for="languages_<?php echo $key; ?>"
									class="mar_left_24"><?php echo $value; ?></label>
								</div>
					            <?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Height'); ?>:</span>
						<div class="left group_frm">
                        	<?php 
                        		$unit_height	=	array();
                        		if($model->measurement == UsrProfileSettings::VN_UNIT){
									$unit_height	=	UnitHelper::getCmList();
                        		}else{
								 	$unit_height	=	UnitHelper::getFeetList();                       			
                        		}
                        		$opt_unit_height	=	array(UsrProfileSettings::VN_UNIT => 'cm', UsrProfileSettings::EN_UNIT => 'ft');
                        		
	                            echo $form->dropDownList($model, 'height', $unit_height, array('class' => 'virtual_form', 'empty' => Lang::t('search', '--Any--')));
	                            echo $form->dropDownList($model, 'unit_height', $opt_unit_height, array('class' => 'right', 'onchange' => 'Settings.changeUnit(this);'));
                            ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Weight'); ?>:</span>
						<div class="left group_frm">
	                        <?php 
		                        $unit_weight	=	array();
		                        if($model->measurement == UsrProfileSettings::VN_UNIT){
									$unit_weight	=		UnitHelper::getKgList();
		                        }else{
									$unit_weight	=		UnitHelper::getLbsList();
		                        }
		                        $opt_unit_weight	=	array(UsrProfileSettings::VN_UNIT => 'kg', UsrProfileSettings::EN_UNIT => 'lbs');
		                        
		                       	echo $form->dropDownList($model, 'weight', $unit_weight, array('class' => 'virtual_form', 'empty' => Lang::t('search', '--Any--')));
	                            echo $form->dropDownList($model, 'unit_weight', $opt_unit_weight, array('class' => 'right', 'onchange' => 'Settings.changeUnit(this);'));
                            ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Build'); ?>:</span>
						<div class="left group_frm">
							<?php
	                            $build	=	ProfileSettingsConst::getBuildLabel();
                            	$build_selected	=	isset($build[$model->body])	?	$build[$model->body]	:	$build[ProfileSettingsConst::BUILD_PREFER_NOTTOSAY];
	                           	echo $form->dropDownList($model, 'body', $build, array('class' => 'select_persent100'));                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Body Hair'); ?>:</span>
						<div class="left group_frm">
							<?php
	                                $body_hair	=	ProfileSettingsConst::getBodyHairLabel();
	                                $model->body_hair	=	!empty($model->body_hair)	?	$model->body_hair	:	ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY;
                            		$body_hair_selected	=	isset($body_hair[$model->body_hair])	?	$body_hair[$model->body_hair]	:	$body_hair[ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'body_hair', $body_hair, array('class' => 'select_persent100'));                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Tattoos'); ?>:</span>
						<div class="left group_frm">
							<?php
                                	$tattoos	=	ProfileSettingsConst::getTattoosLabel();
                                	$model->tattoo	=	!empty($model->tattoo)	?	$model->tattoo	:	ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY;
                            		$tattoos_selected	=	isset($tattoos[$model->tattoo])	?	$tattoos[$model->tattoo]	:	$tattoos[ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'tattoo', $tattoos, array('class' => 'select_persent100'));
                            ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Piercings'); ?>:</span>
						<div class="left group_frm">
								<?php
                                	$piercings	=	ProfileSettingsConst::getPiercingsLabel();
                                	$model->piercings	=	!empty($model->piercings)	?	$model->piercings	:	ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY;
                            		$piercings_selected	=	isset($piercings[$model->piercings])	?	$piercings[$model->piercings]	:	$piercings[ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'piercings', $piercings, array('class' => 'select_persent100'));
                                ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Occupation'); ?>:</span>
						<div class="left group_frm">
							<?php
	                            $occupation	=	ProfileSettingsConst::getOccupationLabel();
	                           	$model->occupation	=	!empty($model->occupation)	?	$model->occupation	:	ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY;
                            	$occupation_selected	=	isset($occupation[$model->occupation])	?	$occupation[$model->occupation]	:	$occupation[ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY];
	                       		echo $form->dropDownList($model, 'occupation', $occupation, array('class' => 'select_persent100'));                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Religion'); ?>:</span>
						<div class="left group_frm">
							<?php
	                            	$religion	=	ProfileSettingsConst::getReligionLabel();
	                            	$model->religion	=	!empty($model->religion)	?	$model->religion	:	ProfileSettingsConst::RELIGION_ATHEIST;
                            		$religion_selected	=	isset($religion[$model->religion])	?	$religion[$model->religion]	:	$religion[ProfileSettingsConst::RELIGION_ATHEIST];
	                           		echo $form->dropDownList($model, 'religion', $religion, array('class' => 'select_persent100'));                                
	                         ?>
						</div>
					</div>
					<div class="extra_infor clearfix">
						<div class="right">
							<h2>
								<a href="javascript:void(0);" onclick="Settings.switchBasicToExtra();"><?php echo Lang::t('settings', 'Extra'); ?> 
									<i
									class="icon_common icon_arrow_extra"></i>
								</a>
							</h2>
							<span>(<?php echo Lang::t('settings', 'Not Required'); ?>)</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box_detail extra_info" style="display: none;">
			<div class="row">
				<div class="column">
					<h5 class="left"><?php echo Lang::t('settings', 'Extra'); ?></h5>
					<div class="goback right">
						<h3>
							<ins></ins>
							<a href="javascript:void(0);" onclick="Settings.switchExtraToBasic();">
								<?php echo Lang::t('register', 'Basic Info'); ?>
							</a>
						</h3>
						<p>
							<a href="javascript:void(0);" onclick="Settings.switchExtraToBasic();">(<?php echo Lang::t('settings', 'Back'); ?>)</a>
						</p>
					</div>
				</div>
				<div class="column"></div>
			</div>
			<div class="row">
				<div class="column">
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Attributes'); ?>:</span>
						<div class="left group_frm">
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
	                    	    <?php echo CHtml::CheckBox('my_attributes', $checked, $checkbox_attributes); ?>
	                            <label for="my_attributes_<?php echo $key; ?>"><?php echo $value; ?></label>
	                        </div>
	                        <?php endforeach; ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Mannerism'); ?>:</span>
						<div class="left group_frm">
							<?php
	                            	$mannerism	=	ProfileSettingsConst::getMannerismLabel();
	                            	$model->mannerism	=	!empty($model->mannerism)	?	$model->mannerism	:	ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY;
                            		$mannerism_selected	=	isset($mannerism[$model->mannerism])	?	$mannerism[$model->mannerism]	:	$mannerism[ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'mannerism', $mannerism);                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Drink'); ?>:</span>
						<div class="left group_frm">
                    	   <?php
	                            	$drink	=	ProfileSettingsConst::getDrinkLabel();
	                            	$model->drink	=	!empty($model->drink)	?	$model->drink	:	ProfileSettingsConst::DRINK_PREFER_NOTTOSAY;
                            		$drink_selected	=	isset($drink[$model->drink])	?	$drink[$model->drink]	:	$drink[ProfileSettingsConst::DRINK_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'drink', $drink);                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Club'); ?>:</span>
						<div class="left group_frm">
                    	   <?php
	                            	$club	=	ProfileSettingsConst::getClubLabel();
	                            	$model->club	=	!empty($model->club)	?	$model->club	:	ProfileSettingsConst::CLUB_PREFER_NOTTOSAY;
                            		$club_selected	=	isset($club[$model->club])	?	$club[$model->club]	:	$club[ProfileSettingsConst::CLUB_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'club', $club);                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'I Live With'); ?>:</span>
						<div class="left group_frm">
                    	   <?php
	                            	$live_with	=	ProfileSettingsConst::getLiveWithLabel();
	                            	$model->live_with	=	!empty($model->live_with)	?	$model->live_with	:	ProfileSettingsConst::LIVE_WITH_NOBODY;
                            		$live_with_selected	=	isset($live_with[$model->live_with])	?	$live_with[$model->live_with]	:	$live_with[ProfileSettingsConst::LIVE_WITH_NOBODY];
	                           		echo $form->dropDownList($model, 'live_with', $live_with);                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Smoke'); ?>:</span>
						<div class="left group_frm">
                    	   <?php
	                            	$smoke	=	ProfileSettingsConst::getSmokeLabel();
	                            	$model->smoke	=	!empty($model->smoke)	?	$model->smoke	:	ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY;
                            		$smoke_selected	=	isset($smoke[$model->smoke])	?	$smoke[$model->smoke]	:	$smoke[ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'smoke', $smoke);                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'Safe sex'); ?>:</span>
						<div class="left group_frm">
                    	   <?php
	                            	$safe_sex	=	ProfileSettingsConst::getSafeSexLabel();
	                            	$model->safer_sex	=	!empty($model->safer_sex)	?	$model->safer_sex	:	ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY;
                            		$safe_sex_selected	=	isset($safe_sex[$model->safer_sex])	?	$safe_sex[$model->safer_sex]	:	$safe_sex[ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'safer_sex', $safe_sex);                                
	                        ?>
						</div>
					</div>
					<div class="group_item">
						<span class="left"><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</span>
						<div class="left group_frm">
                    	   <?php
	                            	$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
	                            	$model->public_information	=	!empty($model->public_information)	?	$model->public_information	:	ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY;
                            		$public_information_selected	=	isset($public_information[$model->public_information])	?	$public_information[$model->public_information]	:	$public_information[ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'public_information', $public_information);                                
	                        ?>
						</div>
					</div>
				</div>
				<div class="column"></div>
			</div>
		</div>
		<div class="btn_footer">
			<a href="javascript:void(0);" class="btn_big bg_gray" onclick="Register.save_fill_out();"><?php echo Lang::t('settings', 'Next Step'); ?></a>
		</div>
	</div>
</div>
<!-- InstanceEndEditable -->

                <div class="list_unit_temp" style="display: none;">
					<?php 
				        echo CHtml::dropDownList('height', false, UnitHelper::getCmList(), array('class' => 'unit_height_cm', 'empty' => Lang::t('search', '--Any--')));
				        echo CHtml::dropDownList('height', false, UnitHelper::getFeetList(), array('class' => 'unit_height_ft', 'empty' => Lang::t('search', '--Any--')));
				        echo CHtml::dropDownList('weight', false, UnitHelper::getKgList(), array('class' => 'unit_weight_kg', 'empty' => Lang::t('search', '--Any--')));
				        echo CHtml::dropDownList('weight', false, UnitHelper::getLbsList(), array('class' => 'unit_weight_lbs', 'empty' => Lang::t('search', '--Any--')));
				    ?>
				</div>
<?php echo $form->hiddenField($model,'measurement'); ?>
<?php $this->endWidget(); ?>
