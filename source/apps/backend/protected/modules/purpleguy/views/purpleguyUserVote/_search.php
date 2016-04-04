<?php
/* @var $this PurpleguyUserVoteController */
/* @var $model PurpleguyUserVote */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'round_id'); ?>
		<?php echo $form->textField($model,'round_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'total_vote'); ?>
		<?php echo $form->textField($model,'total_vote'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->