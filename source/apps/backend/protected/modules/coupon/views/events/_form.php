<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'events-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data')
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<!-- 
	<div class="row">
		<?php //echo $form->labelEx($model,'image'); ?>
		<?php //echo CHtml::activeFileField($model, 'image'); // see comments below ?>
		<?php //echo CHtml::hiddenField('Events[image]', $model->image); // see comments below ?> (width: 120, height: 90)
		<br><img src="<?php echo Util::getCurrentDomain();?>/uploads/coupon/<?php echo $model->image;?>">
		<?php //echo $form->error($model,'image'); ?>
	</div>
 	-->
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">
		<?php 
		/* if(!empty($model->item)){
			$model->item = json_decode($model->item);
			if(is_array($model->item)){
				$model->item = implode("\n", $model->item);
			}
		} */
		?>
		<?php // echo $form->labelEx($model,'item'); ?>
		<?php //echo $form->textArea($model,'item',array('cols'=>60,'rows'=>10)); ?>
		<?php //echo $form->error($model,'item'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo CHtml::activedropDownList($model,'enabled',array( 1 => 'Enable', 0 => 'Disable')); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start'); ?>
		<?php echo $form->textField($model,'start', array("id"=>"start")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'start',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end'); ?>
		<?php echo $form->textField($model,'end',array("id"=>"end")); ?>
		<?php $this->widget('backend.extensions.calendar.SCalendar',
	        array(
	        'inputField'=>'end',
	        'ifFormat'=>'%d-%m-%Y',
	    ));
	    ?>
		<?php echo $form->error($model,'end'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textArea($model,'note',array('cols'=>60,'rows'=>10)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->