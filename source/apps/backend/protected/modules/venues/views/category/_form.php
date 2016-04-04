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
	)); ?>

	<?php echo $form->errorSummary($model); ?>
			<div class="row">
				<h2><?php echo $form->labelEx($model,'title'); ?></h2>
				<div class="input-wrap">
				<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>500, 'value' => $model->title)); ?>
				<?php echo $form->error($model,'title'); ?>
				</div>
			</div>
			<div class="row">
				<h2><?php echo $form->labelEx($model,'description'); ?></h2>
				<div class="input-wrap">
				<?php echo $form->textArea($model,'description',array('rows' => 5, 'cols' => 80)); ?>
				<?php echo $form->error($model,'description'); ?>
				</div>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'thumbnail'); ?>
				<div class="input-wrap clearfix">
						<div class="tmb-wrap">
							<?php echo CHtml::activeFileField($model, 'thumbnail'); ?>
							<?php echo $form->hiddenField($model, 'thumbnail'); ?>
						</div>
						<div class="tmb-temp">
						<?php if(isset($model->thumbnail)) { ?>
							<?php echo $model->getThumbnail(array('height' => '100px')); ?>							
						<?php } ?>
						</div>
						<?php echo $form->error($model,'thumbnail'); ?>
				</div>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'country'); ?>
				<div class="input-wrap">
				<?php 
				$country	=	SysCountry::model()->findAll();
				echo CHtml::activeDropDownList($model, 'country_id', CHtml::listData( $country, 'id', 'name'),array('template'=>'{label}{input}', 'empty' => '-- Select country --', 'onchange' => 'changeCountry(this);'));?>
				
				</div>
			</div>
			<div class="row state_list" style="display: none;">
				<?php echo $form->labelEx($model,'State'); ?>
				<div class="list">				
				</div>
			</div>
			<div class="row city_list" style="display: none;">
				<?php echo $form->labelEx($model,'City'); ?>
				<div class="list">				
				</div>
			</div>	
			<div class="row district_list" style="display: none;">
				<?php echo $form->labelEx($model,'District'); ?>
				<div class="list">				
				</div>
			</div>								
	<div class="row">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo CHtml::dropDownList('CmsVenues[published]', $model->published, array(CmsVenues::STATUS_UNPUBLISHED=>'No', CmsVenues::STATUS_PUBLISHED=>'Yes')); ?>
		<?php echo $form->error($model,'published'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div>
<!-- form -->

<script>
	function changeCountry(curr){
		var country_id	=	$(curr).val();
		var data	=	{
				country_id: country_id
		};
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/admin/venues/venues/getState',
	        success: function(data) {
	        	if(data != ''){
					$('.state_list').show();
					$('.state_list .list').html(data);
			    }else{
			    	$('.state_list').hide();
			    	$('.city_list').hide();
			    	$('.district_list').hide();
				}
	        },
	        dataType: 'html'
	    });
	}
	function changeState(curr){
		var state_id	=	$(curr).val();
		var data	=	{
				state_id: state_id
		};
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/admin/venues/venues/getCity',
	        success: function(data) {
	        	if(data != ''){
					$('.city_list').show();
					$('.city_list .list').html(data);
			    }else{
			    	$('.city_list').hide();
			    	$('.district_list').hide();
				}
	        },
	        dataType: 'html'
	    });
	}	
</script>