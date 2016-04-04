<?php
/* @var $this HotboxController */
/* @var $model Hotbox */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotbox-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<!-- 
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type'); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type_id'); ?>
		<?php echo $form->textField($model,'type_id'); ?>
		<?php echo $form->error($model,'type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textArea($model,'title',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->textArea($model,'slug',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'slug'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	 -->

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php //echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php 
                        $this->widget('backend.extensions.tinymce.TinyMce', array(
                        	'model' => $model,
                            'attribute' => 'body',
                            'htmlOptions' => array(
                            	'rows' => 15,
                                'cols' => 10,
							),
                            'settings' => array(
                            	'plugins' => '',                                                        
                            	'height' => '100px',                                                        
                                'width' => '500px',
                                'theme_advanced_buttons1' => "bold,italic,underline",
                                'theme_advanced_buttons2' => "",
                                'theme_advanced_buttons3' => "",
                                'theme_advanced_buttons4' => "",
                                'theme_advanced_resizing' => false,
								'theme_advanced_path' =>  false,
				
							),
                    	));
					?>
		<?php echo $form->error($model,'body'); ?>
	</div>
	<!-- 
	<div class="row">
		<?php echo $form->labelEx($model,'meta_description'); ?>
		<?php echo $form->textArea($model,'meta_description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'meta_description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'meta_keywords'); ?>
		<?php echo $form->textArea($model,'meta_keywords',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'meta_keywords'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'author_id'); ?>
		<?php echo $form->textField($model,'author_id'); ?>
		<?php echo $form->error($model,'author_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'thumbnail_id'); ?>
		<?php echo $form->textField($model,'thumbnail_id'); ?>
		<?php echo $form->error($model,'thumbnail_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'public_time'); ?>
		<?php echo $form->textField($model,'public_time'); ?>
		<?php echo $form->error($model,'public_time'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'view'); ?>
		<?php echo $form->textField($model,'view'); ?>
		<?php echo $form->error($model,'view'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modify'); ?>
		<?php echo $form->textField($model,'modify'); ?>
		<?php echo $form->error($model,'modify'); ?>
	</div>
	-->
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->