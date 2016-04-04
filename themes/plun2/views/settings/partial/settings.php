				<?php 
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/settings.js');
					Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/js/jquery.Jcrop.js', CClientScript::POS_BEGIN);
					Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/resources/js/jcrop/css/jquery.Jcrop.css');
					Yii::app()->clientScript->registerScript('settings',"
						$(document).ready(function(){
							objCommon.hw_common();
							objCommon.list_event();
							objCommon.sprScroll(\".setting_page .content_setting .content\");
						});
						$(window).resize(function(){
							objCommon.hw_common();			
						});
						",
					CClientScript::POS_END);
					$userCurrent =  Yii::app()->user->data();
				?>
        
                <!-- InstanceBeginEditable name="doctitle" -->
                <div class="container pheader min_max_1024 clearfix hasBanner_300 settings_page">
                	<div class="explore left">
                    	<div class="list_explore setting_page">
                        	<div class="left menu_setting">
                            	<div class="title"><?php echo Lang::t('settings', 'Settings'); ?></div>
                                <div class="list_menu">
                                	<div class="change_avatar">
                                    	<p><a href="<?php echo $userCurrent->getUserUrl();?>"><img width="160px" width="160px" src="<?php echo $userCurrent->getAvatar(false) ?>" align="absmiddle" class="imgAvatar" /></a></p>
                                        <p><a class="nick" href="<?php echo $userCurrent->getUserUrl();?>"><?php echo $userCurrent->username;?></a></p>
                                        <p>
                                        	<a href="javascript:void(0);" class="click_upload_avatar">(<?php echo Lang::t('settings', 'Click here to change your avatar'); ?>)</a>
                                        	<input type="file" id="upload_avatar" style="display: none;">
                                        	<input type="hidden" name="url_upload_avatar" id="url_upload_avatar" value="<?php echo $userCurrent->createUrl('//my/UploadAvatar', array())?>" />
                                        </p>
                                        <?php 
											$content =  $this->renderPartial('partial/upload-avatar-popup', array(), true);
											$this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'cropAvatar', 'content'=>$content));
										?>
                                    </div>
                                    <div class="">
                                    	<h3><?php echo Lang::t('settings', 'Profile Settings'); ?></h3>
                                    	<ul>
                                            <li><a class="active" href="<?php echo $userCurrent->createUrl('//settings/index');?>"><?php echo Lang::t('settings', 'Basic Info'); ?></a></li>
                                            <li><a href="<?php echo $userCurrent->createUrl('//settings/extra');?>"><?php echo Lang::t('settings', 'Extra'); ?></a></li>
                                        </ul>    
                                    </div>
                                	<div class="">
                                    	<h3><?php echo Lang::t('settings', 'Account Settings'); ?></h3>
                                    	<ul>
                                            <li><a href="<?php echo $userCurrent->createUrl('//settings/languages');?>"><?php echo Lang::t('settings', 'Languages & Change Units'); ?></a></li>
                                    	    <li><a href="<?php echo $userCurrent->createUrl('//settings/changepass');?>"><?php echo Lang::t('settings', 'Change Email & Password'); ?></a></li>
                                        </ul>    
                                    </div>
                                </div>
                            </div>
                            <div class="left content_setting">
                              <?php
				                $form = $this->beginWidget('CActiveForm', array(
				                    'id' => 'frmSaveSettings',
				                    'action' => $this->user->createUrl('/settings/save'),
				                    'enableClientValidation' => true,
				                    'enableAjaxValidation' => true,
				                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
				                        ));
				                ?>
                                <div class="content left">
                                
					              <h3><?php echo Lang::t('settings', 'Basic Info'); ?></h3>
					              <div class="">
					                <table width="420" cellspacing="0" cellpadding="0" border="0" style="overflow:hidden;">
					                  <tbody>
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Date of Birth'); ?>:</td>
					                    <td>
					                        <div class="select_style w90">
					                            <?php
						                            $birthday_month	=	BirthdayHelper::model()->getMonth();
					                            	$birthday_month_selected	=	isset($birthday_month[$model->birthday_month])	?	$birthday_month[$model->birthday_month]	:	$birthday_month[date('n', $model->birthday)];
					                            	echo $form->dropDownList($model, 'birthday_month', $birthday_month, array('class' => 'sl-month virtual_form', 'text' => 'birthday_month_text'));
					                            ?>
					                            <span class="txt_select"><span class="birthday_month_text"><?php echo $birthday_month_selected; ?></span></span> 
					                            <span class="btn_combo_down"></span>
					                        </div>    
					                        <div class="select_style w60">
					                            <?php
					                            	echo $form->dropDownList($model, 'birthday_day', BirthdayHelper::model()->getDates(), array('class' => 'sl-day virtual_form', 'text' => 'birthday_day_text'));
					                            ?>
					                            <span class="txt_select"><span class="birthday_day_text"><?php echo $model->birthday_day; ?></span></span> 
					                            <span class="btn_combo_down"></span>                            
					                        </div>
					                        <div class="select_style w60">
					                            <?php
					                            	echo $form->dropDownList($model, 'birthday_year', BirthdayHelper::model()->getYears(), array('class' => 'sl-year virtual_form', 'text' => 'birthday_year_text'));
					                            ?>
					                            <span class="txt_select"><span class="birthday_year_text"><?php echo $model->birthday_year; ?></span></span> 
					                            <span class="btn_combo_down"></span>                            
					                        </div>                        
					                    </td>
					                  </tr>
							          <tr>
							                    <td class="title"><?php echo Lang::t('settings', 'You\'re From'); ?>:</td>
							                    <td>
							                    	<table style="width:250px;" border="0" cellspacing="0" cellpadding="0">
							                          <tr>
							                            <td style="padding:0;">
							                             	<div class="select_style w160">
																<?php																
																	
																	$html_option		=	false;
																	$html_listcountry		=	'';																	
																	if ($model->country_id) {
																	
																		$top_country 			= 	SysCountry::model()->getCountryTop();
																		 
																		if(isset($top_country[$model->country_id])){
																			$selected_text		=	$top_country[$model->country_id]['name'];
																			$selected_top_id	=	$model->country_id;
																			$selected_id		=	false;
																		}else{
																			$selected_text		=	isset($list_country[$model->country_id]['name'])	?	$list_country[$model->country_id]['name']	:	Lang::t('search', '--Any--');
																			$selected_top_id	=	false;
																			$selected_id		=	!empty($model->country_id)	?	$model->country_id	:	false;
																		}
																	
																		$list_country = CHtml::listData($list_country, 'id', 'name');
																		 
																		if(sizeof($top_country)){
																			$top_country 			= 	CHtml::listData($top_country, 'id', 'name');
																			$html_listcountry		=	CHtml::listOptions($selected_top_id, array('------------' => $top_country), $html_option);
																		}
																		 
																		$html_listcountry		=	$html_listcountry	.	CHtml::listOptions($selected_id, array('------------' => $list_country), $html_option);
																		$html_listcountry		=	CHtml::listOptions(false, array(Lang::t('search', '--Any--')), $html_option)	.	$html_listcountry;
																		 
																	} else {
																		$html_listcountry		=	CHtml::listOptions($model->country_id, $list_country, $html_option);
																	}								                    	        
																	
																	
																?>
								                    	        <select text="country_setting_text" class="setting-country virtual_form" id="UsrProfileSettings_country_id" name="UsrProfileSettings[country_id]" onchange="Location.getStateListSettings();">
								                    	        	<?php echo $html_listcountry; ?>
								                    	        </select>
								                    	        
							                                	<span class="txt_select"><span class="country_setting_text"><?php echo $selected_text; ?></span></span> <span class="btn_combo_down"></span>
								                            	</div>
								                            </td>
							                            </tr>
							                            <?php 
							                            	if($list_state){
							                                	$state_none	=	' style="padding: 0px;"';
							                                }else{
							                                    $state_none	=	' style="padding: 0px; display: none;"';
							                                }
							                            ?>
							                            <tr class="setting-state-list"<?php echo $state_none; ?>>
								                            <td style="padding:16px 0 0 0;">
																<div class="select_style w160">
																  <?php
									                                    
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
									                    	        <select text="state_setting_text" class="setting-state virtual_form" id="UsrProfileSettings_state_id" name="UsrProfileSettings[state_id]" onchange="Location.getCityListSettings();">
									                    	        	<?php echo $html_liststate; ?>
									                    	        </select>
								                    	        	<span class="txt_select"><span class="state_setting_text"><?php echo $selected_text; ?></span></span> <span class="btn_combo_down"></span> 
																</div>
								                            </td>
							                          </tr>
							                          <?php 
							                          if(sizeof($list_city)){
							                          	$city_none	=	'';
							                          }else{
							                          	$city_none	=	' style="display: none;"';
							                          }
							                          ?>
							                          <tr class="setting-city-list"<?php echo $city_none; ?>>
								                           <td style="padding:16px 0 0 0;">
																						<div class="select_style w160">
																	                        <?php 
																	                        	$list_city = CHtml::listData($list_city, 'id', 'name');
																	                        	$city_select	=	isset($list_city[$model->city_id])	?	$list_city[$model->city_id]	:	Lang::t('search', '--Any--');
																	                        	echo CHtml::dropDownList('UsrProfileSettings[city_id]',$model->city_id, $list_city, array('name' => 'txt-city', 'id' => 'UsrProfileSettings_city_id', 'onchange' => "Location.getDistrictListSettings();",'class' => 'setting-city virtual_form', 'text' => 'city_setting_text', 'empty' => Lang::t('search', '--Any--'))); 
																	                        ?>    
																	                        <span class="txt_select"><span class="city_setting_text"><?php echo $city_select; ?></span></span> <span class="btn_combo_down"></span>
																                        </div>      
								                            </td>
							                           </tr>
							                          <?php 
							                          if(sizeof($list_district)){
							                          	$district_none	=	'';
							                          }else{
							                          	$district_none	=	' style="display: none;"';
							                          }
							                          ?>
							                           <tr class="setting-district-list"<?php echo $district_none; ?>>
								                            <td style="padding:16px 0 0 0;">
																						<div class="select_style w160">
																	                        <?php 
																	                        	$list_district = CHtml::listData($list_district, 'id', 'name');
																	                        	$district_select	=	isset($list_district[$model->district_id])	?	$list_district[$model->district_id]	:	Lang::t('search', '--Any--');
																	                        	
																	                        	echo CHtml::dropDownList('UsrProfileSettings[district_id]',$model->district_id, $list_district, array('name' => 'txt-district', 'id' => 'UsrProfileSettings_district_id', 'class' => 'setting-district virtual_form', 'text' => 'district_setting_text', 'empty' => Lang::t('search', '--Any--'))); 
																	                        ?>   
																	                        <span class="txt_select"><span class="district_setting_text"><?php echo $district_select; ?></span></span> <span class="btn_combo_down"></span>
																                        </div> 
								                            </td>
							                          </tr>
							                        </table>
							                    </td>
							        </tr>
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Ethnicity'); ?>:</td>
					                    <td>
					                    	<div class="select_style w160">
					                            <?php 
					                            	$ethnicity	=	ProfileSettingsConst::getEthnicityLabel();
					                            	$ethnicity_selected	=	isset($ethnicity[$model->ethnic_id])	?	$ethnicity[$model->ethnic_id]	:	$ethnicity[ProfileSettingsConst::ETHNICITY_ASIAN];
					                            	echo $form->dropDownList($model, 'ethnic_id', $ethnicity, array('class' => 'virtual_form', 'text' => 'ethnicity_text')); 
					                            ?>
					                            <span class="txt_select"><span class="ethnicity_text"><?php echo $ethnicity_selected; ?></span></span> <span class="btn_combo_down"></span>
					                        </div>
					                    </td>
					                  </tr>
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Sexuality'); ?>:</td>
					                    <td>
					                        <div class="select_style w160">
						                            <?php
						                            	$sexuality	=	ProfileSettingsConst::getSexualityLabel();
					                            		$sexuality_selected	=	isset($sexuality[$model->sexuality])	?	$sexuality[$model->sexuality]	:	$sexuality[ProfileSettingsConst::SEXUALITY_GAY];
						                           		echo $form->dropDownList($model, 'sexuality', $sexuality, array('class' => 'virtual_form', 'text' => 'sexuality_text'));                                
						                            ?>
					                            <span class="txt_select"><span class="sexuality_text"><?php echo $sexuality_selected; ?></span></span> <span class="btn_combo_down"></span>
					                        </div>
					                    </td>
					                  </tr>
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Role'); ?>:</td>
					                    <td>
					                        <div class="select_style w160">
						                            <?php
						                                $role	=	ProfileSettingsConst::getSexRoleLabel();
					                            		$role_selected	=	isset($role[$model->sex_role])	?	$role[$model->sex_role]	:	$role[ProfileSettingsConst::SEXROLE_TOP];
						                           		echo $form->dropDownList($model, 'sex_role', $role, array('class' => 'virtual_form', 'text' => 'sex_role_text'));                                
						                            ?>
					                            <span class="txt_select"><span class="sex_role_text"><?php echo $role_selected; ?></span></span> <span class="btn_combo_down"></span>
					                        </div>
					                    </td>
					                  </tr>
					                  <tr>
					                    <td class="title"><?php echo Lang::t('settings', 'Relationship Status'); ?>:</td>
					                    <td>
					                    	<div class="select_style w160">
					                                <?php
					                                	$relationship	=	ProfileSettingsConst::getRelationshipLabel();
					                            		$relationship_selected	=	isset($relationship[$model->relationship])	?	$relationship[$model->relationship]	:	$relationship[ProfileSettingsConst::RELATIONSHIP_PREFER_NOTTOSAY];
					                                	echo $form->dropDownList($model, 'relationship', $relationship, array('class' => 'virtual_form', 'text' => 'relationship_text'));
					                                ?>
					                            <span class="txt_select"><span class="relationship_text"><?php echo $relationship_selected; ?></span></span> <span class="btn_combo_down"></span>
					                        </div>
					                    </td>
					                  </tr>
					                  <tr>
					                    <td class="title" align="left" valign="top"><?php echo Lang::t('settings', 'Looking for'); ?>:</td>
					                    <td>                        
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
					                    	    <?php echo CHtml::CheckBox('looking_for[]', $checked, $checkbox_looking_for); ?>
					                            <label for="looking_for_<?php echo $key; ?>"></label>
					                            <label for="looking_for_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
					                        </div>
					                        <?php endforeach; ?>                                            	                                                
					                    </td>
					                  </tr>
									  <tr>
					                    <td width="145" class="title"><?php echo Lang::t('settings', 'Languages I Understand'); ?>:</td>
					                    <td>                     
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
					                    	<div class="squaredCheck">
					                    	    <?php echo CHtml::CheckBox('languages[]', $checked, $checkbox_languages); ?>
					                            <label for="languages_<?php echo $key; ?>"></label>
					                            <label for="languages_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
					                        </div>
					                        <?php endforeach; ?>
					                    </td>
					                  </tr>
					                  </tbody>
					                </table>
					              </div>
					              
					              <h3><?php echo Lang::t('settings', 'What you see'); ?></h3>
					              <div class="">
					                <table width="420" cellspacing="0" cellpadding="0" border="0">
									 <tbody>
										 <tr>
						                    <td class="title"><?php echo Lang::t('settings', 'Height'); ?>:</td>
						                    <td style="width:170px; float:left;">
						                        <div class="select_style w90">
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
						                        
						                        
						                        <div class="select_style w60">
						                        	<?php 
						                        		$opt_unit_height	=	array(UsrProfileSettings::VN_UNIT => 'cm', UsrProfileSettings::EN_UNIT => 'ft');
							                            echo $form->dropDownList($model, 'unit_height', $opt_unit_height, array('class' => 'virtual_form', 'text' => 'unit_height_text', 'onchange' => 'Settings.changeUnit(this);'));
						                            ?>
						                            <span class="txt_select"><span class="unit_height_text"><?php echo isset($opt_unit_height[$model->unit_height])	?	$opt_unit_height[$model->unit_height]	:	$unit_height[0]; ?></span></span> <span class="btn_combo_down"></span>
						                        </div>
						                    </td>
						                  </tr> 
					                  
										 <tr>
						                    <td class="title"><?php echo Lang::t('settings', 'Weight'); ?>:</td>
						                    <td>
						                    	<div class="select_style w90">
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
						                       <div class="select_style w60">
						                        	<?php 
						                        		$opt_unit_weight	=	array(UsrProfileSettings::VN_UNIT => 'kg', UsrProfileSettings::EN_UNIT => 'lbs');
							                            echo $form->dropDownList($model, 'unit_weight', $opt_unit_weight, array('class' => 'virtual_form', 'text' => 'unit_weight_text', 'onchange' => 'Settings.changeUnit(this);'));
						                            ?>
						                            <span class="txt_select"><span class="unit_weight_text"><?php echo isset($opt_unit_weight[$model->unit_weight])	?	$opt_unit_weight[$model->unit_weight]	:	$opt_unit_weight[0]; ?></span></span> <span class="btn_combo_down"></span>
						                        </div>
						                    </td> 
						                  </tr>    
						                  <tr>
						                    <td class="title"><?php echo Lang::t('settings', 'Build'); ?>:</td>
						                    <td>
						                        <div class="select_style w160">
							                            <?php
							                            	$build	=	ProfileSettingsConst::getBuildLabel();
						                            		$build_selected	=	isset($build[$model->body])	?	$build[$model->body]	:	$build[ProfileSettingsConst::BUILD_PREFER_NOTTOSAY];
							                           		echo $form->dropDownList($model, 'body', $build, array('class' => 'virtual_form', 'text' => 'body_text'));                                
							                            ?>
						                            <span class="txt_select"><span class="body_text"><?php echo $build_selected; ?></span></span> <span class="btn_combo_down"></span>
						                        </div>
						                    </td>
						                  </tr>
						                  <tr>
						                    <td class="title"><?php echo Lang::t('settings', 'Body Hair'); ?>:</td>
						                    <td>
						                        <div class="select_style w160">
							                            <?php
							                                $body_hair	=	ProfileSettingsConst::getBodyHairLabel();
						                            		$body_hair_selected	=	isset($body_hair[$model->body_hair])	?	$body_hair[$model->body_hair]	:	$body_hair[ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY];
							                           		echo $form->dropDownList($model, 'body_hair', $body_hair, array('class' => 'virtual_form', 'text' => 'body_hair_text'));                                
							                            ?>
						                            <span class="txt_select"><span class="body_hair_text"><?php echo $body_hair_selected; ?></span></span> <span class="btn_combo_down"></span>
						                        </div>
						                    </td>
						                  </tr>
						                  <tr>
						                    <td class="title"><?php echo Lang::t('settings', 'Tattoos'); ?>:</td>
						                    <td>
						                    	<div class="select_style w160">
						                                <?php
						                                	$tattoos	=	ProfileSettingsConst::getTattoosLabel();
						                            		$tattoos_selected	=	isset($tattoos[$model->tattoo])	?	$tattoos[$model->tattoo]	:	$tattoos[ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY];
						                                	echo $form->dropDownList($model, 'tattoo', $tattoos, array('class' => 'virtual_form', 'text' => 'tattoo_text'));
						                                ?>
						                            <span class="txt_select"><span class="tattoo_text"><?php echo $tattoos_selected; ?></span></span> <span class="btn_combo_down"></span>
						                        </div>
						                    </td>
						                  </tr>
						                  <tr>
						                    <td class="title"><?php echo Lang::t('settings', 'Piercings'); ?>:</td>
						                    <td>
						                    	<div class="select_style w160">
						                                <?php
						                                	$piercings	=	ProfileSettingsConst::getPiercingsLabel();
						                            		$piercings_selected	=	isset($piercings[$model->piercings])	?	$piercings[$model->piercings]	:	$piercings[ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY];
						                                	echo $form->dropDownList($model, 'piercings', $piercings, array('class' => 'virtual_form', 'text' => 'piercings_text'));
						                                ?>
						                            <span class="txt_select"><span class="piercings_text"><?php echo $piercings_selected; ?></span></span> <span class="btn_combo_down"></span>
						                        </div>
						                    </td>
						                  </tr>
						                  <tr>
						                    <td></td>
						                    <td>
						                        <a class="right save_change" href="javascript:void(0);" onClick="Settings.save_settings_basicinfo();"><?php echo Lang::t('general', 'Save Changes'); ?></a>
						                        <a class="right" href="javascript:void(0);" onclick="Settings.resetForm();">Reset</a>
						                    </td>
						                  </tr>                                                    
					                </tbody>
					                </table>
					              </div>
                                </div>
                                <?php echo $form->hiddenField($model,'measurement'); ?>
                                <?php $this->endWidget(); ?>
                            </div>
                    	</div>
                    	<?php $this->widget('frontend.widgets.UserPage.Banner', array('type'=>SysBanner::TYPE_W_300)); ?>
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
          