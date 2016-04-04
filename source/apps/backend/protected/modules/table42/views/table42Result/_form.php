<?php
/* @var $this PurpleguyRoundController */
/* @var $model PurpleguyRound */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purpleguy-round-form',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
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
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo CHtml::dropDownList('Table42Result[published]', $model->published, array(Table42Round::STATUS_UNPUBLISHED=>'No', Table42Round::STATUS_PUBLISHED =>'Yes')); ?>
		<?php echo $form->error($model,'published'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'couple_id'); ?>
		<?php echo $form->textField($model,'couple_id',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'couple_id'); ?>
	</div>	
		<div class="row">
		<?php echo $form->labelEx($model,'round_id'); ?>
		<?php 
		    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'roundname',
				'value'=> isset($model->round->id) ?	$model->round->title : '',
		        'source' => Yii::app()->urlManager->createUrl("table42/table42Round/Getround") ,
		        // additional javascript options for the autocomplete plugin
		        'options' => array(
		            'minLength' => '2',
		            'select'=>'js:function(event,ui){
		            	$("#Table42Result_round_id").val(ui.item.value);
						$("#roundname").val(ui.item.label);
						return false;
		        	}',
		        ),
		        'htmlOptions' => array(
		            'style' => 'height:20px;'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'round_id'); ?>
		
		<?php echo $form->hiddenField($model,'round_id'); ?>
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
							'theme_advanced_buttons1' => "save,newdocument,|,bold,italic,underline,strikethrough, code",
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
	<?php 
		$img_total = 0;
		if(isset($model->photo[0])): 
	?>
	<div class="row">	
		<ul>
			<?php foreach ($model->photo AS $row): 
				$img_total++;
			?>
				<li><?php echo $row->getImageThumb203x204(false, array('width' => '60px', 'height' => '60px')); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
	
	<?php for($i = 1; $i <= 5 - $img_total; $i++): ?>
	
	<div class="row">
		<input type="file" name="Table42Result<?php echo $i; ?>" id="fileToUpload1">
	</div>	
	<?php endfor; ?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->