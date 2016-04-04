<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'invitation-form',
					'action' => Yii::app()->createUrl('//site/invitation'),	
					'enableAjaxValidation'=>false,
					'enableClientValidation'=>true,
)); ?>


<div class="row">
	<?php echo $form->labelEx($model,'code'); ?>
	<?php echo $form->textField($model,'code'); ?>
	<?php echo $form->error($model,'code'); ?>
</div>

<div class="row buttons">
	<?php echo CHtml::submitButton('Submit'); ?>
</div>
<?php $this->endWidget(); ?>