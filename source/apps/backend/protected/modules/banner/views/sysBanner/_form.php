<?php
/* @var $this SysBannerController */
/* @var $model SysBanner */
/* @var $form CActiveForm */
?>

<p class="note">Fields with <span class="required">*</span> are required.</p>
<div class="form">
<form target="upload_frame" enctype="multipart/form-data" id="upload-form" action="<?php echo $this->createUrl('sysBanner/upload') ?>" method="post">
	<?php echo CHtml::activeLabelEx($model, 'image'); ?>
	<div id="upload-new" class="button">
		<span>Upload Image</span>
		<input style="margin: 0px;" name="image_upload" type="file">
	</div>
	<a class="button load-avalable" href="<?php echo $this->createUrl('sysBanner/loadAvalableImage') ?>">Load avalable image</a>
	<img style="display: none;" id="loading-list" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/uploading.gif">
</form>
<iframe id="upload_frame" name="upload_frame" style="display: none;"></iframe>
<?php if($model->full_path != NULL ): ?>
<img id="img-view" src="<?php echo $model->full_path ?>" height="100px" width="auto" />
<?php else: ?>
<img id="img-view" src="" height="100px" width="auto" style="display: none;" />
<?php endif; ?>
	
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-banner-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="display: none;">
		<?php echo $form->textField($model,'file_name',array('size'=>60,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'file_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', array(SysBanner::TYPE_W_160=>'W_160', SysBanner::TYPE_W_300=>'W_300')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo CHtml::dropDownList('SysBanner[status]', $model->status, array(SysBanner::STATUS_DISABLED=>'disabled', SysBanner::STATUS_ENABLED=>'enabled')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div id="overlay">
	<div id="popup-wrap">
		<img id="loading-list" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/uploading.gif">
		<div id="popup"></div>
	</div>
</div>
<script>
	$('#upload-new input').change(function(){
		$('#loading-list').show();
		$('#upload-form').submit();
	});
	$('.load-avalable').click(function(){
		var self = $(this);
		var url = self.attr('href');
		$('#overlay, #popup-wrap #loading-list').show();
		$('#popup').html("");
		$.get( url, function( data ) {
			$('#popup').html(data);
			$('#popup-wrap #loading-list').hide();
		});
		return false;
	});
	$('#overlay').click(function(e){
		if($(e.target).attr('id') == 'overlay')
			$(this).hide();
	});
	function uploadSuccess(path, fileName, error) {
		$('#loading-list').hide();
		if(error == "") {
			$('#SysBanner_file_name').val(fileName);
			$('#img-view').attr('src', path+fileName).show();
		} else 
			alert(error);
	}
</script>