<div class="bg_resetpass">
  <div class="resetpass">
      <h3><?php echo Lang::t('forgot', 'Password reset')?></h3>
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register-form',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
        )); ?>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo $form->labelEx($passform,'password');?></td>
          </tr>
          <tr>
            <td>
                <div class="input-wrap <?php echo ($passform->hasErrors('password')) ? 'error' : '';?>">
                    <?php echo $form->passwordField($passform,'password', array('placeholder'=>'')); ?>
                    <label class="arrow"></label>
                      <?php echo $form->error($passform,'password'); ?>                        
                  </div>
            </td>
          </tr>
          <tr>
            <td><?php echo $form->labelEx($passform,'verifyPassword');?></td>
          </tr>
          <tr>
            <td>
                <div class="input-wrap <?php echo ($passform->hasErrors('verifyPassword')) ? 'error' : '';?>">
                    <?php echo $form->passwordField($passform,'verifyPassword', array('placeholder'=>'')); ?>
                    <label class="arrow"></label>
                    <?php echo $form->error($passform,'verifyPassword'); ?>                        
                </div>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo CHtml::submitButton(Lang::t('forgot','Reset'), array('class'=>'reset')); ?>
            </td>
          </tr>
        </table>
        <?php $this->endWidget(); ?>
    </div>
</div>
