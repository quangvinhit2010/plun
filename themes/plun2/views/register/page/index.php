<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/jquery.mCustomScrollbar.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/html/js/jquery.mousewheel.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/register.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('actionRegister', "Register.actionRegister();", CClientScript::POS_END);
?>
<div class="sign_in_up">
            	<div class="outer-wrapper">
                    <div class="sign_form">
                        <div class="wrap_frm">
                            <div class="inner_wrap">
                            <div class="title">Sign up</div>
                            <?php $form=$this->beginWidget('CActiveForm', array(
    		                    'id'=>'register-form',
    		                    'action'=>Yii::app()->createUrl('/register/index'),
    		                    'enableClientValidation'=>true,
    		//                     'enableAjaxValidation'=>true,
    		                    'clientOptions'=>array(
    		                        'validateOnSubmit'=>true,
    		                        'afterValidate' => "js: function(form, data, hasError) {
    		                            if(!hasError){
    		                                objCommon.loading();
    		                                return true;
    		                            }
    		                        }"
    		                    ),
    		                )); ?>
                            <div class="content">
                            	<ul>
                                	<li class="mar_right_20">
                                    	<div class="<?php echo ($model->hasErrors('username')) ? 'error' : '';?>">
                                            <?php echo $form->textField($model,'username', array('placeholder'=>Lang::t('register', 'Username'), 'class'=>'input_txt_other',
    		                                        'ajax' => array(
    		                                                'type'=>'POST',
    		                                                'url'=>Yii::app()->createUrl('register/ajaxvalidate/type/username'),
    		                                                'dataType' => 'json',
    		                                                'success'=>' function(data) {
    		                                                    if(data){
    		                                                        var errUsrname = "<div class=\"errorMessage\" id=\"chkUsername\" style=\"padding: 5px 0 10px 5px;\">" + data.error + "</div>";                                                            
    		                                                        if(errUsrname && errUsrname.length > 0){
    		                                                            $("#wrap-username").addClass("error");
    		                                                            //$("#wrap-username").html(errUsrname);
    		                                                            $(errUsrname).insertAfter($("#wrap-username").find(".arrow"));
    		                                                        }
    		                                                        $(".captcha a").trigger("click");
    		                                                    }
    		                                                }',
    		                                                'beforeSend'=>' function(data) { 
    		                                                    if($("#chkUsername").length > 0){
    		                                                        $("#chkUsername").remove();
    		                                                    }
    		                                                    $("#wrap-username").removeClass("error");
    		                                                    $("#RegisterForm_username_em_").hide();
    		                                                }',
    		                                        ),
    		                                )); ?>
                                            <div class="error_block">
                                                <label class="arrow"></label>
                                                <?php echo $form->error($model,'username'); ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                    	<div class="<?php echo ($model->hasErrors('email')) ? 'error' : '';?>">
                                            <?php echo $form->textField($model,'email', array('placeholder'=>Lang::t('register', 'Email'), 'class'=>'input_txt_other', 
    		                                        'ajax' => array(
    		                                                'type'=>'POST',
    		                                                'url'=>Yii::app()->createUrl('register/ajaxvalidate/type/email'),
    		                                                'dataType' => 'json',
    		                                                'success'=>' function(data) {
    		                                                    if(data){
    		                                                        var errUsrname = "<div class=\"errorMessage\" id=\"chkEmail\" style=\"padding: 5px 0 10px 5px;\">" + data.error + "</div>";                                                            
    		                                                        if(errUsrname && errUsrname.length > 0){
    		                                                            $("#wrap-email").addClass("error");
    		                                                            $(errUsrname).insertAfter($("#wrap-email").find(".arrow"));
    		                                                        }
    		                                                        $(".captcha a").trigger("click");
    		                                                    }
    		                                                }',
    		                                                'beforeSend'=>' function(data) {
    		                                                    if($("#chkEmail").length > 0){
    		                                                        $("#chkEmail").remove();
    		                                                    }
    		                                                    $("#wrap-email").removeClass("error");
    		                                                    $("#RegisterForm_username_em_").hide();
    		                                                }',
    		                                        ),
    		                                )); ?>
                                            <div class="error_block">
                                                <label class="arrow"></label>
                                                <?php echo $form->error($model,'email'); ?>                    		        
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mar_right_20">
                                    	<div>
                                            <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('register', 'Password'))); ?>
                                            <div class="error_block">
                                                <label class="arrow"></label>
                                                <?php echo $form->error($model,'password'); ?>                  		        
                                             </div>
                                         </div>
                                    </li>
                                    <li>
                                    	<div>
                                    	<?php 
                                    	$days = BirthdayHelper::model()->getDates();
                                    	$months = BirthdayHelper::model()->getMonth();
                                    	$years = BirthdayHelper::model()->getYears();
                                    	?>
                                            <div class="select_style w94 mar_right_10">
                                                <?php echo $form->dropDownList($model,'month', $months); ?>                                    
                                                <span class="txt_select"><span><?php echo array_shift($months);?></span></span> 
                                                <span class="btn_combo_down"></span>
                                            </div>
                                            <div class="select_style w61 mar_right_10">
                                                <?php echo $form->dropDownList($model,'day', $days); ?>                                    
                                                <span class="txt_select"><span><?php echo array_shift($days);?></span></span> 
                                                <span class="btn_combo_down"></span>
                                            </div>
                                            <div class="select_style w61">
                                               <?php echo $form->dropDownList($model,'year', $years); ?>                                
                                                <span class="txt_select"><span><?php echo array_shift($years);?></span></span> 
                                                <span class="btn_combo_down"></span>
                                            </div>                                        
                                         </div>
                                    </li>
                                    <li class="mar_right_20">
                                    	<div class="<?php echo ($model->hasErrors('confirm_password')) ? 'error' : '';?>">
                                            <?php echo $form->passwordField($model,'confirm_password', array('placeholder'=>Lang::t('register', 'Confirm Password'), 'class'=>'input_txt_other')); ?>
                                            <div class="error_block">
                                                <label class="arrow"></label>
                                                <?php echo $form->error($model,'confirm_password'); ?>                  		        
                                             </div>
                                         </div>
                                    </li>
                                    <?php if(CCaptcha::checkRequirements()): ?>
                                    <li>
                                    	<div class="<?php echo ($model->hasErrors('verifyCode')) ? 'error' : '';?>">
                                            <?php echo $form->textField($model,'verifyCode', array('placeholder'=>Lang::t('register', 'Type text besides'), 'class'=>'w121 left')); ?>
    	                                    <?php $this->widget('CCaptcha', array(
    	                    		            'buttonLabel'=>'',
    	                                        'imageOptions' => array(
    	                                            'style'=>'height: 28px;',
    	                                            'class'=>'left',
    	                                            'id'=>'yw0',
    	                                        ),
    	                                        'buttonOptions' => array(
    	                                            'class'=>'reload',
    	                                            'id'=>'yw0',
    	                                        )
    	                    		        )); ?>
                                            <div class="error_block">
                                                <label class="arrow"></label>
                                                <?php echo $form->error($model,'verifyCode'); ?>                    		        
                                             </div>
                                         </div>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            	<div class="rule policy">
                            		<input name="" id="chk_tos" type="checkbox" value="" checked="checked" /> 
                            		<label for="chk_tos"><?php echo Lang::t('register', 'By using this site you agree that you are at least 16 years old and acknowledge that you enter the site at your own risk and have to agree with our')?></label> 
                            		<a class="terms" href="javascript:void(0);" id="opener_agreement"><?php echo Lang::t('register', 'Users Agreement')?></a>
    							</div>
                                <div class="but_sign">
                                	<?php echo CHtml::submitButton(Lang::t('register', 'Sign up now'), array('class'=>'btn_submit')); ?>
                                </div>
                                <?php if(!empty(CParams::load()->params->loginsocial->enable)){?>
                                <div class="loginSocialUser">
                                    <p>Đăng nhập bằng tài khoản</p>
                                    <a onclick="window.open('/register/Facebook', 'win001', 'width=500, height=500');" class="icon_common icon_faceLogin" href="javascript:void(0);"></a>
                                    <a href="javascript:void(0);" onclick="window.open('/register/Google', 'win002', 'width=500, height=500');" class="icon_common icon_googleLogin"></a>
                                </div>
                                <?php }?>
                                <div class="link_more"><?php echo Lang::t('register', 'Already have an account')?>? <a href="<?php echo Yii::app()->createUrl('//site/login')?>"><?php echo Lang::t('login', 'Sign in')?></a></div>
                            </div>
                            <?php $this->endWidget(); ?>
                            </div>
                            <div class="gaussian_blur blur"></div>
                        </div>
                    </div>
                    <svg class="svg-image-blur">
                        <filter id="blur-effect-1">
                            <feGaussianBlur stdDeviation="5"></feGaussianBlur>
                        </filter>
                    </svg>
                </div>
            </div>
<?php $this->renderPartial('page/'.Yii::app()->language.'/_users-agreement', array());?>
	