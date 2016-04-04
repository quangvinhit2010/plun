<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle .= ' - Login';
$this->breadcrumbs=array(
	'Login',
);
// Yii::app()->clientScript->registerScript('login-register', "
// 	var loginUrl = '".Yii::app()->createUrl('//site/login')."';
// 	var registerUrl = '".Yii::app()->createUrl('//register')."';
// ", CClientScript::POS_HEAD);
// Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/home/common.js', CClientScript::POS_BEGIN);
?>
<div class="addAlertApp" id="page" >
  <div class="box_width_common">
    <div class="homepage">
        <div class="content-wrap signin_page">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'login-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
            )); ?>
        	<div class="logo_plun">
            	<a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/images/graphics/logo276.png" align="absmiddle" width="138" height="40"></a>
            </div>
            <div class="sign_in_up">
                <ul>
                    <?php if(Yii::app()->user->hasFlash('msgLogin')): ?>
                    <li class="thongbaoloi_login">
                        	<?php echo Yii::app()->user->getFlash('msgLogin'); ?>
                    </li>
                    <?php endif; ?>
                    <li>
                        <div class="input-wrap<?php echo ($model->hasErrors('username')) ? ' error' : '';?>">
                            <?php echo $form->textField($model,'username', array(
									'placeholder'=> Lang::t('login', 'Username'), 
                            		'class'=>'input_txt_username', 
                            		'spellcheck'=>'false', 
                            		'autocorrect' => 'off', 
                            		'autocapitalize' => 'off',
//                             		'onkeyup' => 'javascript:this.value=this.value.toLowerCase();'
                            		)); ?>
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
                    <li class="remember">
                    	<div class="login-remember">
                            <?php echo $form->checkBox($model,'rememberMe'); ?>
                            <label for="LoginForm_rememberMe"><?php echo Lang::t('login', 'Remember me');?></label>   |   <a href="<?php echo Yii::app()->createUrl('//site/forgotpass')?>"><?php echo Lang::t('login', 'Forgot your Password?');?></a>
                            <?php echo $form->error($model,'rememberMe'); ?>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="buttons">
                <button class="btn btn-violet signin" type="submit"><?php echo Lang::t('login', 'Sign in');?></button>
                <button class="btn btn-gray signup" type="reset" onclick="window.location.href='<?php echo Yii::app()->createUrl('//register');?>'"><?php echo Lang::t('register', 'Sign Up');?></button>
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

<script type="text/javascript">
$('#LoginForm_username').keypress(function(e) { 
    var s = String.fromCharCode( e.which );
    if ( s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey ) {
        $('#LoginForm_username_em_').html('caps is on');
        $('#LoginForm_username_em_').show();
        console.log($('#LoginForm_username_em_'));
    }
});
                            
</script>
