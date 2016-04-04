<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle .= ' - Login';
$this->breadcrumbs=array(
	'Login',
);
Yii::app()->clientScript->registerScript('login-register', "
	var loginUrl = '".Yii::app()->createUrl('//site/login')."';
	var registerUrl = '".Yii::app()->createUrl('//register')."';
", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/home/common.js', CClientScript::POS_BEGIN);
?>
<div id="page-login" class="page-form">
    <div class="spr-modal-wrap">
        <div class="form-contain form-login">
            <div class="form-contain-wrap">
                <!-- close button -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                )); ?>
                    <div class="title">
                        <p class="signin"><?php echo Lang::t('login', 'Sign In')?></p>
                    </div>
                    <ul class="w247">
                        <?php if(Yii::app()->user->hasFlash('msgLogin')): ?>
                        <li class="thongbaoloi_login">
                            	<?php echo Yii::app()->user->getFlash('msgLogin'); ?>
                        </li>
                        <?php endif; ?>
                        <li>
                            <div class="input-wrap<?php echo ($model->hasErrors('username')) ? ' error' : '';?>">
                                <?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('login', 'Username'), 'class'=>'input_txt_username')); ?>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'username'); ?>
                            </div>
                        </li>
                        <li>
                            <div class="input-wrap<?php echo ($model->hasErrors('password')) ? ' error' : '';?>">
                                <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('login', 'Password'), 'class'=>'input_txt_pass')); ?>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'password'); ?>
                            </div>
                        </li>
                        <li>
                        	<div class="login-remember">
                            <?php echo $form->checkBox($model,'rememberMe'); ?> <label for="LoginForm_rememberMe"><?php echo Lang::t('login', 'Remember me');?></label>   |   <a href="<?php echo Yii::app()->createUrl('//site/forgotpass')?>"><?php echo Lang::t('login', 'Forgot your Password?');?></a>
                            <?php echo $form->error($model,'rememberMe'); ?>
                            </div>
                        </li>
                        <li class="signin_now">
                            <?php echo CHtml::submitButton(Lang::t('login', 'Sign in now'), array('class'=>'btnSignIn')); ?>
                        </li>
                        <li class="share">
                        </li>
                        <li class="link_signup"><a href="<?php echo Yii::app()->createUrl('//register');?>"><?php echo Lang::t('register', 'Sign up now');?></a></li>
                    </ul>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <!-- form container -->
        <div class="position"></div>
    </div>
    <!-- wrap -->
</div>















