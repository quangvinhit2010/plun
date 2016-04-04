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
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_start'); ?>
		<?php echo $form->textField($model,'time_start', array('id' => 'time_start')); ?>
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
		<?php echo $form->textField($model,'time_end', array('id' => 'time_end')); ?>
        <?php $this->widget('backend.extensions.calendar.SCalendar',
                    array(
                        'inputField'=>'time_end',
                        'ifFormat'=>'%d-%m-%Y',
                    ));
        ?>
        <?php echo $form->error($model,'time_end'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo CHtml::dropDownList('Table42Round[published]', $model->published, array(Table42Round::STATUS_UNPUBLISHED=>'No', Table42Round::STATUS_PUBLISHED =>'Yes')); ?>
		<?php echo $form->error($model,'published'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'disable_vote'); ?>
		<?php echo CHtml::dropDownList('Table42Round[disable_vote]', $model->disable_vote, array(Table42Round::STATUS_UNPUBLISHED=>'No', Table42Round::STATUS_PUBLISHED =>'Yes')); ?>
		<?php echo $form->error($model,'disable_vote'); ?>
	</div>	
	<div class="row">
		<?php echo $form->labelEx($model,'disable_request'); ?>
		<?php echo CHtml::dropDownList('Table42Round[disable_request]', $model->disable_request, array(Table42Round::STATUS_UNPUBLISHED=>'No', Table42Round::STATUS_PUBLISHED =>'Yes')); ?>
		<?php echo $form->error($model,'disable_request'); ?>
	</div>	
	<div class="block">
				<h2><?php echo $form->labelEx($model,'description'); ?></h2>
				<div class="input-wrap">
				<?php 
				$this->widget('application.extensions.tinymce.TinyMce', array(
						'model' => $model,
						'attribute' => 'description',
						'fileManager' => array(
								'class' => 'application.extensions.elFinder.TinyMceElFinder',
								'connectorRoute'=> Yii::app()->createUrl('//../elfinder/connector'),
						),
						'settings' => array(
							'theme_advanced_buttons1' => "save,newdocument,|,bold,italic,underline,strikethrough,code",
							'theme_advanced_buttons2' => "",
							'theme_advanced_buttons3' => "",
							'theme_advanced_buttons4' => "",
						),
						'htmlOptions' => array(
								'rows' => 20,
								'cols' => 60,
						),
				));
				?>
				<?php //echo $form->textArea($model,'description',array('rows'=>4,'cols'=>56, 'value'=>!empty($modelTransDefault->description) ? $modelTransDefault->description : '')); ?>
				<?php echo $form->error($model,'description'); ?>
				</div>
	</div>	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->