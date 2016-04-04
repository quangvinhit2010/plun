<div class="addAlertApp" id="page" >
  <div class="box_width_common">
    <div class="homepage">
        <div class="content-wrap signin_page forgot_page">
        	<div class="w285 title">
            	<h3><?php echo Lang::t('forgot', 'Forgot your password')?></h3>
                <?php echo Lang::t('forgot', 'Forgot_des1')?>
            </div>
            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'register-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                )); ?>
            <div class="sign_in_up">
                <ul>
                    <li>
                        <div class="input-wrap <?php echo ($model->hasErrors('email')) ? 'error' : '';?>">
                            <?php echo $form->textField($model,'email', array('placeholder'=>Lang::t('forgot', 'Enter Your Email'))); ?>
                            <label class="arrow"></label>
                            <?php echo $form->error($model,'email'); ?>
                        </div>
                    </li>
                    <li>
                        <?php if(CCaptcha::checkRequirements()): ?>
                            <div class="input-wrap">
                            	<div class="<?php echo ($model->hasErrors('verifyCode')) ? 'error' : '';?>">
                                <?php echo $form->textField($model,'verifyCode', array('placeholder'=>Lang::t('register', 'Type text besides'), 'class'=>'captchar')); ?>
                                <?php $this->widget('CCaptcha', array(
                		            'buttonLabel'=>'',
                                    'imageOptions' => array(
                                        'style'=>'height: 34px;'
                                    )
                		        )); ?>
                		        <div  style="width:100%; float:left; text-align:center;">
                    		        <label class="arrow"></label>
                                    <?php echo $form->error($model,'verifyCode'); ?>
                		        </div>
            		        </div>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="buttons">
                <?php echo CHtml::submitButton(Lang::t('forgot', 'Submit'), array('class'=>'btn btn-violet signin')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div> 
    </div>
    <div class="clear"></div>
  </div>
</div>

<div id="downApp" class="clearfix">
    <a href="https://play.google.com/store/apps/details?id=dwm.plun.asia" class="left logo_google_app"></a>
    <a href="https://play.google.com/store/apps/details?id=dwm.plun.asia" class="right btn_setup">Cài đặt</a>
</div>