<?php
/* @var $this BonusController */
/* @var $model InviteBonus */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'history_invited_id'); ?>
		<?php echo $form->textField($model,'history_invited_id',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'invited_realcash'); ?>
		<?php echo $form->textField($model,'invited_realcash'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rate'); ?>
		<?php echo $form->textField($model,'rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bonus'); ?>
		<?php echo $form->textField($model,'bonus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'execute'); ?>
		<?php echo $form->textField($model,'execute'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'timeline_rate_id'); ?>
		<?php echo $form->textField($model,'timeline_rate_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fromdate'); ?>
		<?php echo $form->textField($model,'fromdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'todate'); ?>
		<?php echo $form->textField($model,'todate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->