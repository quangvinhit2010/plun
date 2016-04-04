<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/register/common.js', CClientScript::POS_BEGIN);
		
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.bgiframe.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.autocomplete.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/search/jquery.autocompletefb.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/friend/common.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/location/common.js');

?>

<div class="step-search">
  <?php $this->renderPartial('page/_tab');?>
  <div class="step-cont">
    <h2><?php echo Lang::t('register', 'Fill out your profile info'); ?></h2>
    <p><?php echo Lang::t('register', 'This information will help you find your friends on Plun.'); ?></p>
    <?php
    	$form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmFillOut',
                    'action' => Yii::app()->createUrl('/register/stepUpdateProfile'),
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
    ?>
    <!-- step 1 -->
    <div class="block-step update_profile_1 update_profile_step1">
    	<h3><?php echo Lang::t('register', 'Basic Info'); ?></h3>
      <table width="" border="0" cellspacing="0" cellpadding="0">
          <tr>
                    <td class="title"><?php echo Lang::t('settings', 'You\'re From'); ?>:</td>
                    <td>
                    	<table style="width:400px;" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td style="padding-left:0;">
                             <div class="select_style w160">
								<?php
                    	       		$list_country = CHtml::listData($list_country, 'id', 'name');
                    	            echo CHtml::dropDownList('UsrProfileSettings[country_id]', $country_id, $list_country, array('onchange' => 'getStateRegister();', 'name' => 'txt-country','id' => 're-country','class' => 're-country virtual_form', 'text' => 'country_register_text'));
                    	        ?>
                                <span class="txt_select"><span class="country_register_text"><?php echo $list_country[$country_id]; ?></span></span> <span class="btn_combo_down"></span>
                            </div>
                            </td>
                            <?php 
                            	if($list_state){
                                	$state_none	=	'';
                                }else{
                                    $state_none	=	' style="display: none;"';
                                }
                            ?>
                            <td class="rs-state"<?php echo $state_none; ?>>
								<?php if($list_state): ?>
								<div class="select_style w160">
								  <?php
									$list_state = CHtml::listData($list_state, 'id', 'name');
									echo CHtml::dropDownList('UsrProfileSettings[state_id]',$state_id, $list_state, array('name' => 'txt-state', 'id' => 're-state', 'onchange' => "getCityRegister();",'class' => 're-state virtual_form', 'text' => 'state_register_text','empty' => Lang::t('search', '--Any--'))); 
									?>
								  <span class="txt_select"><span class="state_register_text"><?php echo $list_state[$state_id]; ?></span></span> <span class="btn_combo_down"></span> </div>
								<?php endif; ?>
                            </td>
                          </tr>
                          <tr>
                            <td style="padding-left:0;" class="rs-city">
														<?php if(sizeof($list_city)): ?>
														<div class="select_style w160">
									                        <?php 
									                        	$list_city = CHtml::listData($list_city, 'id', 'name');
									                        	echo CHtml::dropDownList('UsrProfileSettings[city_id]',$city_id, $list_city, array('name' => 'txt-city', 'id' => 're-city', 'onchange' => "getDistrictRegister();",'class' => 're-city virtual_form', 'text' => 'city_register_text', 'empty' => Lang::t('search', '--Any--'))); 
									                        ?>    
									                        <span class="txt_select"><span class="city_register_text"><?php echo $list_city[$city_id]; ?></span></span> <span class="btn_combo_down"></span>
								                        </div>      
								                        <?php endif; ?>  
                            </td>
                            <td class="rs-district">
														<?php if(sizeof($list_district)): ?>
														<div class="select_style w160">
									                        <?php 
									                        	$list_district = CHtml::listData($list_district, 'id', 'name');
									                        	echo CHtml::dropDownList('UsrProfileSettings[district_id]',$district_id, $list_district, array('name' => 'txt-district', 'id' => 're-district', 'class' => 're-district virtual_form', 'text' => 'district_register_text', 'empty' => Lang::t('search', '--Any--'))); 
									                        ?>   
									                        <span class="txt_select"><span class="district_register_text"><?php echo $list_district[$district_id]; ?></span></span> <span class="btn_combo_down"></span>
								                        </div> 
								                        <?php endif; ?>
                            </td>
                          </tr>
                        </table>
                    </td>
        </tr>      
        <tr>
          <td class="title"><?php echo Lang::t('settings', 'Sexuality'); ?>:</td>
          <td><div class="select_style w120">
              <?php
	          	$sexuality	=	ProfileSettingsConst::getSexualityLabel();
                $sexuality_selected	=	isset($sexuality[$model->sexuality])	?	$sexuality[$model->sexuality]	:	$sexuality[ProfileSettingsConst::SEXUALITY_GAY];
	            echo $form->dropDownList($model, 'sexuality', $sexuality, array('class' => 'virtual_form', 'text' => 'sexuality_text'));                                
	          ?>
              <span class="txt_select"><span class="sexuality_text"><?php echo $sexuality_selected; ?></span></span> <span class="btn_combo_down"></span> </div></td>
        </tr>
        <tr>
          <td class="title"><?php echo Lang::t('settings', 'Role'); ?>:</td>
          <td><div class="select_style w120">
              <?php
	                                $role	=	ProfileSettingsConst::getSexRoleLabel();
	                                $model->sex_role	=	!empty($model->sex_role)	?	$model->sex_role	:	ProfileSettingsConst::SEXROLE_TOP;
                            		$role_selected	=	isset($role[$model->sex_role])	?	$role[$model->sex_role]	:	$role[ProfileSettingsConst::SEXROLE_TOP];
	                           		echo $form->dropDownList($model, 'sex_role', $role, array('class' => 'virtual_form', 'text' => 'sex_role_text'));                                
	                            ?>
              <span class="txt_select"><span class="sex_role_text"><?php echo $role_selected; ?></span></span> <span class="btn_combo_down"></span> </div></td>
        </tr>
        <tr>
          <td class="title"><?php echo Lang::t('settings', 'Relationship Status'); ?>:</td>
          <td><div class="select_style w165">
              <?php
                                	$relationship	=	ProfileSettingsConst::getRelationshipLabel();
                                	$model->relationship	=	!empty($model->relationship)	?	$model->relationship	:	ProfileSettingsConst::RELATIONSHIP_SINGLE;
                            		$relationship_selected	=	isset($relationship[$model->relationship])	?	$relationship[$model->relationship]	:	$relationship[ProfileSettingsConst::RELATIONSHIP_SINGLE];
                                	echo $form->dropDownList($model, 'relationship', $relationship, array('class' => 'virtual_form', 'text' => 'relationship_text'));
                                ?>
              <span class="txt_select"><span class="relationship_text"><?php echo $relationship_selected; ?></span></span> <span class="btn_combo_down"></span> </div></td>
        </tr>
        <tr>
          <td class="title" align="left" valign="top"><?php echo Lang::t('settings', 'Looking for'); ?>:</td>
          <td><?php 
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
            <div class="squaredCheck"> <?php echo CHtml::CheckBox('looking_for', $checked, $checkbox_looking_for); ?>
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
            <div class="squaredCheck"> <?php echo CHtml::CheckBox('languages', $checked, $checkbox_languages); ?>
              <label for="languages_<?php echo $key; ?>"></label>
              <label for="languages_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
            </div>
            <?php endforeach; ?>
            </td>
        </tr>
        <tr>
          <td class="title"><?php echo Lang::t('settings', 'Ethnicity'); ?>:</td>
          <td><div class="select_style w160">
              <?php 
                            	$ethnicity	=	ProfileSettingsConst::getEthnicityLabel();
	                            $model->ethnic_id	=	!empty($model->ethnic_id)	?	$model->ethnic_id	:	ProfileSettingsConst::ETHNICITY_ASIAN;
                            	$ethnicity_selected	=	isset($ethnicity[$model->ethnic_id])	?	$ethnicity[$model->ethnic_id]	:	$ethnicity[ProfileSettingsConst::ETHNICITY_ASIAN];
                            	echo $form->dropDownList($model, 'ethnic_id', $ethnicity, array('class' => 'virtual_form', 'text' => 'ethnicity_text')); 
                            ?>
              <span class="txt_select"><span class="ethnicity_text"><?php echo $ethnicity_selected; ?></span></span> <span class="btn_combo_down"></span> </div></td>
        </tr>
        <tr>
          <td class="title"><?php echo Lang::t('settings', 'Horoscope'); ?>:</td>
          <td><div class="select_style w160">
              <?php 
                            	$horoscope	=	ProfileSettingsConst::getHoroscopeLabel();
                            	$model->horoscope	=	!empty($model->horoscope)	?	$model->horoscope	:	ProfileSettingsConst::HOROSCOPE_AQUARIUS;
                            	$horoscope_selected	=	isset($horoscope[$model->horoscope])	?	$horoscope[$model->horoscope]	:	$horoscope[ProfileSettingsConst::HOROSCOPE_AQUARIUS];
                            	echo $form->dropDownList($model, 'horoscope', $horoscope, array('class' => 'virtual_form', 'text' => 'horoscope_text')); ?>
              <span class="txt_select"><span class="horoscope_text"><?php echo $horoscope_selected; ?></span></span> <span class="btn_combo_down"></span> </div></td>
        </tr>
        <tr>
                    <td class="title">&nbsp;</td>
                    <td align="right">                    	
                        <a onclick="change_step_register('update_profile_step1', 'update_profile_step2');" title="" href="javascript:void(0);" class="btn btn-white"><?php echo Lang::t('settings', 'Next'); ?></a> 
                    </td>
        </tr>
      </table>
    </div>
    <!-- end step 1 --> 
    
    <!--  step 2 -->
    <div class="block-step update_profile_1  update_profile_step2" style="display: none;">
    	<h3><?php echo Lang::t('settings', 'What you see'); ?></h3>
            	<table width="" border="0" cellspacing="0" cellpadding="0">   
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Height'); ?>:</td>
                    <td>
                        <?php echo $form->textField($model, 'height', array('class' => 'input_setting w60','placeholder' => Lang::t('settings', 'Enter text'))); ?>
                        <div class="select_style w60">
                        	<?php 
                        		$unit_height	=	array('0' => 'cm', '1' => 'm');
	                            echo $form->dropDownList($model, 'unit_height', $unit_height, array('class' => 'virtual_form', 'text' => 'unit_height_text'));
                            ?>
                            <span class="txt_select"><span class="unit_height_text"><?php echo $unit_height[$model->unit_height]; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr> 
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Weight'); ?>:</td>
                    <td>
                        <?php echo $form->textField($model, 'weight', array('class' => 'input_setting w60','placeholder' => Lang::t('settings', 'Enter text'))); ?>
                        <div class="select_style w60">
                        	<?php 
                        		$unit_weight	=	array('0' => 'kg','1' => 'pound');
	                            echo $form->dropDownList($model, 'unit_weight', $unit_weight, array('class' => 'virtual_form', 'text' => 'unit_weight_text'));
                            ?>
                            <span class="txt_select"><span class="unit_weight_text"><?php echo $unit_weight[$model->unit_weight]; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                             	        	
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Build'); ?>:</td>
                    <td>
                        <div class="select_style w230">
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
                        <div class="select_style w230">
	                            <?php
	                                $body_hair	=	ProfileSettingsConst::getBodyHairLabel();
	                                $model->body_hair	=	!empty($model->body_hair)	?	$model->body_hair	:	ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY;
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
                    	<div class="select_style w230">
                                <?php
                                	$tattoos	=	ProfileSettingsConst::getTattoosLabel();
                                	$model->tattoo	=	!empty($model->tattoo)	?	$model->tattoo	:	ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY;
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
                    	<div class="select_style w230">
                                <?php
                                	$piercings	=	ProfileSettingsConst::getPiercingsLabel();
                                	$model->piercings	=	!empty($model->piercings)	?	$model->piercings	:	ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY;
                            		$piercings_selected	=	isset($piercings[$model->piercings])	?	$piercings[$model->piercings]	:	$piercings[ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'piercings', $piercings, array('class' => 'virtual_form', 'text' => 'piercings_text'));
                                ?>
                            <span class="txt_select"><span class="piercings_text"><?php echo $piercings_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr> 
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', '"Shoe" Size'); ?>:</td>
                    <td>
                    	<div class="select_style w230">
                                <?php
                                	$dick_size	=	ProfileSettingsConst::getShoeSizeLabel();
                                	$model->dick_size	=	!empty($model->dick_size)	?	$model->dick_size	:	ProfileSettingsConst::SHOESIZE_PREFER_NOTTOSAY;
                            		$dick_size_selected	=	isset($dick_size[$model->dick_size])	?	$dick_size[$model->dick_size]	:	$dick_size[ProfileSettingsConst::SHOESIZE_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'dick_size', $dick_size, array('class' => 'virtual_form', 'text' => 'dick_size_text'));
                                ?>
                            <span class="txt_select"><span class="dick_size_text"><?php echo $dick_size_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>   
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Cut'); ?>:</td>
                    <td>
                    	<div class="select_style w230">
                                <?php
                                	$cut	=	ProfileSettingsConst::getCutLabel();
                                	$model->cut	=	!empty($model->cut)	?	$model->cut	:	ProfileSettingsConst::CUT_PREFER_NOTTOSAY;
                            		$cut_selected	=	isset($cut[$model->cut])	?	$cut[$model->cut]	:	$cut[ProfileSettingsConst::CUT_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'cut', $cut, array('class' => 'virtual_form', 'text' => 'cut_text'));
                                ?>
                            <span class="txt_select"><span class="cut_text"><?php echo $cut_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>    
                   <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Occupation'); ?>:</td>
                    <td>
                    	<div class="select_style w230">
                    		    <?php
	                            	$occupation	=	ProfileSettingsConst::getOccupationLabel();
	                            	$model->occupation	=	!empty($model->occupation)	?	$model->occupation	:	ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY;
                            		$occupation_selected	=	isset($occupation[$model->occupation])	?	$occupation[$model->occupation]	:	$occupation[ProfileSettingsConst::OCCUPATION_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'occupation', $occupation, array('class' => 'virtual_form', 'text' => 'occupation_text'));                                
	                            ?>
                            <span class="txt_select"><span class="occupation_text"><?php echo $occupation_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr> 
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Religion'); ?>:</td>
                    <td>
                    	<div class="select_style w230">
                    		    <?php
	                            	$religion	=	ProfileSettingsConst::getReligionLabel();
	                            	$model->religion	=	!empty($model->religion)	?	$model->religion	:	ProfileSettingsConst::RELIGION_ATHEIST;
                            		$religion_selected	=	isset($religion[$model->religion])	?	$religion[$model->religion]	:	$religion[ProfileSettingsConst::RELIGION_ATHEIST];
	                           		echo $form->dropDownList($model, 'religion', $religion, array('class' => 'virtual_form', 'text' => 'religion_text'));                                
	                            ?>
                            <span class="txt_select"><span class="religion_text"><?php echo $religion_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                                                                   
				  <tr>
                    <td class="title">&nbsp;</td>
                    <td align="right">                    	
                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="change_step_register('update_profile_step2', 'update_profile_step1');"><?php echo Lang::t('settings', 'Back'); ?></a> 
                        <a class="btn btn-white" href="javascript:void(0);" title="" onclick="change_step_register('update_profile_step2', 'update_profile_step3');"><?php echo Lang::t('settings', 'Next'); ?></a> 
                    </td>
                  </tr>
                </table>    
    </div>
    <!--  end step 2 --> 
    <!--  step 3 -->
    <div class="block-step update_profile_1  update_profile_step3" style="display: none;">
      <form class="frmFillOut" method="post" action="#">
        <h3><?php echo Lang::t('settings', 'Extra'); ?></h3>
        <table width="" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td valign="top" align="left" class="title"><?php echo Lang::t('settings', 'Attributes'); ?>:</td>
              <td colspan="3">
					    <?php 
                    		$my_attributes	=	ProfileSettingsConst::getAttributesLabel(); 
                    		$my_attributes_selected	=	!empty($model->my_attributes)		?	explode(',', $model->my_attributes) 	:	array();
                    		foreach($my_attributes AS $key => $value): 
                        		$checkbox_attributes     =    array(
                        		        'value'     => $key,
                        		        'class'     => 'my_attributes',
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
                            <label for="my_attributes_<?php echo $key; ?>"></label>
                            <label for="my_attributes_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?>              
              </td>
            </tr>
            <tr>
              <td class="title"><?php echo Lang::t('settings', 'Mannerism'); ?>:</td>
              <td colspan="3"><table class="table_small">
                  <tr>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$mannerism	=	ProfileSettingsConst::getMannerismLabel();
	                            	$model->mannerism	=	!empty($model->mannerism)	?	$model->mannerism	:	ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY;
                            		$mannerism_selected	=	isset($mannerism[$model->mannerism])	?	$mannerism[$model->mannerism]	:	$mannerism[ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'mannerism', $mannerism, array('class' => 'virtual_form', 'text' => 'mannerism_text'));                                
	                        ?>
                            <span class="txt_select"><span class="mannerism_text"><?php echo $mannerism_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                    <td class="title" align="left" width="105"><?php echo Lang::t('settings', 'Smoke'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$smoke	=	ProfileSettingsConst::getSmokeLabel();
	                            	$model->smoke	=	!empty($model->smoke)	?	$model->smoke	:	ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY;
                            		$smoke_selected	=	isset($smoke[$model->smoke])	?	$smoke[$model->smoke]	:	$smoke[ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'smoke', $smoke, array('class' => 'virtual_form', 'text' => 'smoke_text'));                                
	                        ?>
                            <span class="txt_select"><span class="smoke_text"><?php echo $smoke_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td class="title"><?php echo Lang::t('settings', 'Drink'); ?>:</td>
              <td colspan="3"><table class="table_small">
                  <tr>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$drink	=	ProfileSettingsConst::getDrinkLabel();
	                            	$model->drink	=	!empty($model->drink)	?	$model->drink	:	ProfileSettingsConst::DRINK_PREFER_NOTTOSAY;
                            		$drink_selected	=	isset($drink[$model->drink])	?	$drink[$model->drink]	:	$drink[ProfileSettingsConst::DRINK_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'drink', $drink, array('class' => 'virtual_form', 'text' => 'drink_text'));                                
	                        ?>
                            <span class="txt_select"><span class="drink_text"><?php echo $drink_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                    <td class="title" align="left" width="105"><?php echo Lang::t('settings', 'Safe sex'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$safe_sex	=	ProfileSettingsConst::getSafeSexLabel();
	                            	$model->safer_sex	=	!empty($model->safer_sex)	?	$model->safer_sex	:	ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY;
                            		$safe_sex_selected	=	isset($safe_sex[$model->safer_sex])	?	$safe_sex[$model->safer_sex]	:	$safe_sex[ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'safer_sex', $safe_sex, array('class' => 'virtual_form', 'text' => 'safer_sex_text'));                                
	                        ?>
                            <span class="txt_select"><span class="safer_sex_text"><?php echo $safe_sex_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                </table></td>
            </tr>
            <tr> </tr>
            <tr>
              <td class="title"><?php echo Lang::t('settings', 'Club'); ?>:</td>
              <td colspan="3"><table class="table_small">
                  <tr>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$club	=	ProfileSettingsConst::getClubLabel();
	                            	$model->club	=	!empty($model->club)	?	$model->club	:	ProfileSettingsConst::CLUB_PREFER_NOTTOSAY;
                            		$club_selected	=	isset($club[$model->club])	?	$club[$model->club]	:	$club[ProfileSettingsConst::CLUB_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'club', $club, array('class' => 'virtual_form', 'text' => 'club_text'));                                
	                        ?>
                            <span class="txt_select"><span class="club_text"><?php echo $club_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                    <td width="105" class="title"><?php echo Lang::t('settings', 'How Out Are You?'); ?></td>
                    <td>
                    
                    	<div class="select_style w160">
                    	   <?php
	                            	$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
	                            	$model->public_information	=	!empty($model->public_information)	?	$model->public_information	:	ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY;
                            		$public_information_selected	=	isset($public_information[$model->public_information])	?	$public_information[$model->public_information]	:	$public_information[ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'public_information', $public_information, array('class' => 'virtual_form', 'text' => 'public_information_text'));                                
	                        ?>
                            <span class="txt_select"><span class="public_information_text"><?php echo $public_information_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
						
						</td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td class="title"><?php echo Lang::t('settings', 'I Live With'); ?>:</td>
              <td colspan="3">
              
                    	<div class="select_style w160">
                    	   <?php
	                            	$live_with	=	ProfileSettingsConst::getLiveWithLabel();
	                            	$model->live_with	=	!empty($model->live_with)	?	$model->live_with	:	ProfileSettingsConst::LIVE_WITH_NOBODY;
                            		$live_with_selected	=	isset($live_with[$model->live_with])	?	$live_with[$model->live_with]	:	$live_with[ProfileSettingsConst::LIVE_WITH_NOBODY];
	                           		echo $form->dropDownList($model, 'live_with', $live_with, array('class' => 'virtual_form', 'text' => 'live_with_text'));                                
	                        ?>
                            <span class="txt_select"><span class="live_with_text"><?php echo $live_with_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                  
                  </td>
            </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Hobbies'); ?>:</td>
                    <td>
                    	<div class="input-type-4 hobbies_tag" onclick="$(this).find('input').focus();">
								<?php if(!empty($model->hobbies)):
									$hobbies	=	json_decode($model->hobbies, true);
								?>
								<?php foreach($hobbies AS $row):  ?>
								<a title="<?php echo $row; ?>" href="javascript:void(0);"><label class="text"><?php echo $row; ?></label><span class="p" onclick="$(this).parent().remove();">X</span></a>							
								<?php endforeach; ?>
								<?php endif; ?>
                            	<input type="text" id="txt_hobbies_input" onblur="collect_hobbies();" class="city acfb-input ac_input" autocomplete="off" style="padding : 0px; border: none; display: block; float: left; width: 80px; margin-top: 5px;">
					    </div>
                        <!--
                    	<textarea name="" cols="" rows=""></textarea>
                    	-->
                    </td>
                  </tr>
            <tr>
              <td class="title">&nbsp;</td>
              <td align="right">
              	<a onclick="change_step_register('update_profile_step3', 'update_profile_step2');" title="" href="javascript:void(0);" class="btn btn-white"><?php echo Lang::t('settings', 'Back'); ?></a> 
              	<a onclick="change_step_register('update_profile_step3', 'update_profile_step4');" title="" href="javascript:void(0);" class="btn btn-white"><?php echo Lang::t('settings', 'Next'); ?></a></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
    <!--  end step 3 --> 
    <!-- step 4 -->
    <div class="block-step update_profile_1  update_profile_step4" style="display: none;">
      <form class="frmFillOut" method="post" action="#">
        <h3><?php echo Lang::t('settings', 'Extra'); ?></h3>
        <table width="" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td class="title"><?php echo Lang::t('settings', 'My Types'); ?>:</td>
              <td>
                                     <?php 
                    		$my_types	=	ProfileSettingsConst::getMyTypesLabel(); 
                    		$my_types_selected	=	!empty($model->my_types)		?	explode(',', $model->my_types) 	:	array();
                    		foreach($my_types AS $key => $value): 
                        		$checkbox_types     =    array(
                        		        'value'     => $key,
                        		        'class'     => 'my_types',
                        		        'id'        =>    'my_types_' . $key
                        		);
                        		if(in_array($key, $my_types_selected)){
                                    $checked    =    true;
                                }else{
                                    $checked    =    false;
                                }
                    	?>
                    	<div class="squaredCheck">
                    	    <?php echo CHtml::CheckBox('my_types', $checked, $checkbox_types); ?>
                            <label for="my_types_<?php echo $key; ?>"></label>
                            <label for="my_types_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?>
                  </td>
            </tr>
            <tr>
              <td class="title" width="74"><?php echo Lang::t('settings', 'Stuff I\'m Into'); ?>:</td>
              <td style="padding-right:0;">
                         <?php 
                    		$my_stuff	=	ProfileSettingsConst::getStuffLabel();
                    		$my_stuff_selected	=	!empty($model->into_stuff)		?	explode(',', $model->into_stuff) 	:	array();
                    		foreach($my_stuff AS $key => $value): 
                        		$checkbox_stuff    =    array(
                        		        'value'     => $key,
                        		        'class'     => 'my_stuff',
                        		        'id'        =>    'my_stuff_' . $key
                        		);
                        		if(in_array($key, $my_stuff_selected)){
                                    $checked    =    true;
                                }else{
                                    $checked    =    false;
                                }
                    	?>
                    	<div class="squaredCheck">
                    	    <?php echo CHtml::CheckBox('my_stuff', $checked, $checkbox_stuff); ?>
                            <label for="my_stuff_<?php echo $key; ?>"></label>
                            <label for="my_stuff_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?>
               </td>
            </tr>
            <tr>
              <td class="title">&nbsp;</td>
              <td align="right"><a onclick="change_step_register('update_profile_step4', 'update_profile_step3');" title="" href="javascript:void(0);" class="btn btn-white"><?php echo Lang::t('settings', 'Back'); ?></a> 
              <a onclick="save_fill_out();" title="" href="javascript:void(0);" class="btn btn-violet"><?php echo Lang::t('settings', 'Save'); ?></a></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
    <!--  end step 4 --> 
    <?php $this->endWidget(); ?>
  </div>
</div>
