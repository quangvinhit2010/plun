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
  <div class="setting_gen setting_language">
    <p class="title left"><a title="Back" href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" class="link_back"></a><span><?php echo Lang::t('settings', 'What you see'); ?></span></p>
    <ul class="whatyousee">
      <li><b><?php echo Lang::t('settings', 'Height'); ?>:</b></li>
	  <li>
      	<div class="left">
      		<?php //echo $form->textField($model, 'height', array('class' => 'w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
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
      	<div class="left">
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
          <span class="txt_select user_looking_textselect"> <span class="select_city body_text"><?php echo $build_selected; ?></span> </span> <span class="btn_combo_down"></span> 
         </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Body Hair'); ?>:</b></li>
      <li>
        <div class="select_style">
	                            <?php
	                                $body_hair	=	ProfileSettingsConst::getBodyHairLabel();
                            		$body_hair_selected	=	isset($body_hair[$model->body_hair])	?	$body_hair[$model->body_hair]	:	$body_hair[ProfileSettingsConst::BODYHAIR_PREFER_NOTTOSAY];
	                           		echo $form->dropDownList($model, 'body_hair', $body_hair, array('class' => 'virtual_form', 'text' => 'body_hair_text'));                                
	                            ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city body_hair_text"><?php echo $body_hair_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Tattoos'); ?>:</b></li>
      <li>
        <div class="select_style">
                                <?php
                                	$tattoos	=	ProfileSettingsConst::getTattoosLabel();
                            		$tattoos_selected	=	isset($tattoos[$model->tattoo])	?	$tattoos[$model->tattoo]	:	$tattoos[ProfileSettingsConst::TATTOOS_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'tattoo', $tattoos, array('class' => 'virtual_form', 'text' => 'tattoo_text'));
                                ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city tattoo_text"><?php echo $tattoos_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li><b><?php echo Lang::t('settings', 'Piercings'); ?>:</b></li>
      <li>
        <div class="select_style">
                                <?php
                                	$piercings	=	ProfileSettingsConst::getPiercingsLabel();
                            		$piercings_selected	=	isset($piercings[$model->piercings])	?	$piercings[$model->piercings]	:	$piercings[ProfileSettingsConst::PIERCINGS_PREFER_NOTTOSAY];
                                	echo $form->dropDownList($model, 'piercings', $piercings, array('class' => 'virtual_form', 'text' => 'piercings_text'));
                                ?>
          <span class="txt_select user_looking_textselect"> <span class="select_city piercings_text"><?php echo $piercings_selected; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li class="but_func"><a href="javascript:void(0);" class="but active" onclick="save_settings_whatyousee();"><?php echo Lang::t('settings', 'Save'); ?></a> <a href="<?php echo $this->usercurrent->createUrl('settings/index'); ?>" class="but"><?php echo Lang::t('settings', 'Discard'); ?></a></li>
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