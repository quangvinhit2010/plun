
<div class="wrap_map">
    <img src="<?php echo Yii::app()->theme->baseUrl?>/resources/html/images/mapCty.jpg" alt=""/>
</div>
<div class="frm_contact">
    <div class="el_form">
        <h1><?php echo Lang::t('contact', 'Contact Form')?></h1>
        <div class="infor_cty clearfix">
            <p><i class="icon_common icon_cty"></i><b><?php echo Lang::t('contact', 'Dream Weavers Online Services JSC');?></b></p>
            <p><?php echo Lang::t('contact', 'Block');?>, <?php echo Lang::t('contact', 'Address_Office');?></p>
            <p class="left"><i class="icon_common icon_phone"></i><b><?php echo Lang::t('contact', 'Phone');?>:</b> (848) 5405 1168 </p>
            <p class="left"><i class="icon_common icon_email"></i><b><?php echo Lang::t('contact', 'Email');?>:</b> support@plun.asia </p>
        </div>
        <?php if(Yii::app()->user->hasFlash('contact')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('contact'); ?>
            </div>
        <?php else: ?>
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'contact-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
                'htmlOptions'=>array(
                    'class'=>'clearfix',
                    'data-url'=>Yii::app()->createUrl('/contact'),
                ),
            )); ?>
            <div class="left">
                <div class="wrap_fi">
                    <?php echo $form->textField($model,'name', array('placeholder'=> Lang::t('contact', 'Name') , 'class'=>'input_txt_firstname')); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div>
                <div class="wrap_fi">
                    <?php echo $form->textField($model,'email', array('placeholder'=> Lang::t('contact', 'Email'), 'class'=>'input_txt_firstname')); ?>
                    <?php echo $form->error($model,'email'); ?>
                </div>
                <div class="wrap_fi">
                    <?php echo $form->textField($model,'phone_number', array('placeholder'=> Lang::t('contact', 'Phone') , 'class'=>'input_txt_firstname')); ?>
                    <?php echo $form->error($model,'phone_number'); ?>
                </div>
                <div class="wrap_fi">
                    <?php if(CCaptcha::checkRequirements()): ?>
                        <?php echo $form->textField($model,'verifyCode', array('class'=>'left', 'placeholder'=>Lang::t('register', 'Type text besides'))); ?>
                        <?php $this->widget('CCaptcha', array(
                                    'buttonLabel'=>''
                        )); ?>
                        <?php echo $form->error($model,'verifyCode'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right">
                <div class="select_style">
                    <?php echo $form->dropDownList($model,'subject', $subjectOptions, array('placeholder'=> Lang::t('contact', 'Subject'), 'class'=>'select-type-5 virtual_form', 'text'=>"body_text")); ?>
                    <span class="txt_select"><span class="body_text"><?php echo Lang::t('contact', 'Subject');?></span></span> <span class="btn_combo_down"></span>
                    <?php echo $form->error($model,'subject'); ?>
                </div>
                <div class="wrap_fi">
                    <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'placeholder'=>Lang::t('contact', 'Content'))); ?>
                    <?php echo $form->error($model,'body'); ?>
                </div>
            </div>
            <div class="btn_center">
                <input type="submit" class="but_submit" value="<?php echo Lang::t('contact', 'Submit'); ?>" name="">
                <input class="but_reset" type="reset" value="<?php echo Lang::t('contact', 'Reset'); ?>" name="">
            </div>
        <?php $this->endWidget(); ?>
        <?php endif; ?>
    </div>
</div>