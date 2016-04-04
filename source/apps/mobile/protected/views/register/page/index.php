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
<div class="addAlertApp" id="page" >
  <div class="box_width_common">
    <div class="homepage">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register-form',
            'action'=>Yii::app()->createUrl('/register/index'),
            'enableClientValidation'=>true,
//             'enableAjaxValidation'=>true,
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
        <div class="content-wrap signin_page signup_page">
        	<div class="logo_plun">
            	<a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/css/images/graphics/logo276.png" align="absmiddle" width="138" height="40"></a>
            </div>
            <div class="sign_in_up">
                <ul>
                    <li>
                        <div class="input-wrap">
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
                            <div>
                                <?php echo $form->passwordField($model,'password', array('placeholder'=>Lang::t('register', 'Password'), 'class'=>'input_txt_other')); ?>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'password'); ?>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="input-wrap">
                            <div>
                                <?php echo $form->passwordField($model,'confirm_password', array('placeholder'=>Lang::t('register', 'Confirm Password'), 'class'=>'input_txt_other')); ?>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'confirm_password'); ?>
                            </div>
                        </div>
                    </li>    
                    <li>
                        <div class="input-wrap">
                                <div class="select_style w112">
                                    <?php echo $form->dropDownList($model,'month', BirthdayHelper::model()->getMonth()); ?>
                                    <span class="txt_select"><span><?php echo (!empty($model->month) ? BirthdayHelper::model()->getMonth($model->month) : Lang::t('time', 'Month'))?></span></span> <span class="btn_combo_down"></span>
                                </div>
                                <div class="select_style w62">
                                    <?php echo $form->dropDownList($model,'day', BirthdayHelper::model()->getDates()); ?>
                                    <span class="txt_select"><span><?php echo (!empty($model->day) ? BirthdayHelper::model()->getDates($model->day) : Lang::t('time', 'Day'))?></span></span> <span class="btn_combo_down"></span>
                                </div>
                                <div class="select_style w76">
                                    <?php echo $form->dropDownList($model,'year', BirthdayHelper::model()->getYears()); ?>
                                    <span class="txt_select"><span><?php echo (!empty($model->year) ? BirthdayHelper::model()->getYears($model->year) : Lang::t('time', 'Year'))?></span></span> <span class="btn_combo_down"></span>
                                </div>
                                <label class="arrow"></label>
                                <?php echo $form->error($model,'day'); ?>
                        </div>
                    </li>                
                    <li>
                        <?php if(CCaptcha::checkRequirements()): ?>
                            <div class="captcha">
                                <div class="<?php echo ($model->hasErrors('verifyCode')) ? 'error' : '';?>">
                                    <?php echo $form->textField($model,'verifyCode', array('placeholder'=>Lang::t('register', 'Type text besides'), 'class'=>'input_txt_captchar left', 'style'=>'width: 133px;')); ?>
                                    <?php $this->widget('CCaptcha', array(
                    		            'buttonLabel'=>'',
                                        'imageOptions' => array(
                                            'style'=>'height: 34px;'
                                        )
                    		        )); ?>
                    		        <div  style="width:100%; float:left;">
                        		        <label class="arrow"></label>
                                        <?php echo $form->error($model,'verifyCode'); ?>
                    		        </div>
                		        </div>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <div class="policy">
                <input name="" id="chk_tos" type="checkbox" value="" checked="checked" /> 
                <label for="chk_tos"><?php echo Lang::t('register', 'By using this site you agree that you are at least 16 years old and acknowledge that you enter the site at your own risk and have to agree with our')?></label> 
                <a class="terms" href="javascript:void(0);"><?php echo Lang::t('register', 'Users Agreement')?></a>
            </div>
            <div class="buttons">
                <?php echo CHtml::submitButton(Lang::t('register', 'Sign Up'), array('class'=>'btn btn-violet signin')); ?>
                <button class="btn btn-gray signup" type="reset"  onclick="window.location.href='<?php echo Yii::app()->createUrl('//site/login');?>'" href="#"><?php echo Lang::t('login', 'Sign in')?></button>
            </div>
        </div> 
        <?php $this->endWidget(); ?>
    </div>
    <div class="clear"></div>
  </div>
</div>

<div id="downApp" class="clearfix">
    <a href="https://play.google.com/store/apps/details?id=dwm.plun.asia" class="left logo_google_app"></a>
    <a href="https://play.google.com/store/apps/details?id=dwm.plun.asia" class="right btn_setup">Cài đặt</a>
</div>

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

<?php $this->renderPartial('page/'.Yii::app()->language.'/_users-agreement', array(
));?>



