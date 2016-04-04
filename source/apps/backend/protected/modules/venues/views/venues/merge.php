<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */
/* @var $form CActiveForm */
?>

<p class="note">Fields with <span class="required">*</span> are required.</p>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'sys-banner-form',
		'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data')
	)); ?>

	<?php echo $form->errorSummary($model); ?>
			<div class="row">
				<h2>Tên Venue Hiện Tại: </h2>
				<div class="input-wrap">
					<?php echo $model->title; ?>
				<?php echo $form->error($model,'title'); ?>
				</div>
			</div>
			<div class="row">
				<h2>Merge vào Venue Mới: </h2>
				<div class="input-wrap">
					<div class="row">
					<?php
					    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'name'=>'venue_name',
							'value'=> '',
					        'source' => Yii::app()->urlManager->createUrl("venues/venues/getVenueSuggest") ,
					        // additional javascript options for the autocomplete plugin
					        'options' => array(
					            'minLength' => '2',
					            'select'=>'js:function(event,ui){
					            	$("#merge_venue_id").val(ui.item.value);
									$("#venue_name").val(ui.item.label);
									return false;
					        	}',
					        ),
					        'htmlOptions' => array(
					            'style' => 'height:20px;'
					        ),
					    ));
					?>
					<?php echo $form->error($model,'id'); ?>
					
					<?php echo $form->hiddenField($model,'id'); ?>
					<input type="hidden" name="CmsVenues[merge_venue_id]" id="merge_venue_id" value="">
				</div>	
				</div>
			</div>
			<div class="row buttons">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
			</div>
	<?php $this->endWidget(); ?>

</div>
<!-- form -->