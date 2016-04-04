<div class="addAlertApp" id="page" >
  <div class="box_width_common">
    <div class="homepage">
        <div class="content-wrap signin_page forgot_page">
            <div class="w285 title">
                <h3><?php echo Lang::t('forgot', 'Password reset')?></h3>
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
                        <div class="input-wrap <?php echo ($passform->hasErrors('password')) ? 'error' : '';?>">
                            <?php echo $form->passwordField($passform,'password', array('placeholder'=>Lang::t('forgot', 'New Password'))); ?>
                            <label class="arrow"></label>
                              <?php echo $form->error($passform,'password'); ?>                        
                          </div>
                    </li>
                    <li>
                        <div class="input-wrap <?php echo ($passform->hasErrors('verifyPassword')) ? 'error' : '';?>">
                            <?php echo $form->passwordField($passform,'verifyPassword', array('placeholder'=>Lang::t('forgot', 'Verify Password'))); ?>
                            <label class="arrow"></label>
                            <?php echo $form->error($passform,'verifyPassword'); ?>                        
                        </div>
                    </li>
                </ul>
            </div>
            <div class="buttons">
                <?php echo CHtml::submitButton(Lang::t('forgot','Reset'), array('class'=>'btn btn-violet signin')); ?>
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