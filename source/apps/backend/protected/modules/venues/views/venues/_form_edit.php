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
				<h2><?php echo $form->labelEx($model,'address'); ?></h2>
				<div class="input-wrap">
				<?php echo $form->textField($model,'address',array('size'=>80,'maxlength'=>500, 'value' => $model->address)); ?>
				<?php echo $form->error($model,'address'); ?>
				</div>
			</div>			
			<div class="row">
				<h2><?php echo $form->labelEx($model,'latitude'); ?></h2>
				<div class="input-wrap">
				<?php echo $form->textField($model,'latitude',array('size'=>20,'maxlength'=>100, 'value' => $model->latitude)); ?>
				<?php echo $form->error($model,'latitude'); ?>
				</div>
			</div>			
			<div class="row">
				<h2><?php echo $form->labelEx($model,'longitude'); ?></h2>
				<div class="input-wrap">
				<?php echo $form->textField($model,'longitude',array('size'=>20,'maxlength'=>100, 'value' => $model->longitude)); ?>
				<?php echo $form->error($model,'longitude'); ?>
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
				<h2><?php echo $form->labelEx($model,'tags'); ?></h2>
				<div class="input-wrap">
				<?php echo $form->textArea($model,'tags',array('rows' => 5, 'cols' => 80)); ?>
				<?php echo $form->error($model,'tags'); ?>
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
			<div class="row state_list">
				<?php echo $form->labelEx($model,'cat_id'); ?>
				<div class="list">			
					<?php
						$criteria=new CDbCriteria;
						$state	=	CmsVenuesCategory::model()->findAll($criteria);
						echo CHtml::activeDropDownList($model, 'cat_id', CHtml::listData( $state, 'id', 'title'),array('template'=>'{label}{input}', 'empty' => '-- Select category --'));
					?>												
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
			<!--  state -->
			<?php if($model->state_id){ ?>
			<div class="row state_list">
				<?php echo $form->labelEx($model,'State'); ?>
				<div class="list">			
					<?php
						$criteria=new CDbCriteria;
						$criteria->addCondition("country_id = {$model->country_id}");
						$state	=	LocationState::model()->findAll($criteria);
						echo CHtml::activeDropDownList($model, 'state_id', CHtml::listData( $state, 'id', 'name'),array('template'=>'{label}{input}', 'empty' => '-- Select state --', 'onchange' => 'changeState(this);'));
					?>												
				</div>
			</div>
			<?php }else{ ?>
			<div class="row state_list" style="display: none;">
				<?php echo $form->labelEx($model,'State'); ?>
				<div class="list">														
				</div>
			</div>			
			<?php } ?>
			
			<!--  city -->
			<?php if($model->city_id){ ?>
			<div class="row city_list">
				<?php echo $form->labelEx($model,'City'); ?>
				<div class="list">
					<?php
						$criteria=new CDbCriteria;
						$criteria->addCondition("state_id = {$model->state_id}");					
						$city	=	SysCity::model()->findAll($criteria);
						echo CHtml::activeDropDownList($model, 'city_id', CHtml::listData( $city, 'id', 'name'),array('template'=>'{label}{input}', 'empty' => '-- Select city --', 'onchange' => 'changeCity(this);'));
					?>									
				</div>
			</div>	
			<?php }else{ ?>
			<div class="row city_list" style="display: none;">
				<?php echo $form->labelEx($model,'City'); ?>
				<div class="list">									
				</div>
			</div>			
			<?php } ?>
			
			<!--  district -->
			<?php if($model->district_id){ ?>
			<div class="row district_list">
				<?php echo $form->labelEx($model,'District'); ?>
				<div class="list">
					<?php
						$criteria=new CDbCriteria;
						$criteria->addCondition("city_id = {$model->city_id}");					
						$district	=	SysDistrict::model()->findAll($criteria);
						echo CHtml::activeDropDownList($model, 'district_id', CHtml::listData( $district, 'id', 'name'),array('template'=>'{label}{input}', 'empty' => '-- Select district --'));
					?>				
				</div>
			</div>
			<?php }else{ ?>	
				<div class="row district_list" style="display: none;">
					<?php echo $form->labelEx($model,'District'); ?>
					<div class="list">			
					</div>
				</div>			
			<?php } ?>
										
	<div class="row">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo CHtml::dropDownList('CmsVenues[published]', $model->published, array(CmsVenues::STATUS_UNPUBLISHED=>'No', CmsVenues::STATUS_PUBLISHED=>'Yes')); ?>
		<?php echo $form->error($model,'published'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'top_venue'); ?>
		<?php echo CHtml::dropDownList('CmsVenues[top_venue]', $model->top_venue, array(CmsVenues::NOTOP_VENUES =>'No', CmsVenues::TOP_VENUES=>'Yes')); ?>
		<?php echo $form->error($model,'top_venue'); ?>
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