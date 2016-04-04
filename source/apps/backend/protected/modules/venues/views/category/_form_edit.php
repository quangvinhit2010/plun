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
							<?php echo CHtml::fileField('thumbnail'); ?>
							<?php echo $form->hiddenField($model, 'thumbnail'); ?>
						</div>
						<div class="tmb-temp">
						<?php if(isset($model->thumbnail)) { ?>
							<?php echo $model->getImageThumbnail(); ?>							
						<?php } ?>
						</div>
						<?php echo $form->error($model,'thumbnail'); ?>
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
    	$('.state_list').hide();
    	$('.state_list .list').empty();
    	$('.city_list').hide();
    	$('.city_list .list').empty();
    	$('.district_list').hide();
    	$('.district_list .list').empty();
    			
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
			    }
	        },
	        dataType: 'html'
	    });
	}
	function changeState(curr){
    	$('.city_list').hide();
    	$('.city_list .list').empty();
    	$('.district_list').hide();
    	$('.district_list .list').empty();
    	
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
			    }
	        },
	        dataType: 'html'
	    });
	}	
	function changeCity(curr){
    	$('.district_list').hide();
    	$('.district_list .list').empty();
		var city_id	=	$(curr).val();
		var data	=	{
				city_id: city_id
		};
	    $.ajax({
	        type: "POST",
	        data: data,
	        url: '/admin/venues/venues/getDistrict',
	        success: function(data) {
	        	if(data != ''){
					$('.district_list').show();
					$('.district_list .list').html(data);
			    }
	        },
	        dataType: 'html'
	    });
	}
</script>