<h1>Generate GiftCode</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'import-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'event'); ?>
		<?php echo CHtml::activedropDownList($model,'event', CHtml::listData(Events::model()->findAll(), 'id', 'title'), array('empty' => ' --- Select Event --- ')); ?>
		<?php echo $form->error($model,'event'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'formula'); ?>
		<?php echo $form->textField($model,'formula'); ?>
		<span class="hint">Ex: xxxx-xxxx-xxxx</span>
		<?php echo $form->error($model,'formula'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'numberOfDigit'); ?>
		<?php echo $form->textField($model,'numberOfDigit'); ?>
		<?php echo $form->error($model,'numberOfDigit'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity'); ?>
		<?php echo $form->error($model,'quantity'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo CHtml::activedropDownList($model,'type',array('1' => Giftcode::TYPE_SYSTEM, '2' => Giftcode::TYPE_MARKETING), array('empty' => ' --- Choose --- ')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>