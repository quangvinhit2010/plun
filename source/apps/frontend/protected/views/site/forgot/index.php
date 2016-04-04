<div class="bg_resetpass">
    <div class="resetpass">
        <h3><?php echo Lang::t('forgot', 'Forgot your password')?></h3>
        <?php echo Lang::t('forgot', 'Forgot_des1')?>
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register-form',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
        )); ?>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td></td>
          </tr>
          <tr>
            <td>
                <div class="input-wrap <?php echo ($model->hasErrors('email')) ? 'error' : '';?>">
                    <?php echo $form->textField($model,'email', array('placeholder'=>Lang::t('forgot', 'Enter Your Email'))); ?>
                    <label class="arrow"></label>
                    <?php echo $form->error($model,'email'); ?>
                </div>
            </td>
          </tr>
          <tr>
            <td>
                <div class="captcha">
                <?php if(CCaptcha::checkRequirements()): ?>
                    <div class="input-wrap">
                    	<div class="<?php echo ($model->hasErrors('verifyCode')) ? 'error' : '';?>">
                        <?php echo $form->textField($model,'verifyCode', array('placeholder'=>Lang::t('register', 'Type text besides'), 'class'=>'captcha left')); ?>
                        <?php $this->widget('CCaptcha', array(
        		            'buttonLabel'=>'',
                            'imageOptions' => array(
                                'style'=>'height: 28px;'
                            )
        		        )); ?>
        		        <div  style="width:100%; float:left; text-align:center;">
            		        <label class="arrow"></label>
                            <?php echo $form->error($model,'verifyCode'); ?>
        		        </div>
    		        </div>
                    </div>
                <?php endif; ?>
                </div>
            </td>
          </tr>
          <tr>
            <td>
                <?php echo CHtml::submitButton(Lang::t('forgot', 'Submit'), array('class'=>'reset')); ?>
            </td>
          </tr>
        </table>
        <?php $this->endWidget(); ?>
    </div>
</div>
