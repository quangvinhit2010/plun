<h1>Import GiftCode</h1>
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
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textArea($model,'code',array('rows' => 10, 'cols' => 60)); ?>
		<p class="hint" style="font-size:11px;">Mỗi GiftCode là 1 hàng</p>
		<?php echo $form->error($model,'code'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>