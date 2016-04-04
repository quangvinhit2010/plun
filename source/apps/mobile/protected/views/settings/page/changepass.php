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
  <div class="setting_gen setting_save_change_pass">
    <p class="title left"><a title="Back" href="<?php echo $this->usercurrent->createUrl('//settings/index');?>" class="link_back"></a><span><?php echo Lang::t('settings', 'Change Email & Password'); ?></span></p>
    <ul>
      <li><b><?php echo Lang::t('settings', 'Your Email Address'); ?>:</b></li>
      <li>
        <?php echo $form->textField($model_profile, 'email', array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
      </li>
      <li><b><?php echo Lang::t('settings', 'Your Current Password'); ?>:</b></li>
      <li>
      	<?php echo CHtml::passwordField('password', null,  array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
      </li>
      <li><b><?php echo Lang::t('settings', 'Your New Password'); ?>:</b></li>
      <li>
        <?php echo CHtml::passwordField('new_password', null,  array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
      </li>
      <li><b><?php echo Lang::t('settings', 'Re-type new Password'); ?>:</b></li>
      <li>
        <?php echo CHtml::passwordField('re_new_password', null,  array('placeholder' => Lang::t('settings', 'Enter text'))); ?>
      </li>
      <li class="but_func"><a class="but active" href="javascript:void(0);" onClick="save_account_settings();"><?php echo Lang::t('settings', 'Save'); ?></a> <a href="<?php echo $this->usercurrent->createUrl('settings/index'); ?>" class="but"><?php echo Lang::t('settings', 'Discard'); ?></a></li>
    </ul>
  </div>
</div>
                                
<?php $this->endWidget(); ?>