<?php
/* @var $this TimelineRateController */
/* @var $model InviteTimelineRate */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invite-timeline-rate-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php echo $form->textField($model,'rate',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fromdate'); ?>
		<?php echo $form->textField($model,'fromdate', array("id"=>"from_date")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'from_date',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'fromdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'todate'); ?>
		<?php echo $form->textField($model,'todate', array("id"=>"to_date")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'to_date',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'todate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->dropDownList($model, 'active', array(0 => Yii::t(null,'No'),1 => Yii::t(null,'Yes'))); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->