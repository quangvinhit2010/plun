<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purpleguy-round-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'round_name'); ?>
		<?php echo $form->textField($model,'round_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'round_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_start'); ?>
		<?php echo $form->textField($model,'time_start', array("id"=>"time_start")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'time_start',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'time_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_end'); ?>
		<?php echo $form->textField($model,'time_end', array("id"=>"time_end")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'time_end',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'time_end'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'event_id'); ?>
		<?php echo $form->textField($model,'event_id'); ?>
		<?php echo $form->error($model,'event_id'); ?>
	</div>
	
	<div class="row">
		<label for="PurpleguyRound_disable_vote" class="required">Disable Vote <span class="required">*</span></label>
		<?php echo CHtml::activeCheckBox($model, 'disable_vote'); ?>
		<?php echo $form->error($model,'disable_vote'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->