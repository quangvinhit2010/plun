<?php
	/* @var $model Background */
	$this->pageTitle=Yii::app()->name;
	$form = $model->getListImage();
	foreach($form as $f) :
?>
<div class="form-wrap">
	<form target="<?php echo $f['file_name'] ?>" enctype="multipart/form-data" id="upload-form" action="<?php echo $this->createUrl('default/config') ?>" method="post">
		<div for="Background_image"><?php echo $f['label'] ?></div>
		<div id="upload-new" class="button">
			<span>Upload new</span>
			<input name="image_upload" type="file">
		</div>
		<input type="hidden" value="<?php echo $f['file_name'] ?>" name="image_name">
		<input type="hidden" value="<?php echo $f['size'] ?>" name="image_size">
		<a class="button" href="<?php echo $this->createUrl('default/LoadOld', array('size'=>$f['size'], 'exclude'=>$f['file_name'])) ?>">Load avalable background</a>
		<img src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/uploading.gif" style="display: none;">
	</form>
	<img data-src="<?php echo '/uploads/background/' . $f['file_name'] . '.jpg' ?>" src="<?php echo '/uploads/background/' . $f['file_name'] . '.jpg?' . time() ?>" />
	<iframe id="<?php echo $f['file_name'] ?>" name="<?php echo $f['file_name'] ?>" style="display: none;"></iframe>
</div>
<?php endforeach; ?>
<div id="overlay">
	<div id="popup-wrap">
		<img id="loading-list" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/uploading.gif">
		<div id="popup"></div>
	</div>
</div>

<script>
	$('input[type=file]').change(function(){
		var formWrap = $(this).closest('.form-wrap');
		formWrap.find('> form').submit();
		formWrap.find('> form > img').show();
	});
	$('.form-wrap form a').click(function(){
		var self = $(this);
		var url = self.attr('href');
		$('#overlay, #loading-list').show();
		$('#overlay').data('current', self.closest('.form-wrap').find('iframe').attr('id'));
		$('#popup').html("");
		$.get( url, function( data ) {
			$('#popup').html(data);
			$('#loading-list').hide();
		});
		return false;
	});
	$('#overlay').click(function(e){
		if($(e.target).attr('id') == 'overlay')
			$(this).hide();
	});
	function uploadSuccess(callbackValue, error) {
		var formWrap = $('#'+callbackValue).closest('.form-wrap');
		var form = formWrap.find('> form');
		form['0'].reset();
		form.find('> img').hide();
		if(error != "")
			alert(error);
		else {
			var img = formWrap.find('> img');
			d = new Date();
			img.attr('src', img.data('src') + '?' + d.getTime());
		}
	}
</script>