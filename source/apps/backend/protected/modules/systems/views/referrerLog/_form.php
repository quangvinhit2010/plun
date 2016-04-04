<?php
/* @var $this ReferrerLogController */
/* @var $model ReferrerLog */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'referrer-log-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'type_referrer'); ?>
		<?php echo $form->textField($model,'type_referrer'); ?>
		<?php echo $form->error($model,'type_referrer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'redirect_url'); ?>
		<?php echo $form->textArea($model,'redirect_url',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'redirect_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'referrer_url'); ?>
		<?php echo $form->textArea($model,'referrer_url',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'referrer_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'referrer_id'); ?>
		<?php echo $form->textField($model,'referrer_id'); ?>
		<?php echo $form->error($model,'referrer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type_log'); ?>
		<?php echo $form->textField($model,'type_log'); ?>
		<?php echo $form->error($model,'type_log'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->