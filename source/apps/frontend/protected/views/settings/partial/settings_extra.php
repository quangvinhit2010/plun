	<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => $this->user->createUrl('/settings/save'),
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                        ));
                ?> 
<div class="left setting_col1 setting_extra">               
            	<table width="" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Occupation'); ?>:</td>
                    <td>
                    	<div class="select_style w230">
                    		    <?php
	                            	$occupation	=	ProfileSettingsConst::getOccupationLabel();
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
                            		$religion_selected	=	isset($religion[$model->religion])	?	$religion[$model->religion]	:	$religion[ProfileSettingsConst::RELIGION_ATHEIST];
	                           		echo $form->dropDownList($model, 'religion', $religion, array('class' => 'virtual_form', 'text' => 'religion_text'));                                
	                            ?>
                            <span class="txt_select"><span class="religion_text"><?php echo $religion_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                  
                  <tr>
                    <td class="title" align="left" valign="top"><?php echo Lang::t('settings', 'Attributes'); ?>:</td>
                    <td>
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
                    	    <?php echo CHtml::CheckBox('my_attributes[]', $checked, $checkbox_attributes); ?>
                            <label for="my_attributes_<?php echo $key; ?>"></label>
                            <label for="my_attributes_<?php echo $key; ?>" class="mar_left_24"><?php echo $value; ?></label>
                        </div>
                        <?php endforeach; ?>

                    </td>
                  </tr>
                  
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Mannerism'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$mannerism	=	ProfileSettingsConst::getMannerismLabel();
                            		$mannerism_selected	=	isset($mannerism[$model->mannerism])	?	$mannerism[$model->mannerism]	:	$mannerism[ProfileSettingsConst::MANNERISM_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'mannerism', $mannerism, array('class' => 'virtual_form', 'text' => 'mannerism_text'));                                
	                        ?>
                            <span class="txt_select"><span class="mannerism_text"><?php echo $mannerism_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>            
                                          
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Smoke'); ?></td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$smoke	=	ProfileSettingsConst::getSmokeLabel();
                            		$smoke_selected	=	isset($smoke[$model->smoke])	?	$smoke[$model->smoke]	:	$smoke[ProfileSettingsConst::SMOKE_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'smoke', $smoke, array('class' => 'virtual_form', 'text' => 'smoke_text'));                                
	                        ?>
                            <span class="txt_select"><span class="smoke_text"><?php echo $smoke_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Drink'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$drink	=	ProfileSettingsConst::getDrinkLabel();
                            		$drink_selected	=	isset($drink[$model->drink])	?	$drink[$model->drink]	:	$drink[ProfileSettingsConst::DRINK_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'drink', $drink, array('class' => 'virtual_form', 'text' => 'drink_text'));                                
	                        ?>
                            <span class="txt_select"><span class="drink_text"><?php echo $drink_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Safe sex'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$safe_sex	=	ProfileSettingsConst::getSafeSexLabel();
                            		$safe_sex_selected	=	isset($safe_sex[$model->safer_sex])	?	$safe_sex[$model->safer_sex]	:	$safe_sex[ProfileSettingsConst::SAFESEX_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'safer_sex', $safe_sex, array('class' => 'virtual_form', 'text' => 'safer_sex_text'));                                
	                        ?>
                            <span class="txt_select"><span class="safer_sex_text"><?php echo $safe_sex_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                  
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Club'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$club	=	ProfileSettingsConst::getClubLabel();
                            		$club_selected	=	isset($club[$model->club])	?	$club[$model->club]	:	$club[ProfileSettingsConst::CLUB_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'club', $club, array('class' => 'virtual_form', 'text' => 'club_text'));                                
	                        ?>
                            <span class="txt_select"><span class="club_text"><?php echo $club_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title" width="105"><?php echo Lang::t('settings', 'How Out Are You?'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$public_information	=	ProfileSettingsConst::getPublicInformationLabel();
                            		$public_information_selected	=	isset($public_information[$model->public_information])	?	$public_information[$model->public_information]	:	$public_information[ProfileSettingsConst::PUBLIC_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'public_information', $public_information, array('class' => 'virtual_form', 'text' => 'public_information_text'));                                
	                        ?>
                            <span class="txt_select"><span class="public_information_text"><?php echo $public_information_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="title"><?php echo Lang::t('settings', 'I Live With'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                    	   <?php
	                            	$live_with	=	ProfileSettingsConst::getLiveWithLabel();
                            		$live_with_selected	=	isset($live_with[$model->live_with])	?	$live_with[$model->live_with]	:	$live_with[ProfileSettingsConst::LIVE_WITH_NOBODY];
	                           		echo $form->dropDownList($model, 'live_with', $live_with, array('class' => 'virtual_form', 'text' => 'live_with_text'));                                
	                        ?>
                            <span class="txt_select"><span class="live_with_text"><?php echo $live_with_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                   
                  <tr>
                    <td class="title"> </td>
                    <td align="left">                    	
                        <a class="btn btn-violet" href="javascript:void(0);" title="<?php echo Lang::t('settings', 'Save Settings'); ?>" onClick="save_settings_extra();"><?php echo Lang::t('settings', 'Save Settings'); ?></a>
                    </td>
                  </tr>                                             
                </table>
</div>
<?php $this->endWidget(); ?>