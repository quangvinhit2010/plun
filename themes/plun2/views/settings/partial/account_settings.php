<div class="left setting_col1">                 
                       <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'frmSaveSettings',
                            'action' => $this->user->createUrl('/settings/SaveAccountSettings'),
                            'enableClientValidation' => true,
                            'enableAjaxValidation' => true,
                            'htmlOptions' => array('enctype' => 'multipart/form-data', 'onsubmit' => 'return save_account_settings();'),
                                ));
                        ?>           
            	<table width="" border="0" cellspacing="0" cellpadding="0">   
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Your Email Address'); ?>:</td>
                    <td>
                        <?php echo $form->textField($model_profile, 'email', array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
                    </td>
                  </tr> 
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Your Current Password'); ?>:</td>
                    <td>
                        <?php echo CHtml::passwordField('password', null,  array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
                    </td>
                  </tr>                  
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Your New Password'); ?>:</td>
                    <td>
                        <?php echo CHtml::passwordField('new_password', null,  array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
                    </td>
                  </tr> 
				 <tr>
                    <td class="title"><?php echo Lang::t('settings', 'Re-type new Password'); ?>:</td>
                    <td>
                        <?php echo CHtml::passwordField('re_new_password', null,  array('class' => 'input_setting w120','placeholder' => Lang::t('settings', 'Enter text'))); ?>
                    </td>
                  </tr>  
                  <tr>
                    <td class="title">&nbsp;</td>
                    <td align="right">                    	
                        <a class="btn btn-violet" href="javascript:void(0);" title="<?php echo Lang::t('settings', 'Save Settings'); ?>" onClick="save_account_settings();"><?php echo Lang::t('settings', 'Save Settings'); ?></a>
                    </td>
                  </tr>
                </table>
                <?php $this->endWidget(); ?>
</div>
<div class="left setting_col2">
</div>