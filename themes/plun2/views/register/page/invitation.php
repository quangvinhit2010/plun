<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* @var $form CActiveForm  */

$this->breadcrumbs=array(
	'Register',
);
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/home/common.js', CClientScript::POS_BEGIN);*/
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
                    'id'=>'invitation-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                )); ?>
                <div class="title">
                    <p class="signin">Chào mừng bạn đến với PLUN.ASIA</p>
                    <p style="font-size: 20px;">Đây là phiên bản Thử Nghiệm. Chỉ dành cho những thành viên được mời mới có thể tham dự.<br />Nếu bạn đã có mã thư mời (Invitation Code). Hãy nhập vào ô bên dưới và bấm vào Đăng Ký</p>
                </div>
                <ul class="w390">
                    <li>
                        <div class="input-wrap">
                            <?php echo $form->textField($model,'code', array('placeholder'=>'Invitation Code', 'class'=>'input_txt_other')); ?>
                        </div>
                        <?php echo $form->error($model,'code'); ?>
                    </li>
                </ul>
                <div class="clear">&nbsp;</div>
                <ul class="w247 w100_per">
                    <li class="signup_now">
                        <?php echo CHtml::submitButton('Đăng ký', array()); ?>
                    </li>
                </ul>
                    
                <?php $this->endWidget(); ?>
                
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'receive-invitation-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                )); ?>
                <div class="title">
                    <p style="font-size: 20px;">Bạn nhập email bên dưới để nhận mã thư mời (Invitation Code).</p>
                </div>
                <ul class="w390">
                    <li class="left">
                        <div class="msg"></div>
                        <div class="input-wrap left">
                            <?php echo $form->textField($receiveModel,'email', array('placeholder'=>'Email', 'class'=>'input_txt_email')); ?>
                        </div>
                        <div class="but_gui">
                            <?php echo CHtml::submitButton('Gửi', array('class'=>'receiveInvitationCode')); ?>
                        </div>
                        <?php echo $form->error($receiveModel,'email'); ?>
                    </li>
                    
                </ul>
                <div class="clear">&nbsp;</div>
                <ul class="w247 w100_per">
                    
                    <li class="link_signup"><a href="<?php echo Yii::app()->createUrl('//site/login');?>">Đăng nhập</a></li>
                </ul>
                    
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <!-- form container -->
        <div class="position"></div>
    </div>
    <!-- wrap -->
</div>


