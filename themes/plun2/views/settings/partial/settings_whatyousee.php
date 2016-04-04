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
                        <?php //echo $form->textField($model, 'height', array('class' => 'input_setting w60','placeholder' => Lang::t('settings', 'Enter text'))); ?>
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
	                            echo $form->dropDownList($model, 'unit_height', $opt_unit_height, array('class' => 'virtual_form', 'text' => 'unit_height_text', 'onchange' => 'changeUnit(this);'));
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
	                            echo $form->dropDownList($model, 'unit_weight', $opt_unit_weight, array('class' => 'virtual_form', 'text' => 'unit_weight_text', 'onchange' => 'changeUnit(this);'));
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
                    <td class="title">&nbsp;</td>
                    <td align="right">                    	
                        <a class="btn btn-violet" href="javascript:void(0);" title="<?php echo Lang::t('settings', 'Save Settings'); ?>" onClick="save_settings_whatyousee();"><?php echo Lang::t('settings', 'Save Settings'); ?></a>
                    </td>
                  </tr>
                </table>
                <?php echo $form->hiddenField($model,'measurement'); ?>
                
                <?php $this->endWidget(); ?>
</div>
<div class="left setting_col2">
</div>

<div class="list_unit_temp" style="display: none;">
	<?php 
        echo CHtml::dropDownList('height', false, UnitHelper::getCmList(), array('class' => 'unit_height_cm', 'empty' => Lang::t('search', '--Any--')));
        echo CHtml::dropDownList('height', false, UnitHelper::getFeetList(), array('class' => 'unit_height_ft', 'empty' => Lang::t('search', '--Any--')));
        echo CHtml::dropDownList('weight', false, UnitHelper::getKgList(), array('class' => 'unit_weight_kg', 'empty' => Lang::t('search', '--Any--')));
        echo CHtml::dropDownList('weight', false, UnitHelper::getLbsList(), array('class' => 'unit_weight_lbs', 'empty' => Lang::t('search', '--Any--')));
   
        
    ?>
</div>

