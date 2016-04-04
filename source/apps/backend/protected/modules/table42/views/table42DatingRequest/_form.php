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
		<?php echo $form->labelEx($model,'vote_total'); ?>
		<?php echo $form->textField($model,'vote_total',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'vote_total'); ?>
	</div>	
	<div class="row">
		<?php echo $form->labelEx($model,'is_win'); ?>
		<?php echo CHtml::dropDownList('Table42DatingRequest[is_win]', $model->is_win, array(0=>'No', 1 =>'Yes')); ?>
		<?php echo $form->error($model,'is_win'); ?>
	</div>	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->