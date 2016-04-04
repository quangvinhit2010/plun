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
    <p class="title left"><a title="Back" href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" class="link_back"></a><span><?php echo Lang::t('settings', 'Languages'); ?></span></p>
    <ul>
      <li><b><?php echo Lang::t('settings', 'Language Preference'); ?>:</b></li>
      <li>
        <div class="select_style">
		  <?php
		 		$list_languages = CHtml::listData($list_languages, 'id', 'title');
		        echo CHtml::dropDownList('UsrProfileSettings[default_language]', $model->default_language, $list_languages, array('name' => 'txt-languages','id' => 'UsrProfileSettings_default_language','class' => 'virtual_form', 'text' => 'default_language'));
		   ?>          
          <span class="txt_select default_language"> <span class="select_city"><?php echo isset($list_languages[$model->default_language])	?	$list_languages[$model->default_language]	:	''; ?></span> </span> <span class="btn_combo_down"></span> </div>
      </li>
      <li class="but_func"><a class="but active" href="javascript:void(0);" onClick="saveDefaultLanguage();"><?php echo Lang::t('settings', 'Save'); ?></a> <a href="<?php echo $this->usercurrent->createUrl('settings/index'); ?>" class="but"><?php echo Lang::t('settings', 'Discard'); ?></a></li>
    </ul>
  </div>
</div>
                
                
                    <?php $this->endWidget(); ?>