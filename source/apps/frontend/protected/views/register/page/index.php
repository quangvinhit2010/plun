<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* @var $form CActiveForm  */

$this->pageTitle .= ' - Register';
$this->breadcrumbs=array(
	'Register',
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/home/common.js', CClientScript::POS_BEGIN);
?>
<script>
    function cboxSelected(classSelect){
        var i = classSelect;
        $(i).on('change',function(){
            var item_select = $(this).find(':selected').text();
            $(this).parent().find('.txt_select span').text(item_select);
        });
    }
    $(function(){
        var select_cbox = '.select_style select';
        cboxSelected(select_cbox);
        $('.select_style select').css('opacity','0');
    });
</script>
<div id="page-login" class="page-form">
    <div class="spr-modal-wrap">
        <div class="form-contain form-login">
            <div class="form-contain-wrap">
                <!-- close button -->
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'register-form',
                    'action'=>Yii::app()->createUrl('/register/index'),
                    'enableClientValidation'=>true,
//                     'enableAjaxValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                        'afterValidate' => "js: function(form, data, hasError) {
                            if(!hasError){
                                $('body').loading();
                                return true;
                            }
                        }"
                    ),
                )); ?>
                <div class="title">
                    <p class="signin"><?php echo Lang::t('register', 'Sign Up');?></p>
                </div>
                <ul class="w390">                    
                    <li>
                        <div class="input-wrap">
                            <label for="RegisterForm_username" class="w117 ">Username</label>
                            <div id="wrap-username">
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
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'username'); ?>
                            </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="input-wrap">
                            <label for="RegisterForm_email" class="w117 ">Email</label>
                            <div id="wrap-email">
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
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'email'); ?>
                            </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="input-wrap">
                            <?php echo $form->label($model, 'password', array('class'=>'w117')); ?>
                            <div>
                                <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('register', 'Password'), 'class'=>'input_txt_other')); ?>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'password'); ?>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="input-wrap">
                            <?php echo $form->label($model, 'confirm_password', array('class'=>'w117')); ?>
                            <div>
                                <?php echo $form->passwordField($model,'confirm_password', array('placeholder'=>Lang::t('register', 'Confirm Password'), 'class'=>'input_txt_other')); ?>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'confirm_password'); ?>
                            </div>
                        </div>
                    </li>    
                    <li class="left">
                        <div class="input-wrap">
                            <span class="w125"><?php echo Lang::t('register', 'Date of Birth')?></span>
                                <div class="select_style w112">
                                    <?php echo $form->dropDownList($model,'month', BirthdayHelper::model()->getMonth()); ?>
                                    <span class="txt_select"><?php echo (!empty($model->month) ? BirthdayHelper::model()->getMonth($model->month) : '')?><span></span></span> <span class="btn_combo_down"></span>
                                </div>
                                <div class="select_style w62">
                                    <?php echo $form->dropDownList($model,'day', BirthdayHelper::model()->getDates()); ?>
                                    <span class="txt_select"><?php echo (!empty($model->day) ? BirthdayHelper::model()->getDates($model->day) : '')?><span></span></span> <span class="btn_combo_down"></span>
                                </div>
                                <div class="select_style w76">
                                    <?php echo $form->dropDownList($model,'year', BirthdayHelper::model()->getYears()); ?>
                                    <span class="txt_select"><?php echo (!empty($model->year) ? BirthdayHelper::model()->getYears($model->year) : '')?><span></span></span> <span class="btn_combo_down"></span>
                                </div>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'day'); ?>
                        </div>
                    </li>                
                    <li>
                        <?php if(CCaptcha::checkRequirements()): ?>
                            <div class="captcha">
                                <label for="RegisterForm_verifyCode" class="w125">Captcha</label>
                                <div class="<?php echo ($model->hasErrors('verifyCode')) ? 'error' : '';?>">
                                    <?php echo $form->textField($model,'verifyCode', array('placeholder'=>Lang::t('register', 'Type text besides'), 'class'=>'input_txt_captchar left', 'style'=>'width: 156px;')); ?>
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
                <div class="clear">&nbsp;</div>
                <ul class="w247 w100_per">
                    <li class="policy">
                        <input name="" id="chk_tos" type="checkbox" value="" checked="checked" /> 
                        <label for="chk_tos"><?php echo Lang::t('register', 'By using this site you agree that you are at least 16 years old and acknowledge that you enter the site at your own risk and have to agree with our')?></label> 
                        <a class="terms" href="javascript:void(0);"><?php echo Lang::t('register', 'Users Agreement')?></a>
                    </li>
                    <li class="signup_now">
                        <?php echo CHtml::submitButton(Lang::t('register', 'Sign up now'), array('class'=>'btnSignUp')); ?>
                    </li>
                    <li class="link_signup"><a href="<?php echo Yii::app()->createUrl('//site/login');?>"><?php echo Lang::t('login', 'Sign In')?></a></li>
                </ul>
                    
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <!-- form container -->
        <div class="position"></div>
    </div>
    <!-- wrap -->
</div>

<?php $this->renderPartial('page/'.Yii::app()->language.'/_users-agreement', array(
));?>



