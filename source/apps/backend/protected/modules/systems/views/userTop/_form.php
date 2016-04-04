<?php
/* @var $this UserTopController */
/* @var $model UserTop */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-top-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php 
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		        'model' =>$model,
		        'attribute'=>'username',
		        'sourceUrl' => Yii::app()->urlManager->createUrl("srbac/authitem/getUsers") ,
		        // additional javascript options for the autocomplete plugin
		        'options' => array(
		                'minLength' => '2',
		                'select'=>'js:function(event,ui){
                        }',
		        ),
		        'htmlOptions' => array(
		                'style' => 'height:20px;'
		        ),
		));
		?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'order'); ?>
		<?php echo $form->textField($model,'order'); ?>
		<?php echo $form->error($model,'order'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->