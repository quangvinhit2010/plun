<div class="left setting_col1 setting_whatyousee">                 
	<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'frmSaveSettings',
                    'action' => $this->user->createUrl('/settings/save'),
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                        ));
                ?>            
            	<table width="" border="0" cellspacing="0" cellpadding="0">   
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Height'); ?>:</td>
                    <td>
                        <div class="select_style w60">
                        	<?php 
                        		$unit_height	=	array();
                        		if($model->measurement == 1){
									$unit_height	=	UnitHelper::getCmList();
                        		}else{
								 	$unit_height	=	UnitHelper::getFeetList();                       			
                        		}
	                            echo $form->dropDownList($model, 'height', $unit_height, array('class' => 'virtual_form', 'text' => 'height_text', 'empty' => Lang::t('search', '--Any--')));
                            ?>
                            <span class="txt_select"><span class="height_text"><?php echo (isset ($unit_height["$model->height"])	?	$model->height	. " $height_unit_label" :	Lang::t('search', '--Any--')); ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                        
                        <div class="select_style w60">
                        	<?php 
                        		$unit_height	=	array('1' => 'cm', '2' => 'ft');
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
                    <td class="title"><?php echo Lang::t('settings', '"Shoe" Size'); ?>:</td>
                    <td>
                    	<div class="select_style w160">
                                <?php
                                	$dick_size	=	ProfileSettingsConst::getShoeSizeLabel();
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
                    	<div class="select_style w160">
                                <?php
                                	$cut	=	ProfileSettingsConst::getCutLabel();
                            		$cut_selected	=	isset($cut[$model->cut])	?	$cut[$model->cut]	:	$cut[ProfileSettingsConst::CUT_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'cut', $cut, array('class' => 'virtual_form', 'text' => 'cut_text'));
                                ?>
                            <span class="txt_select"><span class="cut_text"><?php echo $cut_selected; ?></span></span> <span class="btn_combo_down"></span>
                        </div>
                    </td>
                  </tr>                                     
                  <tr>
                    <td class="title">&nbsp;</td>
                    <td align="right">                    	
                        <a class="btn btn-violet" href="javascript:void(0);" title="<?php echo Lang::t('settings', 'Save Settings'); ?>" onClick="save_settings_whatyousee();"><?php echo Lang::t('settings', 'Save Settings'); ?></a>
                    </td>
                  </tr>
                </table>
                <?php $this->endWidget(); ?>
</div>
<div class="left setting_col2">
</div>