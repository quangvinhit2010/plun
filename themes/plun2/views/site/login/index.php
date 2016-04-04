<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle .= ' - Login';
$this->breadcrumbs=array(
	'Login',
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/register.js', CClientScript::POS_BEGIN);
?>
<div class="sign_in_up">
            	<div class="outer-wrapper">
                    <div class="sign_form sign_in">
                        <div class="wrap_frm">
                            <div class="inner_wrap">
                                <div class="title">Sign in</div>
                                <?php $form=$this->beginWidget('CActiveForm', array(
        		                    'id'=>'login-form',
        		                    'enableClientValidation'=>true,
        		                    'clientOptions'=>array(
        		                        'validateOnSubmit'=>true,
        		                    ),
        		                )); ?>
                                <div class="content">
                                	<ul>
                                    	<li class="mar_right_20">
                                        	<div class="<?php echo ($model->hasErrors('username')) ? ' error' : '';?>">
                                                <?php echo $form->textField($model,'username', array('placeholder'=> Lang::t('login', 'Username'), 'class'=>'')); ?>
                                                <div class="error_block">
                                                    <label class="arrow"></label>
                                                    <?php echo $form->error($model,'username'); ?>                   		        
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                        	<div class="<?php echo ($model->hasErrors('password')) ? ' error' : '';?>">
                                                <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('login', 'Password'), 'class'=>'')); ?>
                                                <div class="error_block">
                                                    <label class="arrow"></label>
                                                    <?php echo $form->error($model,'password'); ?>                  		        
                                                </div>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                	<div class="remember">
        	                        	<?php echo $form->checkBox($model,'rememberMe'); ?><label for="LoginForm_rememberMe"><?php echo Lang::t('login', 'Remember me');?></label> | 
        	                        	<a href="<?php echo Yii::app()->createUrl('//site/forgotpass')?>"><?php echo Lang::t('login', 'Forgot your Password?');?></a>
                                	</div>
                                    <div class="but_sign"><?php echo CHtml::submitButton(Lang::t('login', 'Sign in now'), array('class'=>'btn_submit')); ?></div>
                                    <?php if(!empty(CParams::load()->params->loginsocial->enable)){?>
                                    <div class="loginSocialUser">
                                        <p>Đăng nhập bằng tài khoản</p>
                                        <a href="javascript:void(0);" onclick="window.open('/register/Facebook', 'win001', 'width=500, height=500');" class="icon_common icon_faceLogin"></a>
                                        <a href="javascript:void(0);" onclick="window.open('/register/Google', 'win002', 'width=500, height=500');" class="icon_common icon_googleLogin"></a>
                                    </div>
                                    <?php }?>
                                    <div class="link_more"><?php echo Lang::t('login', 'Not a member yet')?>? <a href="<?php echo Yii::app()->createUrl('//register')?>"><?php echo Lang::t('register', 'Sign Up')?></a></div>
                                </div>
                                <?php $this->endWidget(); ?>

                                <div class="gaussian_blur blur"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script type="text/javascript">
    $(document).ready(function(){
        objCommon.tooltipPlun({
            posiBottom: true,
            el: '.loginSocialUser a.icon_faceLogin',
            titleTip: "<?php echo Lang::t('login', 'Don\'t worry, we won\'t post anything on your timeline.')?>"
        });

        objCommon.tooltipPlun({
            posiBottom: true,
            el: '.loginSocialUser a.icon_googleLogin',
            titleTip: "<?php echo Lang::t('login', 'Don\'t worry, we won\'t post anything on your timeline.')?>"
        });
    });

</script>